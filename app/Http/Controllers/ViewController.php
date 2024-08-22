<?php

namespace App\Http\Controllers;

use App\Models\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewController extends Controller
{
    public function rate(Request $request, int $experience_id): JsonResponse
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

        $view = DB::table('views')
            ->where('user_id', $user->id)
            ->where('experience_id', $experience_id)
            ->first();


        if ($view) {

            DB::table('views')
                ->where('user_id', $user->id)
                ->where('experience_id', $experience_id)
                ->update(['rate' => $request->rate]);

            return response()->json([
                'message' => 'Your rating has been updated'
            ], 200);
        } else {

            DB::table('views')->insert([
                'user_id' => $user->id,
                'experience_id' => $experience_id,
                'rate' => $request->rate
            ]);

            return response()->json([
                'message' => 'You rated the experience'
            ], 201);
        }
    }



    public function like_an_experience(Request $request, $experience_id): JsonResponse
    {
        $user = $request->user();

        $like = View::where('user_id', $user->id)
            ->where('experience_id',$experience_id)
            ->first();

        if ($like)
        {

            View::where('user_id', $user->id)
                ->where('experience_id',$experience_id)
                ->delete();

            return response()->json([
                'message' => 'You unliked this Experience',
            ], 200);
        }
        else
        {
            $like = new View();
            $like->user_id = $user->id;
            $like->experience_id = $experience_id;

            $like->like = true;



            if ($like->save())
            {
                return response()->json([
                    'message'=>'You Liked This Experience',
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

}
