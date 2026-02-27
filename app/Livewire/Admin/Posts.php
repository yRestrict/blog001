<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
// use App\Models\Tag;
// use App\Models\Category;
// use App\Models\ParentCategory;

class Posts extends Component
{
    use WithPagination;

    // ─── Filtros de listagem ──────────────────────────────────────────────────
    public string $search   = '';
    public string $filterStatus = '';

    // ─── Estado do modal de exclusão ─────────────────────────────────────────
    public ?int $confirmingDelete = null;

    // ─── Computed: reseta paginação ao pesquisar ──────────────────────────────
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ─── Confirmar exclusão ───────────────────────────────────────────────────
    public function confirmDelete(int $id): void
    {
        $this->confirmingDelete = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = null;
    }

    public function deletePost(int $id): void
    {
        $post = Post::findOrFail($id);
        $post->delete();

        $this->confirmingDelete = null;
        $this->dispatch('notify', type: 'success', message: 'Post removido com sucesso!');
    }

    // ─── Alternar status ──────────────────────────────────────────────────────
    public function toggleStatus(int $id): void
    {
        $post = Post::findOrFail($id);
        $newStatus = $post->status === 'published' ? 'draft' : 'published';
        $post->update(['status' => $newStatus]);

        $label = $newStatus === 'published' ? 'publicado' : 'salvo como rascunho';
        $this->dispatch('notify', type: 'info', message: "Post {$label}.");
    }

    // ─── Render ───────────────────────────────────────────────────────────────
    public function render()
    {
        $posts = Post::with(['author', 'category', 'tags'])
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterStatus, fn ($q) =>
                $q->where('status', $this->filterStatus)
            )
            ->latest()
            ->paginate(10);

        return view('livewire.admin.posts', [
            'posts' => $posts,
        ]);
    }
}