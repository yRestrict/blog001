<?php

namespace App\View\Components;

use App\Models\Setting;
use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{
    public function render(): View|Closure|string
    {
        $settings = Setting::first();
        $order    = $settings->footer_category_order ?? 'posts';

        $categories = Category::where('status', true)
            ->when($order === 'posts', function ($q) {
                $q->withCount('posts')->orderByDesc('posts_count');
            })
            ->when($order === 'views', function ($q) {
                $q->orderByDesc('views');
            })
            ->take(8)
            ->get();

        return view('components.footer', [
            'settings'   => $settings,
            'categories' => $categories,
        ]);
    }
}