<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'drawing_id',
        'video',
    ];

    public function drawing()
    {
        return $this->belongsTo(Drawing::class);
    }

    public function getVideoUrlAttribute()
    {
        return asset('storage/' . $this->video);
    }
}