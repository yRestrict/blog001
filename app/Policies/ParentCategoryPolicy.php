<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ParentCategory;

class ParentCategoryPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isOwner()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ParentCategory $parentCategory): bool
    {
        return false;
    }

    public function delete(User $user, ParentCategory $parentCategory): bool
    {
        return false;
    }

    public function restore(User $user, ParentCategory $parentCategory): bool
    {
        return false;
    }

    public function forceDelete(User $user, ParentCategory $parentCategory): bool
    {
        return false;
    }
}