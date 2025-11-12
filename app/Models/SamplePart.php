<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SamplePart extends Model
{
    use HasFactory;

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

    public function drawing()
    {
        return $this->belongsTo(Drawing::class);
    }

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path);
        }
        return null;
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->ukuran;
        if ($bytes == 0) return '0 KB';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isImage()
    {
        return in_array(strtolower($this->tipe_file), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
    }

    public function isVideo()
    {
        return in_array(strtolower($this->tipe_file), ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm']);
    }

    public function isDocument()
    {
        return in_array(strtolower($this->tipe_file), ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
    }

    public function hasThumbnail()
    {
        return !empty($this->thumbnail_path) && file_exists(storage_path('app/public/' . $this->thumbnail_path));
    }
}