<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tag;

class TagPolicy
{
    /**
     * Owner tem acesso total.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isOwner()) {
            return true;
        }

        return null;
    }

    /**
     * Qualquer autor autenticado pode ver a lista de tags.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAuthor();
    }

    /**
     * Autores podem criar tags.
     */
    public function create(User $user): bool
    {
        return $user->isAuthor();
    }

    /**
     * Autores podem editar qualquer tag (owner já liberado no before).
     */
    public function update(User $user, Tag $tag): bool
    {
        return $user->isAuthor();
    }

    /**
     * Somente owner pode deletar (before já cuida disso).
     */
    public function delete(User $user, Tag $tag): bool
    {
        return false;
    }
}