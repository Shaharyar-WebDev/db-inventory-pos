<?php

namespace App\Http\Controllers\Api\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user    = Auth::user();
        $token   = $user->createToken('pos-token')->plainTextToken;
        $outlets = $user->outlets()->select('outlets.id', 'outlets.name')->get();

        if ($outlets->isEmpty()) {
            return response()->json(['message' => 'No outlets assigned'], 403);
        }

        return response()->json([
            'token'   => $token,
            'user'    => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'outlets' => $outlets,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
