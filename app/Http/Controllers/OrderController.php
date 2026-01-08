<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/orders/add",
     *     summary="Place a new order",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=201, description="Order placed")
     * )
     */
    public function placeOrder(Request $request)
    {
        $user = auth('api')->user();

        if (!$request->items || count($request->items) == 0) {
            return response()->json(['success' => false, 'message' => 'Order must contain items'], 400);
        }

        return DB::transaction(function () use ($request, $user) {
            $totalAmount = 0;
            $itemsToSave = [];

            foreach ($request->items as $item) {
                // Frontend sends menuItemId
                $menuItemId = $item['menuItemId'] ?? $item['menu_item_id'];
                $quantity = $item['quantity'];

                $menuItem = MenuItem::findOrFail($menuItemId);
                $price = $menuItem->price;
                $totalAmount += $price * $quantity;

                $itemsToSave[] = [
                    'menu_item_id' => $menuItemId,
                    'quantity' => $quantity,
                    'price' => $price
                ];
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'PENDING',
                'order_type' => $request->orderType ?? $request->order_type ?? 'PICKUP',
                'phone_number' => $request->phoneNumber ?? $request->phone_number ?? $user->phone_number,
                'delivery_address' => $request->deliveryAddress ?? $request->delivery_address,
                'notes' => $request->notes,
            ]);

            foreach ($itemsToSave as $itemData) {
                $order->items()->create($itemData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'data' => $this->mapOrder($order->load('items.menuItem'))
            ], 201);
        });
    }

    /**
     * @OA\Get(
     *     path="/api/orders/mine",
     *     summary="Get current user history",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of orders")
     * )
     */
    public function getMyOrders()
    {
        $user = auth('api')->user();
        $orders = Order::with('items.menuItem')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $orders->map(fn($o) => $this->mapOrder($o))
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/all",
     *     summary="Get all orders (Admin)",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of all orders")
     * )
     */
    public function getAllOrders()
    {
        $orders = Order::with(['user', 'items.menuItem'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $orders->map(fn($o) => $this->mapOrder($o))
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/orders/update-status/{id}",
     *     summary="Update order status",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Status updated")
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order status updated',
            'data' => $this->mapOrder($order->load('items.menuItem'))
        ]);
    }

    private function mapOrder($order)
    {
        return [
            'id' => $order->id,
            'user' => [
                'id' => $order->user_id,
                'name' => $order->user->name ?? null,
                'phoneNumber' => $order->user->phone_number ?? null,
            ],
            'items' => $order->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'menuItem' => [
                        'id' => $item->menu_item_id,
                        'title' => $item->menuItem->title ?? 'Deleted Item',
                        'price' => (float) $item->price,
                        'imageUrl' => $item->menuItem->image_url ?? null,
                    ],
                    'quantity' => $item->quantity,
                    'price' => (float) $item->price,
                ];
            }),
            'totalAmount' => (float) $order->total_amount,
            'status' => $order->status,
            'orderType' => $order->order_type,
            'phoneNumber' => $order->phone_number,
            'deliveryAddress' => $order->delivery_address,
            'notes' => $order->notes,
            'createdAt' => $order->created_at,
            'updatedAt' => $order->updated_at,
        ];
    }
}
