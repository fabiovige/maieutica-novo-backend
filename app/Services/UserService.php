<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * Aplica os filtros e retorna os usuários paginados.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUsers(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = User::query();

        // Aplicando filtros
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                $query->where($key, 'like', '%' . $value . '%');
            }
        }

        return $query->paginate($perPage)->appends($filters);
    }
}
