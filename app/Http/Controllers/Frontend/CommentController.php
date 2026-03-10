<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;



class CommentController extends Controller
{
    private function getRelatedPosts(Post $post, int $limit = 3): Collection
    {
        $exclude = $post->id;
        $tagIds  = $post->tags->pluck('id');

        // 1️⃣ Mesma categoria
        $related = Post::with(['author', 'category'])
            ->where('status', 'published')
            ->where('id', '!=', $exclude)
            ->where('category_id', $post->category_id)
            ->latest()
            ->take($limit)
            ->get();

        if ($related->count() >= $limit) return $related;

        // 2️⃣ Mesma categoria pai (parent_category)
        if ($related->count() < $limit && $post->category?->parent_category_id) {
            $alreadyIds = $related->pluck('id')->push($exclude);

            $fromParent = Post::with(['author', 'category'])
                ->where('status', 'published')
                ->whereNotIn('id', $alreadyIds)
                ->whereHas('category', fn($q) =>
                    $q->where('parent_category_id', $post->category->parent_category_id)
                )
                ->latest()
                ->take($limit - $related->count())
                ->get();

            $related = $related->merge($fromParent);
        }

        if ($related->count() >= $limit) return $related;

        // 3️⃣ Tags em comum
        if ($tagIds->isNotEmpty()) {
            $alreadyIds = $related->pluck('id')->push($exclude);

            $fromTags = Post::with(['author', 'category'])
                ->where('status', 'published')
                ->whereNotIn('id', $alreadyIds)
                ->whereHas('tags', fn($q) => $q->whereIn('tags.id', $tagIds))
                ->latest()
                ->take($limit - $related->count())
                ->get();

            $related = $related->merge($fromTags);
        }

        if ($related->count() >= $limit) return $related;

        // 4️⃣ Posts mais recentes (qualquer categoria)
        $alreadyIds = $related->pluck('id')->push($exclude);

        $recent = Post::with(['author', 'category'])
            ->where('status', 'published')
            ->whereNotIn('id', $alreadyIds)
            ->latest()
            ->take($limit - $related->count())
            ->get();

        return $related->merge($recent);
    }   
}
