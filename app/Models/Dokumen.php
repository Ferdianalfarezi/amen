<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $fillable = [
        'drawing_id',
        'nama',
        'file',
        'tipe_file',
    ];

    public function drawing()
    {
        return $this->belongsTo(Drawing::class);
    }

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file);
    }
}