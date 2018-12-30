<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json([
            'success' => false,
            'message' => 'Email tidak ditemukan!',
            'data' => ''
        ], 404);

        if (Hash::check($request->password, $user->password)) {
            $api_token = base64_encode(str_random(40));
            $user->update([
                'api_token' => $api_token
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil!',
                'data' => [
                    'user' => $user,
                    'api_token' => $api_token
                ]
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kombinasi password tidak cocok!',
            'data' => ''
        ], 401);
    }

    public function logout()
    {
        $user = app('auth')->user();

        $user->api_token = null;

        if ($user->update()) {
            return response()->json([
                'success' => true,
                'description' => 'Berhasil logout.'
            ], 201);
        }

        return response()->json([
            'success' => false,
            'description' => 'Gagal logout pada server.'
        ], 417);
    }
}