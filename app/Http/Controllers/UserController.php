<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users/allUsers",
     *     summary="Get all users (Admin)",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of all users")
     * )
     */
    public function getAllUsers()
    {
        $users = User::all();
        $mapped = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'phoneNumber' => $user->phone_number,
                'name' => $user->name,
                'email' => $user->email,
                'emailVerified' => (bool) $user->email_verified,
                'dateOfBirth' => $user->date_of_birth,
                'role' => $user->role,
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
     *     path="/api/users/me",
     *     summary="Get current user profile",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="User profile")
     * )
     */
    public function me()
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => [
                'id' => $user->id,
                'phoneNumber' => $user->phone_number,
                'name' => $user->name,
                'email' => $user->email,
                'emailVerified' => (bool) $user->email_verified,
                'dateOfBirth' => $user->date_of_birth,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/users/update-push-token",
     *     summary="Update user's push notification token",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(type="string", example="ExponentPushToken[xxx]"))
     *     ),
     *     @OA\Response(response=200, description="Push token updated")
     * )
     */
    public function updatePushToken(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // The frontend sends the raw string, sometimes Axios/Spring used to handle it as JSON.
        // We'll try to get it from request body or content.
        $token = $request->getContent();

        // Remove quotes if present
        if (strpos($token, '"') === 0 && strrpos($token, '"') === (strlen($token) - 1)) {
            $token = substr($token, 1, -1);
        }

        $user->push_token = $token;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Push token updated successfully',
            'data' => null
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/users/update",
     *     summary="Update current user profile",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Profile updated")
     * )
     */
    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('dateOfBirth') || $request->has('date_of_birth')) {
            $user->date_of_birth = $request->dateOfBirth ?? $request->date_of_birth;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'phoneNumber' => $user->phone_number,
                'name' => $user->name,
                'email' => $user->email,
                'emailVerified' => (bool) $user->email_verified,
                'dateOfBirth' => $user->date_of_birth,
                'role' => $user->role,
            ]
        ]);
    }
}
