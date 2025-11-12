<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File3D extends Model
{
    use HasFactory;

    protected $table = 'files_3d';

    protected $fillable = [
        'drawing_id',
        'nama',
        'file_path',
        'tipe_file',
        'ukuran',
    ];

    /**
     * Relationship with Drawing
     */
    public function drawing()
    {
        return $this->belongsTo(Drawing::class);
    }

    /**
     * Get full URL for 3D file
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeFormattedAttribute()
    {
        if (!$this->ukuran) return '0 KB';
        
        $bytes = $this->ukuran;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}