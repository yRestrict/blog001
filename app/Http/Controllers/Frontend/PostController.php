<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostDownload;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

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

        // ── SEO ───────────────────────────────────────────────────────────────
        $description = $post->meta_description
                        ?? Str::limit(strip_tags($post->content), 160);

        SEOTools::setTitle($post->title);
        SEOTools::setDescription($description);
        SEOMeta::setKeywords($post->meta_keywords ?? '');

        SEOTools::opengraph()->setUrl(request()->url());
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->addProperty('article:author', $post->author->name);
        SEOTools::opengraph()->addProperty('article:published_time', $post->created_at->toIso8601String());
        if ($post->thumbnail) {
            SEOTools::opengraph()->addImage(asset('uploads/posts/' . $post->thumbnail));
        }

        SEOTools::twitter()->setUrl(request()->url());
        if ($post->thumbnail) {
            SEOTools::twitter()->addImage(asset('uploads/posts/' . $post->thumbnail));
        }

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

    private function getRelatedPosts(Post $post, int $limit = 3): Collection
    {
        $exclude = $post->id;
        $tagIds  = $post->tags->pluck('id');

        $related = Post::with(['author', 'category'])
            ->where('status', 'published')
            ->where('id', '!=', $exclude)
            ->where('category_id', $post->category_id)
            ->latest()->take($limit)->get();

        if ($related->count() >= $limit) return $related;

        if ($post->category?->parent_category_id) {
            $alreadyIds = $related->pluck('id')->push($exclude);
            $fromParent = Post::with(['author', 'category'])
                ->where('status', 'published')
                ->whereNotIn('id', $alreadyIds)
                ->whereHas('category', fn($q) => $q->where('parent_category_id', $post->category->parent_category_id))
                ->latest()->take($limit - $related->count())->get();
            $related = $related->merge($fromParent);
        }

        if ($related->count() >= $limit) return $related;

        if ($tagIds->isNotEmpty()) {
            $alreadyIds = $related->pluck('id')->push($exclude);
            $fromTags = Post::with(['author', 'category'])
                ->where('status', 'published')
                ->whereNotIn('id', $alreadyIds)
                ->whereHas('tags', fn($q) => $q->whereIn('tags.id', $tagIds))
                ->latest()->take($limit - $related->count())->get();
            $related = $related->merge($fromTags);
        }

        if ($related->count() >= $limit) return $related;

        $alreadyIds = $related->pluck('id')->push($exclude);
        $recent = Post::with(['author', 'category'])
            ->where('status', 'published')
            ->whereNotIn('id', $alreadyIds)
            ->latest()->take($limit - $related->count())->get();

        return $related->merge($recent);
    }
}