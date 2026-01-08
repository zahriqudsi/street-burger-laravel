<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reviews/latest",
     *     summary="Get latest 10 reviews",
     *     tags={"Reviews"},
     *     @OA\Response(response=200, description="Latest reviews")
     * )
     */
    public function getLatestReviews()
    {
        $reviews = Review::where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $reviews->map(fn($r) => $this->mapReview($r))
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/reviews/add/{phoneNumber}",
     *     summary="Add a new review",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="phoneNumber", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rating","comment"},
     *             @OA\Property(property="rating", type="integer", example=5),
     *             @OA\Property(property="comment", type="string", example="Great burger!")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Review added")
     * )
     */
    public function addReview(Request $request, $phoneNumber)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ], 400);
        }

        $review = Review::create([
            'user_id' => $user->id,
            'phone_number' => $phoneNumber,
            'reviewer_name' => $user->name,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review added',
            'data' => $this->mapReview($review)
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/reviews",
     *     summary="Get all reviews",
     *     tags={"Reviews"},
     *     @OA\Response(response=200, description="List of reviews")
     * )
     */
    public function getAllReviews()
    {
        $reviews = Review::where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $reviews->map(fn($r) => $this->mapReview($r))
        ]);
    }

    private function mapReview($item)
    {
        return [
            'id' => $item->id,
            'userId' => $item->user_id,
            'phoneNumber' => $item->phone_number,
            'reviewerName' => $item->reviewer_name,
            'rating' => (int) $item->rating,
            'comment' => $item->comment,
            'isApproved' => (bool) $item->is_approved,
            'createdAt' => $item->created_at,
            'updatedAt' => $item->updated_at,
        ];
    }
}
