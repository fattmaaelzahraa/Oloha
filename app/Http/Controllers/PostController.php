<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function post(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $request->validate([
            'text' => 'nullable|string',
            'post_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('post_photo'))
        {
            $file = $request->file('post_photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'postsPhotos/' . $fileName;
            $file->move(public_path('storage/postsPhotos'), $fileName);
        }

        DB::table('posts')->insert([
            'user_id' => $user->id,
            'text' => $request->text,
            'post_photo' => $filePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $post_photo_url = $filePath ? url('storage/postsPhotos/' . $fileName) : null;

        return response()->json([
            'message' => 'Your post has been added successfully',
            'user_id' => $user->id,
            'text' => $request->text,
            'photo' => $post_photo_url
        ], 201);

    }

    public function edit_post(Request $request, $post_id): JsonResponse
    {
        $request->validate([
            'text' => 'required|string',
            'post_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $review = DB::table('posts')
            ->where('id', $post_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$review)
        {
            return response()->json([
                'message' => 'post not found or you are unauthorized to edit this post'
            ], 404);
        }

        DB::table('posts')
            ->where('id', $post_id)
            ->update([
                'text' => $request->text,
                'post_photo' => $request->post_photo,
                'updated_at' => now(),
            ]);

        return response()->json([
            'message' => 'Your post has been updated',
            'user_id'=>$user->id
        ], 200);

    }


    public function delete_post(Request $request, $post_id): JsonResponse
    {
        $user = $request->user();
        if (!$user)
        {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $post = DB::table('posts')
            ->where('id', $post_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$post)
        {
            return response()->json([
                'message' => 'post not found or you are unauthorized to delete this post'
            ], 404);
        }

        DB::table('posts')
            ->where('id', $post_id)
            ->delete();

        return response()->json([
            'message' => 'Your post has been deleted'
        ], 200);

    }


    public function sharePost(int $post_id): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $originalPost = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where('posts.id', $post_id)
            ->select('posts.*', 'users.id as creator_id', 'users.email as creator_email')
            ->first();

        if (!$originalPost) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }

        $sharedPostId = DB::table('posts')->insertGetId([
            'user_id' => $user->id,
            'text' => $originalPost->text,
            'post_photo' => $originalPost->post_photo,
            'original_post_id' => $post_id,
            'original_creator_id' => $originalPost->creator_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sharedPost = DB::table('posts')->find($sharedPostId);

        return response()->json([
            'message' => 'Post shared successfully',
            'shared_post' => [
                'id' => $sharedPost->id,
                'text' => $sharedPost->text,
                'post_photo' => $sharedPost->post_photo,
                'created_at' => $sharedPost->created_at,
                'updated_at' => $sharedPost->updated_at,
            ],
            'creator' => [
                'id' => $originalPost->creator_id,
            ],
            'sharer' => [
                'id' => $user->id,
            ]
        ], 201);
    }



    public function view_posts(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $posts = DB::table('posts as p1')
            ->join('users', 'p1.user_id', '=', 'users.id')
            ->leftJoin('posts as p2', 'p1.original_post_id', '=', 'p2.id')
            ->select('p1.*',
                'users.name as user_name',
                'p1.user_id as user_id',
                'p1.text as post',
                'p1.post_photo as post_photo',
                'p1.original_post_id as original_post_id',
                'p1.original_creator_id as original_creator_id',
                'p1.created_at')
            ->get();

        foreach ($posts as $post) {
            $post->post_photo_url = url('storage/' . $post->post_photo);
        }

        return response()->json([
            'posts' => $posts
        ], 200);
    }


    public function get_post(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $post = DB::table('posts')
            ->where('posts.id', '=', $id)
            ->first();

        if ($post)
        {
            $post_photo_url = url('storage/' . $post->post_photo);

            return response()->json([
                'status' => true,
                'message' => 'post opened successfully',
                'post_photo_url' => $post_photo_url,
                'data' => $post
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Post not found'
            ], 404);
        }
    }
}
