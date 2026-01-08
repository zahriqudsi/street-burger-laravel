<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/gallery",
     *     summary="Get all gallery images",
     *     tags={"Gallery"},
     *     @OA\Response(response=200, description="List of images")
     * )
     */
    public function getAll()
    {
        $images = GalleryImage::all();
        $mapped = $images->map(function ($item) {
            return [
                'id' => $item->id,
                'imageUrl' => $item->image_url,
                'caption' => $item->title ?? $item->caption,
                'displayOrder' => $item->display_order,
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
     *     path="/api/gallery",
     *     summary="Add a new gallery image",
     *     tags={"Gallery"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=201, description="Image added")
     * )
     */
    public function add(Request $request)
    {
        $image = GalleryImage::create([
            'image_url' => $request->imageUrl ?? $request->image_url,
            'title' => $request->caption ?? $request->title,
            'category' => $request->category,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Image added',
            'data' => $image
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/gallery/{id}",
     *     summary="Delete a gallery image",
     *     tags={"Gallery"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Image deleted")
     * )
     */
    public function delete($id)
    {
        $image = GalleryImage::find($id);
        if (!$image) {
            return response()->json(['success' => false, 'message' => 'Image not found'], 404);
        }
        $image->delete();
        return response()->json(['success' => true, 'message' => 'Image deleted']);
    }
}
