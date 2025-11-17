<?php

namespace App\Http\Controllers;

use App\Models\Drawing;
use App\Models\File3D;
use App\Models\File2D;
use App\Models\SamplePart;
use App\Models\Quality;
use App\Models\SetupProcedure;
use App\Models\Quote;
use App\Models\WorkInstruction;
use App\Http\Requests\StoreDrawingRequest;
use App\Http\Requests\UpdateDrawingRequest;
use App\Services\ThumbnailService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory as ExcelIOFactory;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpPresentation\IOFactory as PptIOFactory;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

class DrawingController extends Controller
{
    protected $thumbnailService;

    public function __construct(ThumbnailService $thumbnailService)
    {
        $this->thumbnailService = $thumbnailService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $drawings = Drawing::with([
            'files3d', 
            'files2d',
            'sampleParts', 
            'qualities', 
            'setupProcedures', 
            'quotes', 
            'workInstructions', 
            'user'
        ])
        ->when($search, function($query) use ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                ->orWhere('project', 'like', "%{$search}%")
                ->orWhere('customer', 'like', "%{$search}%")
                ->orWhere('departemen', 'like', "%{$search}%")
                ->orWhere('tahun_project', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(12);

        return view('drawings.index', compact('drawings'));
    }

    public function create()
    {
        return view('drawings.create');
    }

    public function store(StoreDrawingRequest $request)
    {
        DB::beginTransaction();

        try {
            // Create drawing
            $drawing = Drawing::create([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'user_id' => Auth::id(),
                'tahun_project' => $request->tahun_project,
                'customer' => $request->customer,
                'project' => $request->project,
                'departemen' => $request->departemen,
            ]);

            // Upload HANYA files_2d (foto 2D)
            if ($request->hasFile('files_2d')) {
                foreach ($request->file('files_2d') as $index => $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filePath = $file->store('files_2d/' . $drawing->id, 'public');
                    
                    File2D::create([
                        'drawing_id' => $drawing->id,
                        'nama' => $request->file_2d_names[$index] ?? $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'tipe_file' => $extension,
                        'mime_type' => $file->getMimeType(),
                        'ukuran' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('drawings.show', $drawing)
                ->with('success', 'Drawing berhasil ditambahkan! Anda bisa menambahkan file 3D dan file lainnya di tab yang tersedia.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(Drawing $drawing)
    {
        return view('drawings.show', compact('drawing'));
    }

    public function edit(Drawing $drawing)
    {
        $drawing->load(['files2d']);
        
        return view('drawings.edit', compact('drawing'));
    }

    public function update(UpdateDrawingRequest $request, Drawing $drawing)
    {
        DB::beginTransaction();

        try {
            // Update drawing basic info
            $drawing->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'tahun_project' => $request->tahun_project,
                'customer' => $request->customer,
                'project' => $request->project,
                'departemen' => $request->departemen,
            ]);

            // Add new files_2d (foto 2D)
            if ($request->hasFile('files_2d')) {
                foreach ($request->file('files_2d') as $index => $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filePath = $file->store('files_2d/' . $drawing->id, 'public');
                    
                    File2D::create([
                        'drawing_id' => $drawing->id,
                        'nama' => $request->file_2d_names[$index] ?? $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'tipe_file' => $extension,
                        'mime_type' => $file->getMimeType(),
                        'ukuran' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('drawings.show', $drawing)
                ->with('success', 'Drawing berhasil diupdate!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function destroy(Drawing $drawing)
    {
        try {
            // Hapus files2d
            $this->deleteAllFiles($drawing->files2d);
            
            // Hapus semua file dari setiap kategori lainnya
            $this->deleteAllFiles($drawing->files3d);
            $this->deleteAllFiles($drawing->sampleParts);
            $this->deleteAllFiles($drawing->qualities);
            $this->deleteAllFiles($drawing->setupProcedures);
            $this->deleteAllFiles($drawing->quotes);
            $this->deleteAllFiles($drawing->workInstructions);

            $drawing->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Drawing berhasil dihapus!'
                ]);
            }

            return redirect()->route('drawings.index')
                ->with('success', 'Drawing berhasil dihapus!');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Methods for Lazy Loading Tabs
     */

    public function files2d(Drawing $drawing)
    {
        return view('drawings.partials.files2d-tab', [
            'files' => $drawing->files2d, 
            'drawing' => $drawing
        ]);
    }

    public function files3d(Drawing $drawing)
    {
        return view('drawings.partials.files3d-tab', [
            'files' => $drawing->files3d, 
            'drawing' => $drawing
        ]);
    }

    public function sampleParts(Drawing $drawing)
    {
        return view('drawings.partials.sample-parts-tab', [
            'files' => $drawing->sampleParts, 
            'drawing' => $drawing
        ]);
    }

    public function qualities(Drawing $drawing)
    {
        return view('drawings.partials.quality-tab', [
            'files' => $drawing->qualities, 
            'drawing' => $drawing
        ]);
    }

    public function setupProcedures(Drawing $drawing)
    {
        return view('drawings.partials.setup-procedure-tab', [
            'files' => $drawing->setupProcedures, 
            'drawing' => $drawing
        ]);
    }

    public function quotes(Drawing $drawing)
    {
        return view('drawings.partials.quotes-tab', [
            'files' => $drawing->quotes, 
            'drawing' => $drawing
        ]);
    }

    public function workInstructions(Drawing $drawing)
    {
        return view('drawings.partials.work-instructions-tab', [
            'files' => $drawing->workInstructions, 
            'drawing' => $drawing
        ]);
    }

    /**
     * HELPER METHODS
     */
    private function handleFileUpload($request, $drawing, $inputName, $modelClass, $storagePath)
    {
        if ($request->hasFile($inputName)) {
            foreach ($request->file($inputName) as $index => $file) {
                $extension = $file->getClientOriginalExtension();
                $filePath = $file->store($storagePath, 'public');
                
                // Generate thumbnail jika bukan image
                $thumbnailPath = null;
                if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $thumbnailPath = $this->thumbnailService->generate($filePath, $extension);
                }
                
                $modelClass::create([
                    'drawing_id' => $drawing->id,
                    'nama' => $request->{$inputName . '_names'}[$index] ?? $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'thumbnail_path' => $thumbnailPath,
                    'tipe_file' => $extension,
                    'mime_type' => $file->getMimeType(),
                    'ukuran' => $file->getSize(),
                    'deskripsi' => $request->{$inputName . '_descriptions'}[$index] ?? null,
                ]);
            }
        }
    }

    private function deleteAllFiles($files)
    {
        foreach ($files as $file) {
            // Delete main file
            Storage::disk('public')->delete($file->file_path);
            
            // Delete thumbnail if exists
            if (!empty($file->thumbnail_path)) {
                Storage::disk('public')->delete($file->thumbnail_path);
            }
        }
    }

    /**
     * DELETE METHODS
     */
    public function deleteFile3D($id)
    {
        // Check permission
        if (!auth()->user()->hasPermission('files_3d', 'delete')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus file.'
            ], 403);
        }

        return $this->deleteFile(File3D::class, $id, '3D File');
    }

    public function deleteFile2D($id)
    {
        // Check permission
        if (!auth()->user()->hasPermission('files_2d', 'delete')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus file.'
            ], 403);
        }

        return $this->deleteFile(File2D::class, $id, '2D File');
    }

    public function deleteSamplePart($id)
    {
        // Check permission
        if (!auth()->user()->hasPermission('sample_parts', 'delete')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus file.'
            ], 403);
        }

        return $this->deleteFile(SamplePart::class, $id, 'Sample Part');
    }

    public function deleteQuality($id)
    {
        // Check permission
        if (!auth()->user()->hasPermission('quality', 'delete')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus file.'
            ], 403);
        }

        return $this->deleteFile(Quality::class, $id, 'Quality File');
    }

    public function deleteSetupProcedure($id)
    {
        // Check permission
        if (!auth()->user()->hasPermission('setup_procedures', 'delete')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus file.'
            ], 403);
        }

        return $this->deleteFile(SetupProcedure::class, $id, 'Setup Procedure');
    }

    public function deleteQuote($id)
    {
        // Check permission
        if (!auth()->user()->hasPermission('quotes', 'delete')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus file.'
            ], 403);
        }

        return $this->deleteFile(Quote::class, $id, 'Quote');
    }

