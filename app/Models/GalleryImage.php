<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = [
        'title',
        'image_url',
        'category',
    ];

    protected $casts = [
        // Add any necessary casts here
    ];
}
