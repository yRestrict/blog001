<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Tag;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;

class TagController extends Controller
{
    public function index($slug)
    {
        $tag = Tag::where('slug', $slug)->first();

        if (! $tag) {
            return redirect()->route('frontend.home')
                ->with('error', 'Tag não encontrada.');
        }

        $tag->increment('views');

        $posts = $tag->posts()
                    ->where('status', 'published')
                    ->with(['category', 'author', 'tags'])
                    ->latest()
                    ->paginate(6)
                    ->withQueryString();

        // ── SEO ───────────────────────────────────────────────────────────────
        $title       = "#{$tag->name} — " . (Setting::first()->site_title ?? config('app.name'));
        $description = "Explore todos os posts com a tag {$tag->name}. {$posts->total()} publicações encontradas.";

        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        SEOMeta::setKeywords($tag->name);

        SEOTools::opengraph()->setUrl(request()->url());
        SEOTools::opengraph()->addProperty('type', 'website');

        SEOTools::twitter()->setUrl(request()->url());

        return view('frontend.tag.index', [
            'pageTitle' => 'Tag: ' . $tag->name,
            'settings'  => Setting::first(),
            'tag'       => $tag,
            'posts'     => $posts,
        ]);
    }
}