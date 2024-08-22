<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phonenumber' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails())
        {
            return response()->json(
                $validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phonenumber' => $request->phonenumber,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('userToken')->accessToken;

        return response()->json([
            'success' => true,
//            $user->notify(new EmailVerificationNotification()),
            'message' => 'Registration successful',
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'phonenumber' => $user->phonenumber,
                'token' => $token, //personal_access_tokens table
            ]
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required', //for the email or the phone number
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $login = $request->login;
        $password = $request->password;

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $login, 'password' => $password];
        } else {
            $credentials = ['phonenumber' => $login, 'password' => $password];
        }

        if (auth()->attempt($credentials))
        {
            $user = auth()->user();
            $token = auth()->user()->createToken('userToken')->accessToken;

            return response()->json([
                'success' => true,
                'message' => 'login successful',
                'user_data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'phonenumber' => $user->phonenumber,
                    'token' => $token, //personal_access_tokens table
                ]
            ], 200);
        }
        else
        {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
    }



    public function logout(Request $request): JsonResponse
    {
        if ( $request->user()->token()->delete() )
        {
            return response()->json([
                'status' => true,
                'message' => 'User logged out successfully'
            ], 200);
        }
        else
        {
            return response()->json([
                'message'=>'Some error occurred, please try again'
            ], 500);
        }
    }
}
