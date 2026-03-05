<?php

namespace App\Sidebar\Widgets;

use App\Models\Post;
use App\Models\Sidebar;

class PopularPostsWidget extends BaseWidget
{
    public static function type(): string  { return 'popular_posts'; }
    public static function label(): string { return 'Posts Populares'; }
    public static function icon(): string  { return 'fa-fire'; }
    public static function color(): string { return '#ef4444'; }

    public static function validationRules(array $context = []): array
    {
        return [
            'limit' => 'required|integer|min:1|max:10',
        ];
    }

    public function resolve(Sidebar $widget): array
    {
        return Post::where('status', 'published')
            ->withCount('comments')
            ->orderByDesc('created_at')
            ->limit($widget->limit)
            ->get(['id', 'title', 'slug', 'thumbnail', 'created_at'])
            ->toArray();
    }
}