<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;

class PostsTrash extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $confirmingForceDelete = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ─── Restaurar post ───────────────────────────────────────────────────────
    public function restore(int $id): void
    {
        Post::onlyTrashed()->findOrFail($id)->restore();
        $this->dispatch('notify', type: 'success', message: 'Post restaurado com sucesso!');
    }

    // ─── Restaurar todos ──────────────────────────────────────────────────────
    public function restoreAll(): void
    {
        Post::onlyTrashed()->restore();
        $this->dispatch('notify', type: 'success', message: 'Todos os posts foram restaurados!');
    }

    // ─── Confirmação de exclusão permanente ───────────────────────────────────
    public function confirmForceDelete(int $id): void
    {
        $this->confirmingForceDelete = $id;
    }

    public function cancelForceDelete(): void
    {
        $this->confirmingForceDelete = null;
    }

    // ─── Excluir permanentemente ──────────────────────────────────────────────
    public function forceDelete(int $id): void
    {
        $post = Post::onlyTrashed()->findOrFail($id);

        // Remove imagem do disco se existir
        if ($post->featured_image && file_exists(public_path('uploads/posts/' . $post->featured_image))) {
            unlink(public_path('uploads/posts/' . $post->featured_image));
        }

        // Desvincula tags da pivot antes de excluir
        $post->tags()->detach();

        $post->forceDelete();

        $this->confirmingForceDelete = null;
        $this->dispatch('notify', type: 'success', message: 'Post excluído permanentemente!');
    }

    // ─── Esvaziar lixeira ────────────────────────────────────────────────────
    public function emptyTrash(): void
    {
        $posts = Post::onlyTrashed()->get();

        foreach ($posts as $post) {
            if ($post->featured_image && file_exists(public_path('uploads/posts/' . $post->featured_image))) {
                unlink(public_path('uploads/posts/' . $post->featured_image));
            }
            $post->tags()->detach();
            $post->forceDelete();
        }

        $this->dispatch('notify', type: 'success', message: 'Lixeira esvaziada com sucesso!');
    }

    // ─── Render ───────────────────────────────────────────────────────────────
    public function render()
    {
        $posts = Post::onlyTrashed()
            ->with(['author', 'category'])
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->latest('deleted_at')
            ->paginate(10);

        return view('livewire.admin.posts-trash', [
            'posts'      => $posts,
            'trashCount' => Post::onlyTrashed()->count(),
        ]);
    }
}