<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Setting;

class TagController extends Controller
{
    public function index($slug)
    {
        // 1. Busca a tag pelo slug
        $tag = Tag::where('slug', $slug)->first();

        // 2. Validação simples
        if (!$tag) {
            return redirect()->route('frontend.home')
                ->with('error', 'Tag não encontrada.');
        }

        // 3. Incrementa visualizações (campo 'views' definido no seu Model Tag)
        $tag->increment('views');

        // 4. Monta o array $data com tudo o que a view precisa
        $data = [
            'pageTitle' => 'Tag: ' . $tag->name,
            'settings'  => Setting::first(),
            'tag'       => $tag,
            // Paginação dos posts relacionados através da relação belongsToMany
            'posts'     => $tag->posts()
                                ->where('status', "published") // Garante que o post está ativo
                                ->with(['category', 'author', 'tags']) // 'author' é o nome no seu Model Post
                                ->latest() // Ordena por created_at desc
                                ->paginate(10)
                                ->withQueryString() // Mantém filtros na URL ao trocar de página
        ];

        return view('frontend.tag.index', $data);
    }
}