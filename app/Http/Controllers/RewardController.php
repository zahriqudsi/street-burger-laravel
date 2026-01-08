<?php

namespace App\Http\Controllers;

use App\Models\RewardPoint;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/rwdpts/addrwdpts",
     *     summary="Add reward points",
     *     tags={"Rewards"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"points","description"},
     *             @OA\Property(property="points", type="integer", example=10),
     *             @OA\Property(property="description", type="string", example="Order bonus")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Points added")
     * )
     */
    public function addPoints(Request $request)
    {
        $user = auth()->user();

        $points = RewardPoint::create([
            'user_id' => $user->id,
            'points' => $request->points,
            'description' => $request->description,
            'transaction_type' => 'EARNED',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Points added',
            'data' => $points
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/rwdpts/getrwdpts",
     *     summary="Get user reward points",
     *     tags={"Rewards"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Total points and transaction history")
     * )
     */
    public function getPoints()
    {
        $user = auth('api')->user();
        $history = RewardPoint::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $total = $history->sum('points');

        $mappedHistory = $history->map(function ($item) {
            return [
                'id' => $item->id,
                'userId' => $item->user_id,
                'points' => $item->points,
                'description' => $item->description,
                'transactionType' => $item->transaction_type,
                'createdAt' => $item->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => [
                'totalPoints' => (int) $total,
                'history' => $mappedHistory
            ]
        ]);
    }
}
