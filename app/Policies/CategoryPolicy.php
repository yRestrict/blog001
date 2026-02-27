<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    /**
     * Antes de qualquer check, se for owner libera tudo.
     * Retornar null deixa cair nos metodos individuais (caso queira granularidade futura).
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isOwner()) {
            return true;
        }

        return null; // cai no metodo especifico
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Category $category): bool
    {
        return false;
    }

    public function delete(User $user, Category $category): bool
    {
        return false;
    }

    // Para restaurar da lixeira
    public function restore(User $user, Category $category): bool
    {
        return false;
    }

    // Para excluir permanentemente
    public function forceDelete(User $user, Category $category): bool
    {
        return false;
    }
}