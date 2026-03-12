<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;

class CategoryController extends Controller
{
    public function index($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = Post::where('status', 'published')
                    ->whereHas('category', fn($q) => $q->where('slug', $slug))
                    ->with(['author', 'category', 'tags'])
                    ->latest()
                    ->paginate(6);

        // ── SEO ───────────────────────────────────────────────────────────────
        $title       = $category->name . ' — ' . (Setting::first()->site_title ?? config('app.name'));
        $description = $category->description
                        ?? "Confira todos os posts da categoria {$category->name}.";

        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        SEOMeta::setKeywords($category->name);

        SEOTools::opengraph()->setUrl(request()->url());
        SEOTools::opengraph()->addProperty('type', 'website');

        SEOTools::twitter()->setUrl(request()->url());

        return view('frontend.category.index', [
            'pageTitle' => $category->name,
            'settings'  => Setting::first(),
            'category'  => $category,
            'posts'     => $posts,
        ]);
    }
}