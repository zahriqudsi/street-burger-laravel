<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Chef;
use App\Models\GalleryImage;
use App\Models\RestaurantInfo;
use Illuminate\Support\Facades\DB;

class ProductionSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MenuCategory::truncate();
        MenuItem::truncate();
        Chef::truncate();
        GalleryImage::truncate();
        RestaurantInfo::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Restaurant Info
        RestaurantInfo::create([
            'name' => 'Street Burger',
            'address' => 'No. 123, Burger Street, Colombo',
            'phone' => '0112345678',
            'email' => 'info@streetburger.lk',
            'opening_hours' => 'Mon-Sun: 10:00 AM - 11:00 PM',
            'about_us' => 'The best burgers in town, made with fresh ingredients and love.',
            'latitude' => 6.927079,
            'longitude' => 79.861244,
            'facebook_url' => 'https://facebook.com/streetburger',
            'instagram_url' => 'https://instagram.com/streetburger',
        ]);

        // Categories
        $burgers = MenuCategory::create(['name' => 'Burgers', 'display_order' => 1]);
        $sides = MenuCategory::create(['name' => 'Sides', 'display_order' => 2]);

        // Items
        MenuItem::create([
            'category_id' => $burgers->id,
            'title' => 'Classic Cheeseburger',
            'description' => 'Juicy beef patty with cheddar cheese',
            'price' => 1200.00,
            'is_popular' => true,
        ]);

        MenuItem::create([
            'category_id' => $sides->id,
            'title' => 'French Fries',
            'description' => 'Crispy golden fries',
            'price' => 450.00,
        ]);

        // Chefs
        Chef::create([
            'name' => 'Chef Mario',
            'role' => 'Head Chef',
            'bio' => 'Expert in gourmet burgers with 15 years of experience.',
        ]);

        // Gallery
        GalleryImage::create([
            'image_url' => 'https://example.com/burger1.jpg',
            'title' => 'Our Signature Burger',
        ]);
    }
}
