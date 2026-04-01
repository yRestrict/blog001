<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;

class PostsTrash extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    // ─── Modal de exclusão permanente ────────────────────────────────────────
    public ?int $deletingPostId = null;
    public string $deletingPostTitle = '';

    // ─── Modal de esvaziar lixeira ───────────────────────────────────────────
    public bool $confirmingEmptyTrash = false;

    // ─── Busca ───────────────────────────────────────────────────────────────
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    // ─── Computed: contagem de itens na lixeira ─────────────────────────────
    public function getTrashedCountProperty(): int
    {
        return Post::onlyTrashed()->count();
    }

    // ─── Restaurar post ─────────────────────────────────────────────────────
    public function restorePost(int $id): void
    {
        Post::onlyTrashed()->findOrFail($id)->restore();
        $this->dispatch('notify', type: 'success', message: 'Post restaurado com sucesso!');
    }

    // ─── Restaurar todos ────────────────────────────────────────────────────
    public function restoreAll(): void
    {
        Post::onlyTrashed()->restore();
        $this->dispatch('notify', type: 'success', message: 'Todos os posts foram restaurados!');
    }

    // ─── Preparar exclusão permanente (abre modal) ──────────────────────────
    public function prepareForceDelete(int $id): void
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $this->deletingPostId = $post->id;
        $this->deletingPostTitle = $post->title;
    }

    public function cancelForceDelete(): void
    {
        $this->deletingPostId = null;
        $this->deletingPostTitle = '';
    }

    // ─── Excluir permanentemente ────────────────────────────────────────────
    public function forceDeletePost(): void
    {
        if (!$this->deletingPostId) {
            return;
        }

        $post = Post::onlyTrashed()->findOrFail($this->deletingPostId);

        // Remove imagem do disco se existir
        if ($post->thumbnail && file_exists(public_path('uploads/posts/' . $post->thumbnail))) {
            unlink(public_path('uploads/posts/' . $post->thumbnail));
        }

        // Desvincula tags da pivot antes de excluir
        $post->tags()->detach();

        $post->forceDelete();

        $this->deletingPostId = null;
        $this->deletingPostTitle = '';
        $this->dispatch('notify', type: 'success', message: 'Post excluído permanentemente!');
    }

    // ─── Esvaziar lixeira (modal) ───────────────────────────────────────────
    public function confirmEmptyTrash(): void
    {
        $this->confirmingEmptyTrash = true;
    }

    public function cancelEmptyTrash(): void
    {
        $this->confirmingEmptyTrash = false;
    }

    public function emptyTrash(): void
    {
        $posts = Post::onlyTrashed()->get();

        foreach ($posts as $post) {
            if ($post->thumbnail && file_exists(public_path('uploads/posts/' . $post->thumbnail))) {
                unlink(public_path('uploads/posts/' . $post->thumbnail));
            }
            $post->tags()->detach();
            $post->forceDelete();
        }

        $this->confirmingEmptyTrash = false;
        $this->dispatch('notify', type: 'success', message: 'Lixeira esvaziada com sucesso!');
    }

    // ─── Render ─────────────────────────────────────────────────────────────
    public function render()
    {
        $posts = Post::onlyTrashed()
            ->with(['author', 'category'])
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->latest('deleted_at')
            ->paginate($this->perPage);

        return view('livewire.admin.posts-trash', [
            'posts' => $posts,
        ]);
    }
}
