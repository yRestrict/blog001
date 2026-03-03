<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $data = [
            'category' => Category::where('slug', $slug)->firstOrFail(),
            'posts'    => Post::where('status', 'published')
                                ->whereHas('category', function ($query) use ($slug) {
                                    $query->where('slug', $slug);
                                })
                                ->with(['author', 'category', 'tags'])
                                ->latest()
                                ->paginate(6),
        ];
    }
}
