<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->get('q'));

        if (!$query) {
            return redirect()->route('frontend.home')
                ->with('error', 'Por favor, insira um termo para buscar.');
        }

        // ── SEO — página de busca não deve ser indexada ───────────────────────
        SEOTools::setTitle('Resultados para: ' . $query, false);
        SEOMeta::addMeta('robots', 'noindex, nofollow');

        $popularTags = Tag::withCount(['posts' => fn($q) => $q->where('status', 'published')])
            ->orderByDesc('posts_count')
            ->limit(8)
            ->get()
            ->filter(fn($tag) => $tag->posts_count > 0);

        $data = [
            'pageTitle'   => 'Resultados para: ' . $query,
            'settings'    => Setting::first(),
            'query'       => $query,
            'popularTags' => $popularTags,

            'posts' => Post::with(['category', 'tags'])
                ->where('status', 'published')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('content', 'LIKE', "%{$query}%");
                })
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ];

        return view('frontend.search.index', $data);
    }
}