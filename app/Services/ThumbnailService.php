<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ThumbnailService
{
    /**
     * Generate thumbnail berdasarkan tipe file
     */
    public function generate($filePath, $extension)
    {
        $fullPath = storage_path('app/public/' . $filePath);
        
        // Cek apakah file exists
        if (!file_exists($fullPath)) {
            Log::warning("File not found for thumbnail: {$fullPath}");
            return null;
        }
        
        try {
            $extension = strtolower($extension);
            
            switch ($extension) {
                case 'pdf':
                    return $this->generatePdfThumbnail($fullPath, $filePath);
                    
                case 'ppt':
                case 'pptx':
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                    return $this->generateOfficeThumbnail($fullPath, $filePath, $extension);
                    
                case 'mp4':
                case 'avi':
                case 'mov':
                case 'webm':
                case 'mkv':
                    return $this->generateVideoThumbnail($fullPath, $filePath);
                    
                default:
                    return null;
            }
        } catch (\Exception $e) {
            Log::error('Thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate PDF thumbnail
     */
    private function generatePdfThumbnail($fullPath, $filePath)
    {
        // Try Imagick first (if available)
        if (extension_loaded('imagick')) {
            try {
                return $this->generatePdfThumbnailWithImagick($fullPath, $filePath);
            } catch (\Exception $e) {
                Log::warning('Imagick failed, trying GhostScript: ' . $e->getMessage());
            }
        }

        // Fallback: GhostScript (recommended untuk Windows)
        Log::info('Using GhostScript for PDF thumbnail');
        return $this->generatePdfThumbnailWithGhostscript($fullPath, $filePath);
    }

    /**
     * Generate PDF thumbnail menggunakan Imagick
     */
    private function generatePdfThumbnailWithImagick($fullPath, $filePath)
    {
        $thumbnailPath = $this->getThumbnailPath($filePath);
        $outputPath = storage_path('app/public/' . $thumbnailPath);
        
        $dir = dirname($outputPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $imagick = new \Imagick();
        $imagick->setResolution(150, 150);
        $imagick->readImage($fullPath . '[0]');
        $imagick->setImageFormat('jpg');
        $imagick->setImageCompression(\Imagick::COMPRESSION_JPEG);
        $imagick->setImageCompressionQuality(85);
        $imagick->thumbnailImage(400, 0);
        $imagick->writeImage($outputPath);
        $imagick->clear();
        $imagick->destroy();

        Log::info("PDF Thumbnail created (Imagick): {$thumbnailPath}");
        return $thumbnailPath;
    }

    /**
     * Generate PDF thumbnail menggunakan GhostScript
     */
    private function generatePdfThumbnailWithGhostscript($fullPath, $filePath)
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        
        // Cek GhostScript
        if ($isWindows) {
            $gsCmds = ['gswin64c', 'gswin32c', 'gs'];
            $gsCmd = null;
            
            foreach ($gsCmds as $cmd) {
                $check = @shell_exec("{$cmd} -version 2>nul");
                if ($check && stripos($check, 'ghostscript') !== false) {
                    $gsCmd = $cmd;
                    Log::info("Found GhostScript command: {$cmd}");
                    break;
                }
            }
            
            if (!$gsCmd) {
                Log::warning('GhostScript not found on Windows');
                return null;
            }
        } else {
            $gs = trim(shell_exec('which gs 2>/dev/null'));
            if (empty($gs)) {
                Log::warning('GhostScript not installed on Linux');
                return null;
            }
            $gsCmd = 'gs';
        }

        try {
            $thumbnailPath = $this->getThumbnailPath($filePath);
            $outputPath = storage_path('app/public/' . $thumbnailPath);
            
            $dir = dirname($outputPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // Generate PNG dari halaman pertama PDF
            $tempPng = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('pdf_') . '.png';
            
            if ($isWindows) {
                // Windows command - pastikan path di-quote dengan benar
                $command = sprintf(
                    '"%s" -dSAFER -dBATCH -dNOPAUSE -sDEVICE=png16m -r150 -dFirstPage=1 -dLastPage=1 -sOutputFile="%s" "%s" 2>&1',
                    $gsCmd,
                    $tempPng,
                    $fullPath
                );
            } else {
                $command = sprintf(
                    '%s -dSAFER -dBATCH -dNOPAUSE -sDEVICE=png16m -r150 -dFirstPage=1 -dLastPage=1 -sOutputFile=%s %s 2>&1',
                    $gsCmd,
                    escapeshellarg($tempPng),
                    escapeshellarg($fullPath)
                );
            }

            Log::info("Executing GhostScript", ['command' => $command]);
            exec($command, $output, $returnVar);
            Log::info("GhostScript output", ['return' => $returnVar, 'output' => implode("\n", $output)]);

            if (file_exists($tempPng)) {
                Log::info("PNG generated successfully: {$tempPng}");
                
                // Convert PNG ke JPG dengan resize menggunakan GD
                if (extension_loaded('gd')) {
                    $this->convertPngToJpg($tempPng, $outputPath);
                    unlink($tempPng);
                    
                    Log::info("PDF Thumbnail created (GhostScript): {$thumbnailPath}");
                    return $thumbnailPath;
                } else {
                    Log::error('GD extension not available for image conversion');
                    unlink($tempPng);
                }
            } else {
                Log::warning("GhostScript failed to generate PNG", [
                    'expected_file' => $tempPng,
                    'return_code' => $returnVar,
                    'output' => implode("\n", $output)
                ]);
            }

            return null;
            
        } catch (\Exception $e) {
            Log::error('PDF Thumbnail (GhostScript) Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate Office Document thumbnail menggunakan LibreOffice
     */
    private function generateOfficeThumbnail($fullPath, $filePath, $extension)
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        
        if ($isWindows) {
            $commands = [
                'soffice --version 2>nul',
                '"C:\Program Files\LibreOffice\program\soffice.exe" --version 2>nul',
                'libreoffice --version 2>nul',
            ];
            
            $libreofficeFound = false;
            $libreofficeCmd = 'soffice';
            
            foreach ($commands as $cmd) {
                $output = @shell_exec($cmd);
                if ($output && stripos($output, 'libreoffice') !== false) {
                    $libreofficeFound = true;
                    $libreofficeCmd = explode(' --version', $cmd)[0];
                    $libreofficeCmd = trim($libreofficeCmd, '"');
                    break;
                }
            }
            
            if (!$libreofficeFound) {
                Log::warning('LibreOffice not found on Windows');
                return null;
            }
        } else {
            $libreoffice = trim(shell_exec('which libreoffice 2>/dev/null'));
            if (empty($libreoffice)) {
                Log::warning('LibreOffice not installed on Linux');
                return null;
            }
            $libreofficeCmd = 'libreoffice';
        }

        try {
            $thumbnailPath = $this->getThumbnailPath($filePath);
            $outputPath = storage_path('app/public/' . $thumbnailPath);
            
            $dir = dirname($outputPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('libreoffice_');
            mkdir($tempDir, 0755, true);

            if ($isWindows) {
                $command = sprintf(
                    '"%s" --headless --convert-to png --outdir "%s" "%s" 2>&1',
                    $libreofficeCmd,
                    $tempDir,
                    $fullPath
                );
            } else {
                $command = sprintf(
                    'libreoffice --headless --convert-to png --outdir %s %s 2>&1',
                    escapeshellarg($tempDir),
                    escapeshellarg($fullPath)
                );
            }

            Log::info("Executing LibreOffice", ['command' => $command]);
            exec($command, $output, $returnVar);
            Log::info("LibreOffice output", ['return' => $returnVar, 'output' => implode("\n", $output)]);

            $pngFile = $tempDir . DIRECTORY_SEPARATOR . pathinfo($fullPath, PATHINFO_FILENAME) . '.png';

            if (file_exists($pngFile)) {
                Log::info("PNG file generated: {$pngFile}");
                
                if (extension_loaded('imagick')) {
                    $imagick = new \Imagick($pngFile);
                    $imagick->setImageFormat('jpg');
                    $imagick->setImageCompression(\Imagick::COMPRESSION_JPEG);
                    $imagick->setImageCompressionQuality(85);
                    $imagick->thumbnailImage(400, 0);
                    $imagick->writeImage($outputPath);
                    $imagick->clear();
                    $imagick->destroy();
                } elseif (extension_loaded('gd')) {
                    $this->convertPngToJpg($pngFile, $outputPath);
                } else {
                    Log::warning('Neither Imagick nor GD available');
                    copy($pngFile, str_replace('.jpg', '.png', $outputPath));
                    $thumbnailPath = str_replace('.jpg', '.png', $thumbnailPath);
                }

                unlink($pngFile);
                $this->deleteDirectory($tempDir);

                Log::info("Office Thumbnail created: {$thumbnailPath}");
                return $thumbnailPath;
            } else {
                Log::warning("PNG file not generated", [
                    'expected' => $pngFile,
                    'temp_dir_contents' => @scandir($tempDir)
                ]);
            }

            $this->deleteDirectory($tempDir);
            return null;
            
        } catch (\Exception $e) {
            Log::error('Office Thumbnail Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate Video thumbnail
     */
    private function generateVideoThumbnail($fullPath, $filePath)
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        
        if ($isWindows) {
            $ffmpegCheck = @shell_exec('ffmpeg -version 2>nul');
            if (!$ffmpegCheck || stripos($ffmpegCheck, 'ffmpeg') === false) {
                Log::warning('FFmpeg not installed on Windows');
                return null;
            }
            $ffmpegCmd = 'ffmpeg';
        } else {
            $ffmpeg = trim(shell_exec('which ffmpeg 2>/dev/null'));
            if (empty($ffmpeg)) {
                Log::warning('FFmpeg not installed on Linux');
                return null;
            }
            $ffmpegCmd = 'ffmpeg';
        }

        try {
            $thumbnailPath = $this->getThumbnailPath($filePath);
            $outputPath = storage_path('app/public/' . $thumbnailPath);
            
            $dir = dirname($outputPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            if ($isWindows) {
                $command = sprintf(
                    'ffmpeg -i "%s" -ss 00:00:01 -vframes 1 -vf "scale=400:-1" "%s" 2>&1',
                    $fullPath,
                    $outputPath
                );
            } else {
                $command = sprintf(
                    'ffmpeg -i %s -ss 00:00:01 -vframes 1 -vf "scale=400:-1" %s 2>&1',
                    escapeshellarg($fullPath),
                    escapeshellarg($outputPath)
                );
            }

            exec($command, $output, $returnVar);

            if ($returnVar === 0 && file_exists($outputPath)) {
                Log::info("Video Thumbnail created: {$thumbnailPath}");
                return $thumbnailPath;
            }

            Log::warning("FFmpeg failed", ['return' => $returnVar]);
            return null;
            
        } catch (\Exception $e) {
            Log::error('Video Thumbnail Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get thumbnail path
     */
    private function getThumbnailPath($filePath)
    {
        $pathInfo = pathinfo($filePath);
        $dir = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        
        return $dir . '/thumb_' . $filename . '.jpg';
    }

    /**
     * Convert PNG to JPG using GD
     */
    private function convertPngToJpg($pngPath, $jpgPath)
    {
        $image = @imagecreatefrompng($pngPath);
        
        if (!$image) {
            Log::error("Failed to create image from PNG: {$pngPath}");
            return false;
        }
        
        list($width, $height) = getimagesize($pngPath);
        $newWidth = 400;
        $newHeight = (int)(($height / $width) * $newWidth);
        
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        
        // White background
        $white = imagecolorallocate($resized, 255, 255, 255);
        imagefill($resized, 0, 0, $white);
        
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagejpeg($resized, $jpgPath, 85);
        
        imagedestroy($image);
        imagedestroy($resized);
        
        return true;
    }

    /**
     * Delete directory recursively
     */
    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = @array_diff(@scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? $this->deleteDirectory($path) : @unlink($path);
        }
        @rmdir($dir);
    }
}