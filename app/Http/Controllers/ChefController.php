<?php

namespace App\Http\Controllers;

use App\Models\Chef;
use Illuminate\Http\Request;

class ChefController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/chefs",
     *     summary="Get all chefs",
     *     tags={"Chefs"},
     *     @OA\Response(response=200, description="List of chefs")
     * )
     */
    public function getAll()
    {
        $chefs = Chef::all();
        $mapped = $chefs->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'role' => $item->role,
                'bio' => $item->bio,
                'imageUrl' => $item->image_url,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $mapped
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/chefs",
     *     summary="Add a new chef",
     *     tags={"Chefs"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=201, description="Chef added")
     * )
     */
    public function add(Request $request)
    {
        $chef = Chef::create([
            'name' => $request->name,
            'role' => $request->role,
            'bio' => $request->bio,
            'image_url' => $request->imageUrl ?? $request->image_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Chef added',
            'data' => $chef
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/chefs/{id}",
     *     summary="Delete a chef",
     *     tags={"Chefs"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Chef deleted")
     * )
     */
    public function delete($id)
    {
        $chef = Chef::find($id);
        if (!$chef) {
            return response()->json(['success' => false, 'message' => 'Chef not found'], 404);
        }
        $chef->delete();
        return response()->json(['success' => true, 'message' => 'Chef deleted']);
    }
}
