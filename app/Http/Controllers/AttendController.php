<?php

namespace App\Http\Controllers;

use App\Models\Attend;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendController extends Controller
{
    public function like_event(Request $request, $event_id): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $like = Attend::where('user_id', $user->id)
                      ->where('event_id',$event_id)
                      ->first();

        if ($like) {

            Attend::where('user_id', $user->id)
                  ->where('event_id',$event_id)
                  ->delete();

            return response()->json([
                'message' => 'You unliked this Event',
            ], 200);
        }
        else
        {
            $like = new Attend();
            $like->user_id = $user->id;
            $like->event_id = $event_id;
            $like->like = true;

            if ($like->save())
            {
                return response()->json([
                    'message' => 'You Liked This Event',
                    'like' => $like
                ],201);
            }
            else
            {
                return response()->json([
                    'message' => 'some error occurred, please try again'
                ], 500);
            }
        }
    }


    public function liked_events(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $likedEvents = $user->likedEvents()
                            ->where('like', true)
                            ->get();

        return response()->json($likedEvents, 200);

    }
}
