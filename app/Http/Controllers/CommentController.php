<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function comment(Request $request, $post_id): JsonResponse
    {
        $request->validate([
            'text' => 'required|string',
            'comment_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

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

        $filePath = null;
        if ($request->hasFile('comment_photo'))
        {
            $file = $request->file('comment_photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'commentPhotos/' . $fileName;
            $file->move(public_path('storage/commentPhotos'), $fileName);
        }

        DB::table('comments')->insert([
            'user_id' => $user->id,
            'post_id' => $post_id,
            'text' => $request->text,
            'comment_photo' => $filePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $comment_photo_url = $filePath ? url('storage/commentPhotos/' . $fileName) : null;

        return response()->json([
            'message' => 'Your comment has been added',
            'user_id' => $user->id,
            'text' => $request->text,
            'photo' => $comment_photo_url
        ], 201);

    }

    public function edit_comment(Request $request, $comment_id): JsonResponse
    {
        $request->validate([
            'text' => 'required|string',
            'comment_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $review = DB::table('comments')
            ->where('id', $comment_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$review)
        {
            return response()->json([
                'message' => 'comment not found or you are unauthorized to edit this comment'
            ], 404);
        }

        DB::table('comments')
            ->where('id', $comment_id)
            ->update([
                'text' => $request->text,
                'comment_photo' => $request->comment_photo,
                'updated_at' => now(),
            ]);

        return response()->json([
            'message' => 'Your comment has been updated',
            'user_id'=>$user->id
        ], 200);

    }

    public function delete_comment(Request $request, $comment_id): JsonResponse
    {
        $user = $request->user();
        if (!$user)
        {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $comment = DB::table('comments')
            ->where('id', $comment_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$comment)
        {
            return response()->json([
                'message' => 'comment not found or you are unauthorized to delete this comment'
            ], 404);
        }

        DB::table('comments')
            ->where('id', $comment_id)
            ->delete();

        return response()->json([
            'message' => 'Your comment has been deleted'
        ], 200);

    }

    public function view_comments(Request $request, $post_id): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        if (!$post_id) {
            return response()->json([
                'message' => 'Post ID is required'
            ], 400);
        }

        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->join('posts', 'comments.post_id', '=', 'posts.id')
            ->select(
                'comments.id as comment_id',
                'users.name as user_name',
                'users.id as user_id',
                'posts.text as post',
                'posts.original_creator_id as post_creator',
                'posts.post_photo as post_photo',
                'comments.text',
                'comments.comment_photo',
                'comments.created_at'
            )
            ->where('comments.post_id', '=', $post_id)
            ->get();

        return response()->json([
            'comments' => $comments
        ], 200);

    }
}
