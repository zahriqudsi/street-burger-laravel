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
    public function update(Request $request, $id)
    {
        $info = RestaurantInfo::findOrFail($id);
        $info->update([
            'name' => $request->name ?? $info->name,
            'address' => $request->address ?? $info->address,
            'phone' => $request->phone ?? $info->phone,
            'email' => $request->email ?? $info->email,
            'opening_hours' => $request->openingHours ?? $request->opening_hours ?? $info->opening_hours,
            'about_us' => $request->aboutUs ?? $request->about_us ?? $info->about_us,
            'latitude' => $request->latitude ?? $info->latitude,
            'longitude' => $request->longitude ?? $info->longitude,
            'facebook_url' => $request->facebookUrl ?? $request->facebook_url ?? $info->facebook_url,
            'instagram_url' => $request->instagramUrl ?? $request->instagram_url ?? $info->instagram_url,
            'uber_eats_url' => $request->uberEatsUrl ?? $request->uber_eats_url ?? $info->uber_eats_url,
            'pickme_food_url' => $request->pickmeFoodUrl ?? $request->pickme_food_url ?? $info->pickme_food_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Restaurant info updated successfully',
            'data' => $info
        ]);
    }
}
