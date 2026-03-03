<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'settings'      => Setting::first(),
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
        ];

        return view('frontend.home.index', $data);
    }
}