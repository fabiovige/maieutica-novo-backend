<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the authenticated user can view another user.
     */
    public function view(User $authenticatedUser, User $targetUser)
    {
        // Admins podem visualizar qualquer usuário
        // Usuários podem visualizar seus próprios dados
        return $authenticatedUser->role === 'admin' || $authenticatedUser->id === $targetUser->id;
    }

    /**
     * Determine if the authenticated user can create a new user.
     */
    public function create(User $authenticatedUser)
    {
        // Apenas Admins podem criar novos usuários
        return $authenticatedUser->role === 'admin';
    }

    /**
     * Determine if the authenticated user can update another user.
     */
    public function update(User $authenticatedUser, User $targetUser)
    {
        // Admins podem atualizar qualquer usuário
        // Usuários podem atualizar seus próprios dados
        return $authenticatedUser->role === 'admin' || $authenticatedUser->id === $targetUser->id;
    }

    /**
     * Determine if the authenticated user can delete another user.
     */
    public function delete(User $authenticatedUser, User $targetUser)
    {
        // Apenas Admins podem deletar usuários
        return $authenticatedUser->role === 'admin';
    }
}
