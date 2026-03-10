<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostDownload;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

class PostController extends Controller
{
    public function index($slug)
    {
        $post = Post::with([
                        'category',
                        'author',
                        'tags',
                        'comments.user',
                        'comments.replies.user',
                    ])
                    ->withCount(['tags', 'likes', 'comments'])
                    ->where('status', 'published')
                    ->where('slug', $slug)
                    ->firstOrFail();

        $post->increment('views');

        return view('frontend.post.index', [
            'pageTitle'    => $post->title,
            'settings'     => Setting::first(),
            'post'         => $post,
            'relatedPosts' => $this->getRelatedPosts($post),
        ]);
    }

    public function download(PostDownload $download)
    {
        $download->post->increment('downloads');

        if ($download->file) {
            return response()->download(storage_path('app/public/' . $download->file));
        }

        if ($download->url) {
            return redirect()->away($download->url);
        }

        return redirect()->back()->with('error', 'Arquivo não encontrado.');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

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

        // 2️⃣ Mesma categoria pai
        if ($post->category?->parent_category_id) {
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

        // 4️⃣ Posts mais recentes
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