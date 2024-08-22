<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitController extends Controller
{
    public function like_unlike(Request $request, $place_id): JsonResponse
    {
        $user = $request->user();

        $like = Visit::where('user_id', $user->id)
                     ->where('place_id',$place_id)
                     ->first();

        if ($like)
        {

            Visit::where('user_id', $user->id)
                 ->where('place_id',$place_id)
                 ->delete();

            return response()->json([
                'message' => 'Removed from Favourites',
            ], 200);
        }
        else
        {
            $like = new Visit();
            $like->user_id = $user->id;
            $like->place_id = $place_id;

            $like->favourite = true;



            if ($like->save())
            {
                return response()->json([
                    'message'=>'Added to Favourites',
                    'like'=>$like
                ],201);
            }
            else
            {
                return response()->json([
                    'message'=>'some error occurred, please try again'
                ], 500);
            }
        }
    }


    public function LikedPlaces(): JsonResponse
    {
        $user = Auth::user();
        $likedPlaces = $user->likedPlaces()
                            ->where('favourite', true)
                            ->get();

        return response()->json($likedPlaces, 200);
    }

}
