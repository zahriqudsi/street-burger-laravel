<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chef extends Model
{
    protected $fillable = [
        'name',
        'role',
        'bio',
        'image_url',
    ];

    protected $casts = [
        // Add any necessary casts here
    ];
}
