<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    protected $userService;
        
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'name', 'email', 'role', 'phone', 'cep', 
                'address', 'number', 'complement', 'neighborhood', 
                'city', 'state'
            ]);
            Log::info('Busca todos os usuários:', $request->all());

            $perPage = $request->input('per_page', 10);
            $users = $this->userService->getUsers($filters, $perPage);

            return response()->json($users, 200);

        } catch (Exception $e) {
            Log::error('Falha ao buscar usuários:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Falha ao buscar usuários!'
            ], 500);
        }
    }

    public function store(UserRequest $request)
    {
        DB::beginTransaction();

        try {
            
            $user = User::create($request->all());
            Log::info('Cadastrando um novo usuário:', $request->all());

            DB::commit();
            Log::info('Usuário cadastrado com sucesso!', ['user' => $user->id]);
            
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Usuário cadastrado com sucesso!',
            ], 201);

        } catch (Exception $e) {
            
            DB::rollBack();
            Log::error('Falha ao cadastrar usuário:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'status' => false,
                'message' => 'Falha ao cadastrar usuário!'
            ], 500);

        }
    }

    public function show(User $user)
    {
        try {
            
            Log::info('Buscando usuário com ID:', ['user_id' => $user->id]);
            return response()->json($user);

        } catch (Exception $e) {
            Log::error('Falha ao exibir usuário com ID:', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return response()->json([
                'staus' => false,
                'message' => 'Falha ao exibir usuário!'
            ], 500);
        }
    }

    /**
     * 
     * @param \App\Http\Requests\UserRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, User $user) : JsonResponse
    {
        DB::beginTransaction();

        try {
            
            $user->update($request->all());
            Log::info('Usuário atualizado com ID:', ['user_id' => $user->id, 'request_data' => $request->all()]);

            DB::commit();
            Log::info('Usuário atualizado com sucesso!', ['user_id' => $user->id]);

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Usuário atualizado com sucesso!',
            ], 200);

        } catch (Exception $e) {
            
            DB::rollBack();
            Log::error('Falha ao atualizar o usuário:', ['user' => $user, 'error' => $e->getMessage()]);
            
            return response()->json([
                'status' => false,
                'message' => 'Falha ao atualizar o usuário!'
            ], 500);

        }
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();

        try {
            Log::info('Excluíndo usuário com ID:', ['user_id' => $user->id]);
            $user->delete();
            
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Usuário excluído com sucesso!'
            ], 204);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Falha ao excluir o usuário com ID:', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            
            return response()->json([
                'status' => false,
                'message' => 'Falha ao excluir o usuário!'
            ], 500);
        }
    }
}
