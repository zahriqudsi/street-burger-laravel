<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    protected $fillable = [
        'name',
        'name_si',
        'name_ta',
        'display_order',
        'image_url',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }
}
