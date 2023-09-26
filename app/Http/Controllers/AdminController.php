<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AdminController extends Controller
{

    public function login()
    {
        // $credentials = request()->only('email', 'password');
        // Config::set('jwt.user', Admin::class);
        // Config::set('auth.providers.users.model', Admin::class);
        // try {
        //     if (!Auth::guard('admin')->attempt($credentials)) {
        //         return response([
        //             'success' => false,
        //             'error' => 'your email and password are incorrect'
        //         ]);
        //     }
        //     $admin = Auth::guard('admin')->user();
        //     if (!$token = JWTAuth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
        //         return response()->json(['error' => 'Could not create token'], 500);
        //     }
        // } catch (JWTException $e) {
        //     Log::error($e);
        //     return response()->json(['error' => 'Could not create token'], 500);
        // }

        return response([
            // 'id' => $admin->id,
            // 'access_token' => compact('token')['token'],
            // 'user' => $admin,
            'success' => true
        ]);
    }
}
