<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\Category;

class Posts extends Component
{
    use WithPagination;

    // ─── Filtros de listagem ──────────────────────────────────────────────────
    public string $search         = '';
    public string $filterStatus   = '';
    public ?int   $filterCategory = null;
    public ?int   $filterAuthor   = null;
    public string $filterPeriod   = '';
    public bool   $filterFeatured = false;
    public int    $perPage        = 10;

    // ─── Estado do modal de exclusão ─────────────────────────────────────────
    public ?int   $deletingPostId    = null;
    public string $deletingPostTitle = '';

    // ─── Dropdown de filtros ─────────────────────────────────────────────────
    public bool $showFilters = false;

    // ─── Reseta paginação ao alterar filtros ─────────────────────────────────
    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingPerPage(): void      { $this->resetPage(); }

    public function toggleFilters(): void
    {
        $this->showFilters = !$this->showFilters;
    }

    public function setFilterCategory(?int $id): void
    {
        $this->filterCategory = $this->filterCategory === $id ? null : $id;
        $this->resetPage();
    }

    public function setFilterAuthor(?int $id): void
    {
        $this->filterAuthor = $this->filterAuthor === $id ? null : $id;
        $this->resetPage();
    }

    public function setFilterPeriod(string $period): void
    {
        $this->filterPeriod = $this->filterPeriod === $period ? '' : $period;
        $this->resetPage();
    }

    public function toggleFilterFeatured(): void
    {
        $this->filterFeatured = !$this->filterFeatured;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->filterCategory = null;
        $this->filterAuthor   = null;
        $this->filterPeriod   = '';
        $this->filterFeatured = false;
        $this->resetPage();
    }

    public function getActiveFilterCountProperty(): int
    {
        return ($this->filterCategory ? 1 : 0)
             + ($this->filterAuthor ? 1 : 0)
             + ($this->filterPeriod ? 1 : 0)
             + ($this->filterFeatured ? 1 : 0);
    }

    // ─── Modal de exclusão ──────────────────────────────────────────────────
    public function prepareDelete(int $id): void
    {
        $post = Post::findOrFail($id);
        $this->deletingPostId    = $post->id;
        $this->deletingPostTitle = $post->title;
    }

    public function cancelDelete(): void
    {
        $this->deletingPostId    = null;
        $this->deletingPostTitle = '';
    }

    public function deletePost(): void
    {
        $post = Post::findOrFail($this->deletingPostId);
        $post->delete();

        $this->deletingPostId    = null;
        $this->deletingPostTitle = '';
        $this->dispatch('notify', type: 'success', message: 'Post movido para a lixeira!');
    }

    // ─── Alternar status (published → draft → private → published) ──────────
    public function toggleStatus(int $id): void
    {
        $post = Post::findOrFail($id);

        $newStatus = match ($post->status) {
            'published' => 'draft',
            'draft'     => 'private',
            'private'   => 'published',
            default     => 'published',
        };

        $post->update(['status' => $newStatus]);

        $label = match ($newStatus) {
            'published' => 'publicado',
            'draft'     => 'salvo como rascunho',
            'private'   => 'definido como privado',
        };

        $this->dispatch('notify', type: 'info', message: "Post {$label}.");
    }

    // ─── Render ───────────────────────────────────────────────────────────────
    public function render()
    {
        // Base query com todos os filtros (exceto status — contamos por status)
        $baseQuery = Post::query()
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterCategory, fn ($q) =>
                $q->where('category_id', $this->filterCategory)
            )
            ->when($this->filterAuthor, fn ($q) =>
                $q->where('author_id', $this->filterAuthor)
            )
            ->when($this->filterPeriod, fn ($q) => match ($this->filterPeriod) {
                'today'      => $q->whereDate('created_at', today()),
                'this_week'  => $q->where('created_at', '>=', now()->startOfWeek()),
                'this_month' => $q->where('created_at', '>=', now()->startOfMonth()),
                'this_year'  => $q->where('created_at', '>=', now()->startOfYear()),
                default      => $q,
            })
            ->when($this->filterFeatured, fn ($q) =>
                $q->where('featured', true)
            );

        // Widgets refletem os filtros (exceto filterStatus)
        $totalPosts     = (clone $baseQuery)->count();
        $publishedPosts = (clone $baseQuery)->where('status', 'published')->count();
        $draftPosts     = (clone $baseQuery)->where('status', 'draft')->count();
        $privatePosts   = (clone $baseQuery)->where('status', 'private')->count();

        // Posts paginados (com filtro de status)
        $posts = (clone $baseQuery)
            ->with(['author', 'category', 'tags'])
            ->when($this->filterStatus, fn ($q) =>
                $q->where('status', $this->filterStatus)
            )
            ->latest()
            ->paginate($this->perPage);

        // Dados para dropdown de filtros
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $authors    = \App\Models\User::whereHas('posts')
                        ->orderBy('name')
                        ->get(['id', 'name']);

        return view('livewire.admin.posts', [
            'posts'          => $posts,
            'totalPosts'     => $totalPosts,
            'publishedPosts' => $publishedPosts,
            'draftPosts'     => $draftPosts,
            'privatePosts'   => $privatePosts,
            'trashedPosts'   => Post::onlyTrashed()->count(),
            'categories'     => $categories,
            'authors'        => $authors,
        ]);
    }
}
