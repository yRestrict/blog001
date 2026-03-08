<?php

namespace App\View\Composers;

use App\Models\Tag;
use Illuminate\View\View;

class HeaderViewComposer
{
    public function compose(View $view): void
    {
        $view->with('popularTags', Tag::withCount(['posts' => fn($q) => $q->where('status', 'published')])
            ->orderByDesc('posts_count')
            ->limit(8)
            ->get()
            ->filter(fn($tag) => $tag->posts_count > 0));
    }
}