<?php

namespace App\Sidebar\Widgets;

use App\Models\Post;
use App\Models\Sidebar;
use Illuminate\Support\Carbon;

class PopularDownloadsWidget extends BaseWidget
{
    public static function type(): string  { return 'popular_downloads'; }
    public static function label(): string { return 'Downloads Populares'; }
    public static function icon(): string  { return 'fa-download'; }
    public static function color(): string { return '#10b981'; }

    public static function validationRules(array $context = []): array
    {
        return [
            'limit'       => 'required|integer|min:1|max:20',
            'period_type' => 'required|in:week,month,total',
        ];
    }

    public function resolve(Sidebar $widget): array
    {
        $query = Post::where('status', true)
            ->where('type', 'download') // ajuste para o seu campo de tipo
            ->orderByDesc('downloads');

        if ($widget->period_type === 'week') {
            $query->where('created_at', '>=', Carbon::now()->subWeek());
        } elseif ($widget->period_type === 'month') {
            $query->where('created_at', '>=', Carbon::now()->subMonth());
        }

        return $query->limit($widget->limit)
            ->get(['id', 'title', 'slug', 'thumbnail', 'downloads', 'created_at'])
            ->toArray();
    }
}