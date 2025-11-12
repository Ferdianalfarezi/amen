<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File2D extends Model
{
    use HasFactory;

    protected $table = 'files_2d';

    protected $fillable = [
        'drawing_id',
        'nama',
        'file_path',
        'thumbnail_path',
        'tipe_file',
        'mime_type',
        'ukuran',
        'deskripsi',
    ];

    /**
     * Relasi ke Drawing
     */
    public function drawing()
    {
        return $this->belongsTo(Drawing::class);
    }

    /**
     * Get formatted file size
     */
    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->ukuran;
        
        if ($bytes == 0) return '0 Bytes';
        
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
}