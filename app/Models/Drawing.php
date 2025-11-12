<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drawing extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'user_id',
        'tahun_project',
        'customer',
        'project',
        'departemen',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke 3D Files (DIPERTAHANKAN)
     */
    public function files3d()
    {
        return $this->hasMany(File3D::class);
    }

    public function files2d()
    {
        return $this->hasMany(File2D::class);
    }

    /**
     * Relasi ke Sample Parts (BARU)
     */
    public function sampleParts()
    {
        return $this->hasMany(SamplePart::class);
    }

    /**
     * Relasi ke Qualities (BARU)
     */
    public function qualities()
    {
        return $this->hasMany(Quality::class);
    }

    /**
     * Relasi ke Setup Procedures (BARU)
     */
    public function setupProcedures()
    {
        return $this->hasMany(SetupProcedure::class);
    }

    /**
     * Relasi ke Quotes (BARU)
     */
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * Relasi ke Work Instructions (BARU)
     */
    public function workInstructions()
    {
        return $this->hasMany(WorkInstruction::class);
    }

    /**
     * DEPRECATED - Relasi lama yang akan dihapus
     * (Biarkan dulu untuk backward compatibility)
     */
    public function fotos()
    {
        return $this->hasMany(Foto::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class);
    }

    /**
     * Get first 3D file or sample part image as thumbnail
     */
    public function getThumbnailAttribute()
    {
        // Coba dari files3d dulu
        $firstFile = $this->files3d()->first();
        if ($firstFile) {
            return asset('storage/' . $firstFile->file_path);
        }

        // Coba dari sample parts yang image
        $firstSamplePart = $this->sampleParts()->whereIn('tipe_file', ['jpg', 'jpeg', 'png', 'gif', 'webp'])->first();
        if ($firstSamplePart) {
            return asset('storage/' . $firstSamplePart->file_path);
        }

        // Default thumbnail
        return asset('images/default-thumbnail.png');
    }

    /**
     * Get first 3D file type for badge
     */
    public function getFirstFileTypeAttribute()
    {
        $firstFile = $this->files3d()->first();
        return $firstFile ? strtoupper($firstFile->tipe_file) : 'N/A';
    }

    /**
     * Get total size of all 3D files
     */
    public function getTotalFileSizeAttribute()
    {
        $totalBytes = $this->files3d()->sum('ukuran');
        
        if ($totalBytes == 0) return '0 KB';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $totalBytes > 1024; $i++) {
            $totalBytes /= 1024;
        }
        
        return round($totalBytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get count of all files across all categories
     */
    public function getTotalFilesCountAttribute()
    {
        return $this->files3d()->count() +
               $this->sampleParts()->count() +
               $this->qualities()->count() +
               $this->setupProcedures()->count() +
               $this->quotes()->count() +
               $this->workInstructions()->count();
    }
}