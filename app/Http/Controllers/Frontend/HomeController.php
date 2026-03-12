<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;


class HomeController extends Controller
{
    public function index()
    {
        $settings = Setting::first();

        $title       = $settings->site_title ?? config('app.name');
        $description = $settings->site_meta_description ?? $settings->site_description ?? '';
        $keywords    = $settings->site_meta_keywords ?? '';
        $currentUrl  = url()->current();
        $imageURL    = $settings->site_logo_light
                        ? asset('uploads/logo/' . $settings->site_logo_light)
                        : '';

        // ── SEO ───────────────────────────────────────────────────────────────
        SEOTools::setTitle($title, false);
        SEOTools::setDescription($description);
        SEOMeta::setKeywords($keywords);

        SEOTools::opengraph()->setUrl($currentUrl);
        SEOTools::opengraph()->addProperty('type', 'website');
        if ($imageURL) SEOTools::opengraph()->addImage($imageURL);

        SEOTools::twitter()->setUrl($currentUrl);
        if ($imageURL) SEOTools::twitter()->addImage($imageURL);

        return view('frontend.home.index', [
            'pageTitle'     => $title,
            'settings'      => $settings,

            'featuredPosts' => Post::with(['author', 'category'])
                                ->where('status', 'published')
                                ->where('featured', true)
                                ->latest()
                                ->take(3)
                                ->get(),

            'posts'         => Post::where('status', 'published')
                                ->with(['category', 'author', 'tags'])
                                ->latest()
                                ->paginate(6),

            'categories'    => Category::where('status', true)
                                ->withCount('posts')
                                ->orderBy('ordering')
                                ->get(),
        ]);
    }
}