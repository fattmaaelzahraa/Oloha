<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Guide;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function rate(Request $request, $guide_id): JsonResponse
    {
        $request->validate([
            'rate' => 'required|numeric|min:0|max:5',
        ]);
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $feedback = DB::table('feedbacks')
            ->where('user_id', $user->id)
            ->where('guide_id', $guide_id)
            ->first();


        if ($feedback) {

            DB::table('feedbacks')
                ->where('user_id', $user->id)
                ->where('guide_id', $guide_id)
                ->update(['rate' => $request->rate]);

            return response()->json([
                'message' => 'Your rating has been updated'
            ], 200);
        } else {

            DB::table('feedbacks')->insert([
                'user_id' => $user->id,
                'guide_id' => $guide_id,
                'rate' => $request->rate
            ]);

            return response()->json([
                'message' => 'You rated the experience'
            ], 201);
        }
    }


    public function like(Request $request, $guide_id): JsonResponse
    {
        $user = $request->user();

        $like = Feedback::where('user_id', $user->id)
            ->where('guide_id', $guide_id)
            ->first();

        if ($like) {
            Feedback::where('user_id', $user->id)
                ->where('guide_id', $guide_id)
                ->delete();

            return response()->json([
                'message' => 'You Unliked This Guide',
            ], 200);
        } else {
            $like = new Feedback();
            $like->user_id = $user->id;
            $like->guide_id = $guide_id;

            $like->like = true;


            if ($like->save()) {
                return response()->json([
                    'message' => 'You Liked This Guide',
                    'like' => $like
                ], 201);
            } else {
                return response()->json([
                    'message' => 'some error occurred, please try again'
                ], 500);
            }
        }
    }


    public function LikedGuides(): JsonResponse
    {
        $user = Auth::user();
        $likedGuides = $user->likedGuides()
            ->where('like', true)
            ->get();

        return response()->json(
            [
                'message' => 'Liked Guides retrieved Successfully',
                'liked_Guides' => $likedGuides
            ]
            , 200);
    }


    public function review(Request $request, $guide_id): JsonResponse
    {
        $request->validate([
            'review' => 'required|string',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }


        $guideExists = DB::table('guides')
            ->where('id', $guide_id)
            ->exists();

        if (!$guideExists) {
            return response()->json([
                'message' => 'Guide not found'
            ], 404);
        }

        $feedbackExists = DB::table('feedbacks')
            ->where('user_id', $user->id)
            ->where('guide_id', $guide_id)
            ->exists();

        if ($feedbackExists) {
            DB::table('feedbacks')
                ->where('user_id', $user->id)
                ->where('guide_id', $guide_id)
                ->update([
                    'review' => $request->review,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'message' => 'Your review has been updated',
                'user_id' => $user->id,
                'review' => $request->review,
            ], 200);
        } else {
            DB::table('feedbacks')->insert([
                'user_id' => $user->id,
                'guide_id' => $guide_id,
                'review' => $request->review,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Your review has been added',
                'user_id' => $user->id,
                'review' => $request->review,
            ], 201);
        }
    }



    public function guide_reviews(Request $request, $guide_id): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        if (!$guide_id) {
            return response()->json([
                'message' => 'Guide ID is required'
            ], 400);
        }

        $reviews = DB::table('feedbacks')
            ->join('users', 'feedbacks.user_id', '=', 'users.id')
            ->join('guides', 'feedbacks.guide_id', '=', 'guides.id')
            ->select(
                'feedbacks.user_id',
                'users.name as user_name',
                'users.id as user_id',
                'guides.name as guide_name',
                'feedbacks.review as review',
                'feedbacks.rate as rate',
                'feedbacks.created_at'
            )
            ->where('feedbacks.guide_id', '=', $guide_id)
            ->get();

        return response()->json([
            'reviews' => $reviews
        ], 200);


    }
}
