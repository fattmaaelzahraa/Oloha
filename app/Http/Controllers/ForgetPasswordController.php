<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Models\User;
use App\Notifications\ResetPasswordVerificationNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function forgotPassword(ForgetPasswordRequest $request): JsonResponse
    {
        $input = $request->only('email');
        $user = User::where('email', $input)->first();
        $user->notify(new ResetPasswordVerificationNotification());
        $success['success'] = true;
        return response()->json([$success, 200]);

    }
}
