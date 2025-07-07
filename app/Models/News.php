<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image_path',
        'published_at',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'published_at' => 'datetime',
    ];
}
