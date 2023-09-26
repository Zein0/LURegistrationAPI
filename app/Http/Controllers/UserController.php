<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{

    public function login()
    {
        $credentials = request()->only('email', 'password');
        Config::set('jwt.user', 'App\Models\User');
        Config::set('auth.providers.users.model', User::class);
        try {
            if (!Auth::guard('user')->attempt($credentials)) {
                return response([
                    'success' => false,
                    'error' => 'your email and password are incorrect'
                ]);
            }

            $user = Auth::guard('user')->user();

            if (!$token = JWTAuth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
                return response()->json(['error' => 'Could not create token'], 500);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
        Log::error(compact('token'));
        return response([
            'id' => $user->id,
            'access_token' => compact('token')['token'],
            'user' => $user,
            'success' => true
        ]);
    }
}