    public function deleteWorkInstruction($id)
    {
        // Check permission
        if (!auth()->user()->hasPermission('work_instructions', 'delete')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus file.'
            ], 403);
        }

        return $this->deleteFile(WorkInstruction::class, $id, 'Work Instruction');
    }

    private function deleteFile($modelClass, $id, $fileType)
    {
        try {
            $file = $modelClass::findOrFail($id);
            
            // Delete main file
            Storage::disk('public')->delete($file->file_path);
            
            // Delete thumbnail if exists
            if (!empty($file->thumbnail_path)) {
                Storage::disk('public')->delete($file->thumbnail_path);
            }
            
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => $fileType . ' berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus ' . $fileType . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * UPLOAD METHODS - dengan thumbnail generation
     */
    public function uploadFile2D(Request $request, Drawing $drawing)
    {
        // Check permission
        if (!auth()->user()->hasPermission('files_2d', 'upload')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk upload file.'
            ], 403);
        }

        $request->validate([
            'file' => 'required|file|max:200000',
            'nama' => 'nullable|string|max:255',
        ]);

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filePath = $file->store('files_2d/' . $drawing->id, 'public');
            
            File2D::create([
                'drawing_id' => $drawing->id,
                'nama' => $request->nama ?? $file->getClientOriginalName(),
                'file_path' => $filePath,
                'tipe_file' => $extension,
                'mime_type' => $file->getMimeType(),
                'ukuran' => $file->getSize(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '2D File berhasil diupload!'
                ]);
            }

            return redirect()->route('drawings.show', $drawing)
                ->with('success', '2D File berhasil diupload!');
                
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal upload file: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }

    public function uploadFile3D(Request $request, Drawing $drawing)
    {
        // Check permission
        if (!auth()->user()->hasPermission('files_3d', 'upload')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk upload file.'
            ], 403);
        }

        $request->validate([
            'file' => 'required|file|max:200000',
            'nama' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            
            $uniqueName = uniqid() . '_' . time() . '.' . $extension;
            $filePath = $file->storeAs('files_3d', $uniqueName, 'public');
            
            // Generate thumbnail (opsional untuk 3D files)
            $thumbnailPath = $this->thumbnailService->generate($filePath, $extension);
            
            File3D::create([
                'drawing_id' => $drawing->id,
                'nama' => $request->nama ?? $originalName,
                'file_path' => $filePath,
                'thumbnail_path' => $thumbnailPath,
                'tipe_file' => $extension,
                'mime_type' => $file->getMimeType(),
                'ukuran' => $file->getSize(),
                'deskripsi' => $request->deskripsi,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '3D File berhasil diupload!'
                ]);
            }

            return redirect()->route('drawings.show', $drawing)
                ->with('success', '3D File berhasil diupload!');
                
        } catch (\Exception $e) {
            Log::error('Upload Error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal upload file: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }

    public function uploadSamplePart(Request $request, Drawing $drawing)
    {
        // Check permission
        if (!auth()->user()->hasPermission('sample_parts', 'upload')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk upload file.'
            ], 403);
        }

        $request->validate([
            'file' => 'required|file|max:200000',
            'nama' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filePath = $file->store('sample_parts/' . $drawing->id, 'public');
            
            // Generate thumbnail untuk non-image files
            $thumbnailPath = null;
            if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $thumbnailPath = $this->thumbnailService->generate($filePath, $extension);
            }
            
            SamplePart::create([
                'drawing_id' => $drawing->id,
                'nama' => $request->nama ?? $file->getClientOriginalName(),
                'file_path' => $filePath,
                'thumbnail_path' => $thumbnailPath,
                'tipe_file' => $extension,
                'mime_type' => $file->getMimeType(),
                'ukuran' => $file->getSize(),
                'deskripsi' => $request->deskripsi,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sample Part berhasil diupload!'
                ]);
            }

            return redirect()->route('drawings.show', $drawing)
                ->with('success', 'Sample Part berhasil diupload!');
                
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal upload file: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }

    public function uploadQuality(Request $request, Drawing $drawing)
    {
        // Check permission
        if (!auth()->user()->hasPermission('quality', 'upload')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk upload file.'
            ], 403);
        }

        return $this->handleSingleUpload($request, $drawing, Quality::class, 'qualities', 'Quality File');
    }

    public function uploadSetupProcedure(Request $request, Drawing $drawing)
    {
        // Check permission
        if (!auth()->user()->hasPermission('setup_procedures', 'upload')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk upload file.'
            ], 403);
        }

        return $this->handleSingleUpload($request, $drawing, SetupProcedure::class, 'setup_procedures', 'Setup Procedure');
    }

    public function uploadQuote(Request $request, Drawing $drawing)
    {
        // Check permission
        if (!auth()->user()->hasPermission('quotes', 'upload')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk upload file.'
            ], 403);
        }

        return $this->handleSingleUpload($request, $drawing, Quote::class, 'quotes', 'Quote');
    }

    public function uploadWorkInstruction(Request $request, Drawing $drawing)
    {
        // Check permission
        if (!auth()->user()->hasPermission('work_instructions', 'upload')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk upload file.'
            ], 403);
        }

        return $this->handleSingleUpload($request, $drawing, WorkInstruction::class, 'work_instructions', 'Work Instruction');
    }

    /**
     * Helper untuk upload file dengan thumbnail
     */
    private function handleSingleUpload(Request $request, Drawing $drawing, $modelClass, $storagePath, $fileType)
    {
        $request->validate([
            'file' => 'required|file|max:200000',
            'nama' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filePath = $file->store($storagePath . '/' . $drawing->id, 'public');
            
            // Generate thumbnail
            $thumbnailPath = null;
            if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $thumbnailPath = $this->thumbnailService->generate($filePath, $extension);
            }
            
            $modelClass::create([
                'drawing_id' => $drawing->id,
                'nama' => $request->nama ?? $file->getClientOriginalName(),
                'file_path' => $filePath,
                'thumbnail_path' => $thumbnailPath,
                'tipe_file' => $extension,
                'mime_type' => $file->getMimeType(),
                'ukuran' => $file->getSize(),
                'deskripsi' => $request->deskripsi,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $fileType . ' berhasil diupload!'
                ]);
            }

            return redirect()->route('drawings.show', $drawing)
                ->with('success', $fileType . ' berhasil diupload!');
                
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal upload file: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }

    // Preview methods remain the same...
    public function previewDocument(Request $request, $path)
    {
        $filePath = storage_path('app/public/' . $path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $previewDir = storage_path('app/public/previews');
        if (!file_exists($previewDir)) mkdir($previewDir, 0777, true);

        $outputPdf = $previewDir . '/' . pathinfo($filePath, PATHINFO_FILENAME) . '.pdf';

        try {
            switch ($ext) {
                case 'xlsx':
                case 'xls':
                    $spreadsheet = ExcelIOFactory::load($filePath);
                    $sheet = $spreadsheet->getActiveSheet();

                    $highestColumn = $sheet->getHighestColumn();
                    $columnCount = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                    for ($col = 1; $col <= $columnCount; $col++) {
                        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                        $dimension = $sheet->getColumnDimension($columnLetter);
                        $dimension->setAutoSize(true);
                    }

                    $sheet->calculateColumnWidths();

                    $maxWidth = $columnCount > 8 ? 20 : 25;
                    for ($col = 1; $col <= $columnCount; $col++) {
                        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                        $dimension = $sheet->getColumnDimension($columnLetter);

                        if ($dimension->getWidth() > $maxWidth) {
                            $dimension->setWidth($maxWidth);
                            $dimension->setAutoSize(false);
                        }
                    }

                    $pageSetup = $sheet->getPageSetup();
                    $pageSetup->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                    $pageSetup->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

                    $pageSetup->setFitToWidth(0);
                    $pageSetup->setFitToHeight(0);
                    $pageSetup->setScale(100);

                    if ($columnCount > 12) {
                        $pageSetup->setScale(75);
                    } else {
                        $pageSetup->setFitToWidth(1);
                        $pageSetup->setFitToHeight(0);
                    }

                    $sheet->getPageMargins()->setTop(0.4);
                    $sheet->getPageMargins()->setBottom(0.4);
                    $sheet->getPageMargins()->setLeft(0.5);
                    $sheet->getPageMargins()->setRight(0.5);

                    $sheet->unfreezePane('A1');
                    $sheet->getPageSetup()->setPrintArea($sheet->calculateWorksheetDimension());

                    $sheet->setShowGridlines(true);

                    $writer = new Mpdf($spreadsheet);
                    $writer->save($outputPdf);
                    break;

                case 'doc':
                case 'docx':
                    $phpWord = WordIOFactory::load($filePath);
                    
                    \PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF);
                    \PhpOffice\PhpWord\Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));
                    
                    $pdfWriter = WordIOFactory::createWriter($phpWord, 'PDF');
                    $pdfWriter->save($outputPdf);
                    break;

                case 'ppt':
                case 'pptx':
                    $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
                    
                    if ($isWindows) {
                        $libreofficeCmd = '"C:\Program Files\LibreOffice\program\soffice.exe"';
                    } else {
                        $libreofficeCmd = 'libreoffice';
                    }
                    
                    $command = sprintf(
                        '%s --headless --convert-to pdf --outdir %s %s 2>&1',
                        $libreofficeCmd,
                        escapeshellarg($previewDir),
                        escapeshellarg($filePath)
                    );
                    
                    exec($command, $output, $returnVar);
                    
                    if ($returnVar === 0 && file_exists($outputPdf)) {
                        return redirect(asset('storage/previews/' . basename($outputPdf)));
                    } else {
                        abort(500, 'Gagal mengonversi PPT ke PDF: ' . implode("\n", $output));
                    }
                    break;

                case 'pdf':
                    return redirect(asset('storage/' . $path));

                default:
                    abort(400, 'Format file tidak didukung untuk preview.');
            }

            return redirect(asset('storage/previews/' . basename($outputPdf)));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function previewPpt($id)
    {
        $file = SamplePart::findOrFail($id);
        $filePath = storage_path('app/public/' . $file->file_path);
        
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $previewDir = storage_path('app/public/previews/ppt_' . $id);
        if (!is_dir($previewDir)) {
            mkdir($previewDir, 0755, true);
        }

        $existingSlides = glob($previewDir . '/slide_*.png');
        
        if (empty($existingSlides)) {
            $success = $this->generatePptSlidesWithLibreOffice($filePath, $previewDir);
            
            if (!$success) {
                return response()->json([
                    'error' => 'Failed to generate presentation slides',
                    'fallback_url' => asset('storage/' . $file->file_path)
                ], 500);
            }
        }

        $slides = [];
        $slideFiles = glob($previewDir . '/slide_*.png');
        sort($slideFiles);
        
        foreach ($slideFiles as $slideFile) {
            $slides[] = asset('storage/previews/ppt_' . $id . '/' . basename($slideFile));
        }

        if (empty($slides)) {
            return response()->json([
                'error' => 'No slides generated',
                'fallback_url' => asset('storage/' . $file->file_path)
            ], 500);
        }

        return response()->json([
            'success' => true,
            'slides' => $slides,
            'filename' => $file->nama,
            'total' => count($slides)
        ]);
    }

    private function generatePptSlides($filePath, $fileId)
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        
        if ($isWindows) {
            $commands = [
                'soffice --version 2>nul',
                '"C:\Program Files\LibreOffice\program\soffice.exe" --version 2>nul',
            ];
            
            $libreofficeCmd = null;
            foreach ($commands as $cmd) {
                $output = @shell_exec($cmd);
                if ($output && stripos($output, 'libreoffice') !== false) {
                    $libreofficeCmd = explode(' --version', $cmd)[0];
                    $libreofficeCmd = trim($libreofficeCmd, '"');
                    break;
                }
            }
            
            if (!$libreofficeCmd) {
                Log::error('LibreOffice not found for PPT preview');
                return false;
            }
        } else {
            $libreofficeCmd = 'libreoffice';
        }

        $previewDir = storage_path('app/public/previews/ppt_' . $fileId);
        if (!is_dir($previewDir)) {
            mkdir($previewDir, 0755, true);
        }

        if ($isWindows) {
            $command = sprintf(
                '"%s" --headless --convert-to png --outdir "%s" "%s" 2>&1',
                $libreofficeCmd,
                $previewDir,
                $filePath
            );
        } else {
            $command = sprintf(
                'libreoffice --headless --convert-to png --outdir %s %s 2>&1',
                escapeshellarg($previewDir),
                escapeshellarg($filePath)
            );
        }

        Log::info("Converting PPT to images", ['command' => $command]);
        exec($command, $output, $returnVar);
        Log::info("PPT conversion result", ['return' => $returnVar, 'output' => implode("\n", $output)]);

        return $this->convertPptViaPdf($filePath, $previewDir, $isWindows, $libreofficeCmd);
    }

    private function convertPptViaPdf($filePath, $previewDir, $isWindows, $libreofficeCmd)
    {
        $tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('ppt_pdf_');
        mkdir($tempDir, 0755, true);

        if ($isWindows) {
            $command = sprintf(
                '"%s" --headless --convert-to pdf --outdir "%s" "%s" 2>&1',
                $libreofficeCmd,
                $tempDir,
                $filePath
            );
        } else {
            $command = sprintf(
                'libreoffice --headless --convert-to pdf --outdir %s %s 2>&1',
                escapeshellarg($tempDir),
                escapeshellarg($filePath)
            );
        }

        exec($command, $output, $returnVar);
        
        $pdfFile = $tempDir . DIRECTORY_SEPARATOR . pathinfo($filePath, PATHINFO_FILENAME) . '.pdf';
        
        if (!file_exists($pdfFile)) {
            Log::error("Failed to convert PPT to PDF");
            $this->deleteDirectory($tempDir);
            return false;
        }

        Log::info("PPT converted to PDF: {$pdfFile}");

        $this->convertPdfToImages($pdfFile, $previewDir);

        $this->deleteDirectory($tempDir);

        return true;
    }

    private function convertPdfToImages($pdfFile, $outputDir)
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        
        if (extension_loaded('imagick')) {
            try {
                $imagick = new \Imagick();
                $imagick->setResolution(150, 150);
                $imagick->readImage($pdfFile);
                
                $numPages = $imagick->getNumberImages();
                
                foreach ($imagick as $pageNum => $page) {
                    $page->setImageFormat('png');
                    $page->setImageCompression(\Imagick::COMPRESSION_JPEG);
                    $page->setImageCompressionQuality(85);
                    $page->thumbnailImage(800, 0);
                    
                    $outputFile = $outputDir . DIRECTORY_SEPARATOR . sprintf('slide_%03d.png', $pageNum + 1);
                    $page->writeImage($outputFile);
                    
                    Log::info("Generated slide image: {$outputFile}");
                }
                
                $imagick->clear();
                $imagick->destroy();
                
                return true;
            } catch (\Exception $e) {
                Log::warning("Imagick failed, trying GhostScript: " . $e->getMessage());
            }
        }

        if ($isWindows) {
            $gsCmds = ['gswin64c', 'gswin32c', 'gs'];
            $gsCmd = null;
            
            foreach ($gsCmds as $cmd) {
                $check = @shell_exec("{$cmd} -version 2>nul");
                if ($check && stripos($check, 'ghostscript') !== false) {
                    $gsCmd = $cmd;
                    break;
                }
            }
        } else {
            $gsCmd = 'gs';
        }

        if (!$gsCmd) {
            Log::error("Neither Imagick nor GhostScript available for PDF to images");
            return false;
        }

        $outputPattern = $outputDir . DIRECTORY_SEPARATOR . 'slide_%03d.png';
        
        if ($isWindows) {
            $command = sprintf(
                '"%s" -dSAFER -dBATCH -dNOPAUSE -sDEVICE=png16m -r150 -sOutputFile="%s" "%s" 2>&1',
                $gsCmd,
                $outputPattern,
                $pdfFile
            );
        } else {
            $command = sprintf(
                '%s -dSAFER -dBATCH -dNOPAUSE -sDEVICE=png16m -r150 -sOutputFile=%s %s 2>&1',
                $gsCmd,
                escapeshellarg($outputPattern),
                escapeshellarg($pdfFile)
            );
        }

        exec($command, $output, $returnVar);
        
        if ($returnVar === 0) {
            Log::info("PDF converted to images with GhostScript");
            return true;
        }

        Log::error("Failed to convert PDF to images", ['output' => implode("\n", $output)]);
        return false;
    }

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