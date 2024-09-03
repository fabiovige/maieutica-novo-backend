<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function getUsers(array $filters, int $perPage)
    {
        // Adicione sua lógica de filtragem aqui
        return User::where($filters)->paginate($perPage);
    }

    public function createUser(array $data): User
    {
        DB::beginTransaction();

        try {
            $user = User::create($data);
            Log::info('Usuário cadastrado com sucesso!', ['user' => $user->id]);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Falha ao cadastrar usuário:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateUser(User $user, array $data): User
    {
        DB::beginTransaction();

        try {
            $user->update($data);
            Log::info('Usuário atualizado com sucesso!', ['user_id' => $user->id]);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Falha ao atualizar o usuário:', ['user' => $user->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteUser(User $user)
    {
        DB::beginTransaction();

        try {
            $user->delete();
            Log::info('Usuário excluído com sucesso!', ['user_id' => $user->id]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Falha ao excluir o usuário:', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function findUserById(int $id): User
    {
        return User::findOrFail($id);
    }
}
