<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Setting;

class CategoryController extends Controller
{
    public function index($slug)
    {
        $data = [
            'category' => Category::where('slug', $slug)->firstOrFail(),
            'settings' => Setting::first(),
            'posts'    => Post::where('status', 'published')
                                ->whereHas('category', function ($query) use ($slug) {
                                    $query->where('slug', $slug);
                                })
                                ->with(['author', 'category', 'tags'])
                                ->latest()
                                ->paginate(6),
        ];

        return view('frontend.category.index', $data);
    }
}
