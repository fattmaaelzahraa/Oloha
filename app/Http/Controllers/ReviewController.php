<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function Review(Request $request, int $place_id): JsonResponse
    {
        $request->validate([
            'description' => 'required|string',
            'rate' => 'required|numeric|min:0|max:5',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();
        if (!$user)
        {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $placeExists = DB::table('places')
                         ->where('id', $place_id)
                         ->exists();
        if (!$placeExists)
        {
            return response()->json([
                'message' => 'Place not found'
            ], 404);
        }

        $filePath = null;
        if ($request->hasFile('photo_path'))
        {
            $file = $request->file('photo_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'reviewPhotos/' . $fileName;
            $file->move(public_path('storage/reviewPhotos'), $fileName);
        }

        DB::table('reviews')->insert([
            'user_id' => $user->id,
            'place_id' => $place_id,
            'description' => $request->description,
            'rate' => $request->rate,
            'photo_path' => $filePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $photo_path_url = $filePath ? url('storage/reviewPhotos/' . $fileName) : null;

        return response()->json([
            'message' => 'Your review has been added',
            'user_id' => $user->id,
            'review' => $request->description,
            'rate' => $request->rate,
            'photo' => $photo_path_url
        ], 201);

    }


    public function edit_review(Request $request, $review_id): JsonResponse
    {
        $request->validate([
            'description' => 'required|string',
            'rate' => 'required|numeric|min:0|max:5',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $review = DB::table('reviews')
            ->where('id', $review_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$review)
        {
            return response()->json([
                'message' => 'Review not found or you are unauthorized to edit this review'
            ], 404);
        }

        DB::table('reviews')
            ->where('id', $review_id)
            ->update([
                'description' => $request->description,
                'rate' => $request->rate,
                'photo_path' => $request->photo_path,
                'updated_at' => now(),
            ]);

        return response()->json([
            'message' => 'Your review has been updated',
            'user_id'=>$user->id
        ], 200);

    }


    public function delete_review(Request $request, $review_id): JsonResponse
    {
        $user = $request->user();
        if (!$user)
        {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $review = DB::table('reviews')
            ->where('id', $review_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$review)
        {
            return response()->json([
                'message' => 'Review not found or you are unauthorized to delete this review'
            ], 404);
        }

        DB::table('reviews')
            ->where('id', $review_id)
            ->delete();

        return response()->json([
            'message' => 'Your review has been deleted'
        ], 200);


    }


    public function view_reviews(Request $request, $place_id): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        if (!$place_id) {
            return response()->json([
                'message' => 'Place ID is required'
            ], 400);
        }

        $reviews = DB::table('reviews')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->join('places', 'reviews.place_id', '=', 'places.id')
            ->select(
                'reviews.id',
                'reviews.description',
                'reviews.rate',
                'reviews.photo_path',
                'users.name as user_name',
                'users.id as user_id',
                'places.name as place_name',
                'reviews.created_at',
                'reviews.updated_at'
            )
            ->where('reviews.place_id', '=', $place_id)
            ->get();

        return response()->json([
            'reviews' => $reviews
        ], 200);

    }
}
