<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * @OA\Response(response=200, description="List of notifications")
     * )
     */
    public function getAll()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $notifications->map(fn($n) => $this->mapNotification($n))
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/notification/getById/{id}",
     *     summary="Get notification by ID",
     *     tags={"Notifications"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Notification details")
     * )
     */
    public function getById($id)
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }
        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $this->mapNotification($notification)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/notification/add",
     *     summary="Add a new notification",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=201, description="Notification created")
     * )
     */
    public function add(Request $request)
    {
        $notification = Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'target_user_id' => $request->targetUserId ?? $request->target_user_id,
            'is_global' => $request->isGlobal ?? $request->is_global ?? true,
            'notification_type' => $request->notificationType ?? $request->notification_type ?? 'GENERAL',
            'image_url' => $request->imageUrl ?? $request->image_url,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification added',
            'data' => $this->mapNotification($notification)
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/notification/updateById/{id}",
     *     summary="Update a notification",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Notification updated")
     * )
     */
    public function update(Request $request, $id)
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        $notification->update([
            'title' => $request->title ?? $notification->title,
            'message' => $request->message ?? $notification->message,
            'target_user_id' => $request->targetUserId ?? $request->target_user_id ?? $notification->target_user_id,
            'is_global' => $request->isGlobal ?? $request->is_global ?? $notification->is_global,
            'notification_type' => $request->notificationType ?? $request->notification_type ?? $notification->notification_type,
            'image_url' => $request->imageUrl ?? $request->image_url ?? $notification->image_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification updated',
            'data' => $this->mapNotification($notification)
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/notification/deleteById/{id}",
     *     summary="Delete a notification",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Notification deleted")
     * )
     */
    public function delete($id)
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }
        $notification->delete();
        return response()->json(['success' => true, 'message' => 'Notification deleted']);
    }

    /**
     * @OA\Get(
     *     path="/api/notification/user",
     *     summary="Get notifications for current user",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of notifications")
     * )
     */
    public function getUserNotifications()
    {
        try {
            $user = auth('api')->user();
        } catch (\Exception $e) {
            $user = null;
        }

        if (!$user) {
            $notifications = Notification::where('is_global', true)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $notifications = Notification::where(function ($query) use ($user) {
                $query->where('is_global', true)
                    ->orWhere('target_user_id', $user->id);
            })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $notifications->map(fn($n) => $this->mapNotification($n))
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/notification/markRead/{id}",
     *     summary="Mark notification as read",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Notification marked as read")
     * )
     */
    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
                'data' => null
            ], 404);
        }

        $notification->is_read = true;
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'data' => $this->mapNotification($notification)
        ]);
    }

    private function mapNotification($item)
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'message' => $item->message,
            'targetUserId' => $item->target_user_id,
            'isGlobal' => (bool) $item->is_global,
            'notificationType' => $item->notification_type,
            'imageUrl' => $item->image_url,
            'isRead' => (bool) $item->is_read,
            'createdAt' => $item->created_at,
        ];
    }
}
