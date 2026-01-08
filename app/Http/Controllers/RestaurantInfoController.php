<?php

namespace App\Http\Controllers;

use App\Models\RestaurantInfo;
use Illuminate\Http\Request;

class RestaurantInfoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/restaurant-info/get/all",
     *     summary="Get all restaurant info",
     *     tags={"Restaurant Info"},
     *     @OA\Response(response=200, description="Restaurant info list")
     * )
     */
    public function getAll()
    {
        $info = RestaurantInfo::all();
        // Laravel's response needs to match the structure expected by the app
        // The app expects camelCase fields in the data, but our DB has snake_case.
        // We probably need to map them.

        $mappedInfo = $info->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'address' => $item->address,
                'phone' => $item->phone,
                'email' => $item->email,
                'openingHours' => $item->opening_hours,
                'aboutUs' => $item->about_us,
                'latitude' => (float) $item->latitude,
                'longitude' => (float) $item->longitude,
                'facebookUrl' => $item->facebook_url,
                'instagramUrl' => $item->instagram_url,
                'uberEatsUrl' => $item->uber_eats_url,
                'pickmeFoodUrl' => $item->pickme_food_url,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $mappedInfo
        ]);
    }
}
