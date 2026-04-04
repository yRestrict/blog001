<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;

class PostPolicy
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
     * Author pode ver a lista, mas só os próprios posts
     * (o filtro é feito na query do controller/Livewire).
     */
    public function viewAny(User $user): bool
    {
        return $user->isAuthor();
    }

    /**
     * Author pode ver um post específico apenas se for o dono.
     */
    public function view(User $user, Post $post): bool
    {
        return $user->isAuthor() && $user->id === $post->author_id;
    }

    /**
     * Author pode criar posts.
     */
    public function create(User $user): bool
    {
        return $user->isAuthor();
    }

    /**
     * Author pode editar apenas os próprios posts.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->isAuthor() && $user->id === $post->author_id;
    }

    /**
     * Author pode mandar para lixeira apenas os próprios posts.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->isAuthor() && $user->id === $post->author_id;
    }

    /**
     * Author não acessa a lixeira. Somente owner (before).
     */
    public function viewTrashed(User $user): bool
    {
        return false;
    }

    /**
     * Somente owner pode restaurar. (before)
     */
    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Somente owner pode excluir permanentemente. (before)
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Somente owner pode aprovar posts. (before)
     */
    public function approve(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Somente owner pode rejeitar posts. (before)
     */
    public function reject(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Author pode marcar como destaque apenas se tiver auto_approve_posts ativo.
     */
    public function feature(User $user, Post $post): bool
    {
        // Post novo (ainda não tem author_id) — libera se tiver auto_approve
        if (! $post->exists) {
            return $user->isAuthor() && $user->autoApprovePosts();
        }

        return $user->isAuthor()
            && $user->id === $post->author_id
            && $user->autoApprovePosts();
    }
}