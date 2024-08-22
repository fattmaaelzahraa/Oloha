<?php

namespace App\Http\Controllers;

use App\Models\React;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReactController extends Controller
{
    public function React(Request $request, $post_id): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $like = React::where('user_id', $user->id)
            ->where('post_id',$post_id)
            ->first();

        if ($like) {

            React::where('user_id', $user->id)
                ->where('post_id',$post_id)
                ->delete();

            return response()->json([
                'message' => 'You unliked this Post',
            ], 200);
        }
        else {
            $like = new React();
            $like->user_id = $user->id;
            $like->post_id = $post_id;

            $like->react = true;



            if ($like->save()) {
                return response()->json([
                    'message'=>'You Liked This Post',
                    'like'=>$like
                ],201);
            }else{
                return response()->json([
                    'message'=>'some error occurred, please try again'
                ], 500);
            }
        }
    }


    public function reactors_list(Request $request, $post_id): JsonResponse
    {
        $user = $request->user();

        if (!$user)
        {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }


        $postExists = DB::table('posts')
                        ->where('id', $post_id)
                        ->exists();
        if (!$postExists)
        {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }


        $reactors = DB::table('reacts')
            ->join('users', 'reacts.user_id', '=', 'users.id')
            ->where('reacts.post_id', $post_id)
            ->select('users.id',
                'users.name',
                'reacts.created_at')
            ->get();

        return response()->json([
            'reactors' => $reactors
        ], 200);



    }
}
