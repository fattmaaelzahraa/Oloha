<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function GetUserData(Request $request, $id): JsonResponse
    {
        $user = DB::table('users')
            ->where('id', '=', $id)
            ->first();

        if ($user)
        {
            return response()->json([
                'status' => true,
                'message' => 'User data retrieved successfully',
                'data' => $user
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }
    }
}

//            ->select(
//                'users.id as User_id',
//                'users.name as User_name',
//                'users.email as User_email'
//            )
