<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Comment;
use App\Models\PostLike;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            // ─── Estatísticas de Posts ────────────────────────────────────────
            'totalPosts'     => Post::count(),
            'publishedPosts' => Post::where('status', 'published')->count(),
            'draftPosts'     => Post::where('status', 'draft')->count(),
            'privatePosts'   => Post::where('status', 'private')->count(),

            // ─── Tags e Categorias ────────────────────────────────────────────
            'totalTags'       => Tag::count(),
            'totalCategories' => Category::count(),

            // ─── Comentários e Likes ──────────────────────────────────────────
            'pendingComments' => Comment::where('status', 'pending')->count(),
            'totalLikes'      => PostLike::count(),

            // ─── Últimos 8 posts criados ──────────────────────────────────────
            'latestPosts' => Post::with(['author', 'category'])
                ->withCount(['likes', 'comments'])
                ->latest()
                ->take(8)
                ->get(),
        ]);
    }
}