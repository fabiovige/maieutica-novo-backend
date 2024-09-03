<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;
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
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Dados incorretos!'
            ], 401);
        }
    }

    public function logout(User $user) {
        try {
            $user->tokens()->delete();
            return response()->json([
                'status' => true,
                'message' => 'Deslogado com sucesso!'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Não deslogado!'
            ], 400);
        }
    }
}
