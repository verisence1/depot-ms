<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],

            'role' => [
                'required',
                'in:depot_manager,operator,auditor',
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        $token = $user
            ->createToken('api-token')
            ->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully.',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where(
            'email',
            $credentials['email']
        )->first();

        if (
            ! $user ||
            ! Hash::check(
                $credentials['password'],
                $user->password
            )
        ) {

            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user
            ->createToken('api-token')
            ->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'message' =>
            'Logged out successfully.',
        ]);
    }
}
