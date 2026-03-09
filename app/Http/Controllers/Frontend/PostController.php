<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Setting;

class PostController extends Controller
{
    public function index($slug)
    {
        // Busca o post com todas as relações necessárias
        $post = Post::with([
                        'category',
                        'author',
                        'tags',
                        'comments.user',
                        'comments.replies.user',
                    ])
                    ->withCount([
                        'tags',
                        'likes',
                        'comments', // já filtrado pela relação (approved + sem parent)
                    ])
                    ->where('status', 'published') // ← corrigido de true para 'published'
                    ->where('slug', $slug)
                    ->firstOrFail();

        // Incrementa views sempre que o post é aberto
        $post->increment('views');

        // Posts relacionados — mesma categoria, excluindo o atual
        $relatedPosts = Post::with(['author', 'category'])
            ->where('status', 'published')
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id) // ← exclui o post atual
            ->latest()
            ->take(3)
            ->get();

        return view('frontend.post.index', [
            'pageTitle'    => $post->title,
            'settings'     => Setting::first(),
            'post'         => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}