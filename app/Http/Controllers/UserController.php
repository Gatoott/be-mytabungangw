<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function me(Request $request) {
        $user = $request->user();
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'silahkan login dulu'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function regis(Request $request) {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('auth-sanctum')->plainTextToken;

        return response()->json([
            'status' => 'succes',
            'message' => 'Berhasil membuat akun',
            'data' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if(!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username atau password salah'
            ]);
        }

        $user = Auth::user();

        $token = $user->createToken('auth-sanctum')->plainTextToken;
        
        return response()->json([
            'status' => 'succes',
            'message' => 'Berhasil login',
            'data' => $user,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }
}
