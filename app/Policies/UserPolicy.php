<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // Ver lista de usuários
    public function viewAny(User $user): bool
    {
        return $user->isOwner();
    }

    // Ver perfil de um usuário
    public function view(User $user, User $target): bool
    {
        return $user->isOwner() || $user->id === $target->id;
    }

    // Criar usuário manualmente
    public function create(User $user): bool
    {
        return $user->isOwner();
    }

    // Editar usuário
    public function update(User $user, User $target): bool
    {
        // Owner edita qualquer um, autor/visitor só o próprio perfil
        return $user->isOwner() || $user->id === $target->id;
    }

    // Banir usuário
    public function ban(User $user, User $target): bool
    {
        // Só owner bane, e não pode se banir
        return $user->isOwner() && $user->id !== $target->id;
    }

    // Deletar usuário
    public function delete(User $user, User $target): bool
    {
        // Só owner deleta, e não pode se deletar
        return $user->isOwner() && $user->id !== $target->id;
    }

    // Promover para autor
    public function promote(User $user): bool
    {
        return $user->isOwner();
    }
}