<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isOwner()) {
            return true;
        }

        return null;
    }

    // Ver lista — todos autenticados podem ver
    public function viewAny(User $user): bool
    {
        return false; // owner já passa no before; não-owner não acessa lixeira
    }

    // Criar — author com auto_approve pode
    public function create(User $user): bool
    {
        return $user->isAuthor() && $user->autoApprovePosts();
    }

    // Editar — author com auto_approve pode
    public function update(User $user, Category $category): bool
    {
        return $user->isAuthor() && $user->autoApprovePosts();
    }

    // Excluir — somente owner (before)
    public function delete(User $user, Category $category): bool
    {
        return false;
    }

    // Restaurar — somente owner (before)
    public function restore(User $user, Category $category): bool
    {
        return false;
    }

    // Excluir permanentemente — somente owner (before)
    public function forceDelete(User $user, Category $category): bool
    {
        return false;
    }
}