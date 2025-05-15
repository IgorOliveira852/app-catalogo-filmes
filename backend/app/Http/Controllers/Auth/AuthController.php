<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __invoke(AuthRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        $user = User::query()->where('email', $request->email)->firstOrFail();

        return response()->json([
            'message' => 'Invalid credentials',
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user,
        ], 401);
    }
}
