<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantInfo extends Model
{
    protected $table = 'restaurant_info';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'opening_hours',
        'about_us',
        'latitude',
        'longitude',
        'facebook_url',
        'instagram_url',
        'uber_eats_url',
        'pickme_food_url',
    ];
}
