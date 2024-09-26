<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function getUsers() {
        $users = UserResource::collection(User::get());

        return response()->json([
            'error' => false,
            'data' => $users
        ], 200);
    }

    public function isEmailExist(Request $request) {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'data' => [
                    'message' => $validator->messages()->first(),
                ]
            ], 400);
        }
        $isExist = User::where('email', request()->email)->first();

        return response()->json([
            'is_email_exist' => $isExist
        ]);
    }
}
