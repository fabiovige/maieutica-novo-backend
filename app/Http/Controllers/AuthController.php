<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['As credenciais fornecidas estão incorretas.'],
                ]);
            }

            $abilities = [];

            switch ($user->role) {
                case 'admin':
                    $abilities = ['create', 'read', 'update', 'delete'];
                    break;
                case 'professional':
                    $abilities = ['create', 'read', 'update'];
                    break;
                case 'parent':
                    $abilities = ['read', 'update'];
                    break;
            }

            $token = $user->createToken('auth_token', $abilities)->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'abilities' => $abilities,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Credenciais incorretas!',
            ], 401);
        }
    }

    public function logout(User $user)
    {
        try {
            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Deslogado com sucesso!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Não deslogado!',
            ], 400);
        }
    }
}
