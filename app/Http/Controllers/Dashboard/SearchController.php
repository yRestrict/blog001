<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Media;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return view('dashboard.search.index', [
                'pageTitle' => 'Busca',
                'q'         => $q,
                'posts'     => collect(),
                'media'     => collect(),
                'categories'=> collect(),
                'tags'      => collect(),
                'users'     => collect(),
                'total'     => 0,
            ]);
        }

        $like = '%' . $q . '%';
        $user = Auth::user();

        // ── Posts ─────────────────────────────────────────────────────────────
        $postsQuery = Post::with(['author', 'category'])
            ->where(fn($query) =>
                $query->where('title', 'like', $like)
                      ->orWhere('content', 'like', $like)
                      ->orWhere('meta_keywords', 'like', $like)
            );

        // Authors só veem os próprios posts
        if ($user->isAuthor()) {
            $postsQuery->where('author_id', $user->id);
        }

        $posts = $postsQuery->latest()->limit(8)->get();

        // ── Mídia ─────────────────────────────────────────────────────────────
        $media = Media::with('uploader')
            ->where('original_name', 'like', $like)
            ->latest()->limit(6)->get();

        // ── Categorias ────────────────────────────────────────────────────────
        $categories = Category::withCount('posts')
            ->where('name', 'like', $like)
            ->limit(6)->get();

        // ── Tags ──────────────────────────────────────────────────────────────
        $tags = Tag::withCount('posts')
            ->where('name', 'like', $like)
            ->limit(6)->get();

        // ── Usuários (somente owner) ───────────────────────────────────────────
        $users = collect();
        if ($user->isOwner()) {
            $users = User::where('name', 'like', $like)
                ->orWhere('username', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->limit(6)->get();
        }

        $total = $posts->count() + $media->count() + $categories->count()
               + $tags->count() + $users->count();

        return view('dashboard.search.index', [
            'pageTitle'  => "Busca: {$q}",
            'q'          => $q,
            'posts'      => $posts,
            'media'      => $media,
            'categories' => $categories,
            'tags'       => $tags,
            'users'      => $users,
            'total'      => $total,
        ]);
    }
}