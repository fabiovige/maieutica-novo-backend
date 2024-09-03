<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'name', 'email', 'role', 'phone', 'cep',
                'address', 'number', 'complement', 'neighborhood',
                'city', 'state',
            ]);

            $perPage = $request->input('per_page', 10);
            $users = $this->userService->getUsers($filters, $perPage);

            return response()->json($users, 200);

        } catch (\Exception $e) {
            Log::error('Falha ao buscar usuários:', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Falha ao buscar usuários!',
            ], 500);
        }
    }

    public function store(UserRequest $request): JsonResponse
    {
        // Verifica se o usuário autenticado pode criar usuários
        if (Auth::user()->cannot('create', User::class)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $user = $this->userService->createUser($request->all());

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Usuário cadastrado com sucesso!',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Falha ao cadastrar usuário!',
            ], 500);
        }
    }

    public function show(User $user): JsonResponse
    {
        // Verifica se o usuário autenticado pode visualizar este usuário
        if (Auth::user()->cannot('view', $user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($user);
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        // Verifica se o usuário autenticado pode atualizar este usuário
        if (Auth::user()->cannot('update', $user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $user = $this->userService->updateUser($user, $request->all());

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Usuário atualizado com sucesso!',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Falha ao atualizar o usuário!',
            ], 500);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        // Verifica se o usuário autenticado pode deletar este usuário
        if (Auth::user()->cannot('delete', $user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $this->userService->deleteUser($user);

            return response()->json([
                'status' => true,
                'message' => 'Usuário excluído com sucesso!',
            ], 204);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Falha ao excluir o usuário!',
            ], 500);
        }
    }
}
