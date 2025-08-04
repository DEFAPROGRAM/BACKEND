<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'caracteristicas',
        'order',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'order' => 'integer'
    ];
} 