<?php

namespace App\Http\Controllers\Api\Pos;

use App\Http\Controllers\Controller;
use App\Settings\GeneralSettings;
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
        $outlets = $user->outlets()->select('outlets.id', 'outlets.name')->get();

        if ($outlets->isEmpty()) {
            return response()->json(['message' => 'No outlets assigned'], 403);
        }

        $token   = $user->createToken('pos-token')->plainTextToken;

        $generalSettings = app(GeneralSettings::class);

        return response()->json([
            'token'   => $token,
            'user'    => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'settings' => [
                'site_name' => $generalSettings->site_name,
                'site_logo' => $generalSettings->site_logo ? asset('storage/' . $generalSettings->site_logo) : null,
                'contact' => $generalSettings->contact,
                'address' => $generalSettings->address,
            ],
            'outlets' => $outlets,
        ]);
    }

    public function getLoggedInUser(Request $request)
    {
        $user = $request->user();
        return response()->json([$user]);
    }

    public function getLoggedInUserOutlets(Request $request)
    {
        $user = $request->user();
        $outlets = $user->outlets()->select('outlets.id', 'outlets.name')->get();
        return response()->json($outlets);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
