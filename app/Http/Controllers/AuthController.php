<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Info(
 *     title="Street Burger API",
 *     version="1.0.0",
 *     description="Street Burger Mobile Application Backend API"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/signup",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone_number","password","name"},
     *             @OA\Property(property="phone_number", type="string", example="0771234567"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User registered successfully"),
     *     @OA\Response(response=400, description="Validation error")
     * )
     */
    public function signup(Request $request)
    {
        $input = $request->all();
        if ($request->has('phoneNumber')) {
            $input['phone_number'] = $request->phoneNumber;
        }

        $validator = Validator::make($input, [
            'phone_number' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:6',
            'name' => 'required|string|max:100',
            'email' => 'nullable|string|email|max:100',
            'date_of_birth' => 'nullable|date',
            'dateOfBirth' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ], 400);
        }

        $user = User::create([
            'phone_number' => $input['phone_number'],
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth ?? $request->dateOfBirth,
            'role' => 'USER',
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'token' => $token,
                'id' => $user->id,
                'userId' => $user->id,
                'phoneNumber' => $user->phone_number,
                'name' => $user->name,
                'role' => $user->role,
            ]
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Login with phone number and password",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone_number","password"},
     *             @OA\Property(property="phone_number", type="string", example="0771234567"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request)
    {
        $phoneNumber = $request->phone_number ?? $request->phoneNumber;
        $credentials = [
            'phone_number' => $phoneNumber,
            'password' => $request->password
        ];

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number or password',
                'data' => null
            ], 401);
        }

        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'id' => $user->id,
                'userId' => $user->id,
                'phoneNumber' => $user->phone_number,
                'name' => $user->name,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/generatetoken/{phoneNumber}",
     *     summary="Generate JWT token for a phone number",
     *     tags={"Authentication"},
     *     @OA\Parameter(name="phoneNumber", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Token generated"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function generateToken($phoneNumber)
    {
        $user = User::where('phone_number', $phoneNumber)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Token generated',
            'data' => $token
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/auth/delete/account",
     *     summary="Delete user account",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Account deleted successfully")
     * )
     */
    public function deleteAccount()
    {
        $user = auth()->user();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully',
            'data' => null
        ]);
    }
}
