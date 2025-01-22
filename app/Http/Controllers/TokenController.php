<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessTokenResult;

class TokenController extends Controller
{
    public function generateToken(Request $request)
    {
        // Retrieve the user by ID (you can modify this logic)
        $user = User::find(1);

        if (!$user) {
            return response()->json(['error' => 'No user found to generate token'], 404);
        }

        // Revoke (delete) all current tokens for the user
        $user->tokens()->delete();

        // Generate a new token for the user
        $token = $user->createToken('Loyalty-System')->plainTextToken;

        return response()->json(['token' => $token]);
    }
}