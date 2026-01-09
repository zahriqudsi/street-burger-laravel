<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/menu/categories",
     *     summary="Get all menu categories",
     *     tags={"Menu"},
     *     @OA\Response(response=200, description="List of categories")
     * )
     */
    public function getAllCategories()
    {
        $categories = MenuCategory::orderBy('display_order', 'asc')->get();
        $mapped = $categories->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'nameSi' => $item->name_si,
                'nameTa' => $item->name_ta,
                'displayOrder' => $item->display_order,
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
     * @OA\Get(
     *     path="/api/menu/items",
     *     summary="Get all menu items",
     *     tags={"Menu"},
     *     @OA\Response(response=200, description="List of items")
     * )
     */
    public function getAllItems()
    {
        $items = MenuItem::with('category')->where('is_available', true)->get();
        $mapped = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'categoryId' => $item->category_id,
                'category' => $item->category ? [
                    'id' => $item->category->id,
                    'name' => $item->category->name,
                ] : null,
                'title' => $item->title,
                'titleSi' => $item->title_si,
                'titleTa' => $item->title_ta,
                'description' => $item->description,
                'descriptionSi' => $item->description_si,
                'descriptionTa' => $item->description_ta,
                'price' => (float) $item->price,
                'imageUrl' => $item->image_url,
                'isAvailable' => (bool) $item->is_available,
                'isPopular' => (bool) $item->is_popular,
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
     * @OA\Get(
     *     path="/api/menu/items/{categoryId}",
     *     summary="Get items by category",
     *     tags={"Menu"},
     *     @OA\Parameter(name="categoryId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="List of items in category")
     * )
     */
    public function getItemsByCategory($categoryId)
    {
        $items = MenuItem::with('category')
            ->where('category_id', $categoryId)
            ->where('is_available', true)
            ->get();
        $mapped = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'categoryId' => $item->category_id,
                'category' => $item->category ? [
                    'id' => $item->category->id,
                    'name' => $item->category->name,
                ] : null,
                'title' => $item->title,
                'titleSi' => $item->title_si,
                'titleTa' => $item->title_ta,
                'description' => $item->description,
                'descriptionSi' => $item->description_si,
                'descriptionTa' => $item->description_ta,
                'price' => (float) $item->price,
                'imageUrl' => $item->image_url,
                'isAvailable' => (bool) $item->is_available,
                'isPopular' => (bool) $item->is_popular,
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
     * @OA\Get(
     *     path="/api/menu/items/popular",
     *     summary="Get popular items",
     *     tags={"Menu"},
     *     @OA\Response(response=200, description="List of popular items")
     * )
     */
    public function getPopularItems()
    {
        $items = MenuItem::with('category')
            ->where('is_popular', true)
            ->where('is_available', true)
            ->get();
        $mapped = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'categoryId' => $item->category_id,
                'category' => $item->category ? [
                    'id' => $item->category->id,
                    'name' => $item->category->name,
                ] : null,
                'title' => $item->title,
                'titleSi' => $item->title_si,
                'titleTa' => $item->title_ta,
                'description' => $item->description,
                'descriptionSi' => $item->description_si,
                'descriptionTa' => $item->description_ta,
                'price' => (float) $item->price,
                'imageUrl' => $item->image_url,
                'isAvailable' => (bool) $item->is_available,
                'isPopular' => (bool) $item->is_popular,
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
     *     path="/api/menu/items",
     *     summary="Add new item (Admin only)",
     *     tags={"Menu"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=201, description="Item created")
     * )
     */
    public function addItem(Request $request)
    {
        $item = MenuItem::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Item created',
            'data' => $item
        ], 201);
    }

    public function updateItem(Request $request, $id)
    {
        $item = MenuItem::findOrFail($id);
        $item->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Item updated',
            'data' => $item
        ]);
    }

    public function deleteItem($id)
    {
        $item = MenuItem::findOrFail($id);
        $item->delete();
        return response()->json([
            'success' => true,
            'message' => 'Item deleted',
            'data' => null
        ]);
    }

    public function addCategory(Request $request)
    {
        $category = MenuCategory::create([
            'name' => $request->name,
            'name_si' => $request->nameSi,
            'name_ta' => $request->nameTa,
            'display_order' => $request->displayOrder,
            'image_url' => $request->imageUrl,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Category created',
            'data' => $category
        ], 201);
    }

    public function updateCategory(Request $request, $id)
    {
        $category = MenuCategory::findOrFail($id);
        $category->update([
            'name' => $request->name ?? $category->name,
            'name_si' => $request->nameSi ?? $category->name_si,
            'name_ta' => $request->nameTa ?? $category->name_ta,
            'display_order' => $request->displayOrder ?? $category->display_order,
            'image_url' => $request->imageUrl ?? $category->image_url,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Category updated',
            'data' => $category
        ]);
    }

    public function deleteCategory($id)
    {
        $category = MenuCategory::findOrFail($id);
        $category->delete();
        return response()->json([
            'success' => true,
            'message' => 'Category deleted',
            'data' => null
        ]);
    }
}
