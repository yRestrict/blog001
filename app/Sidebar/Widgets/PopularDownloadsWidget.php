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
        // Busca posts publicados que tenham pelo menos 1 download
        $query = Post::where('status', 'published')
            ->where('downloads', '>', 0)
            ->orderByDesc('downloads');

        // Filtra por período se não for 'total'
        if ($widget->period_type === 'week') {
            $query->where('updated_at', '>=', Carbon::now()->subWeek());
        } elseif ($widget->period_type === 'month') {
            $query->where('updated_at', '>=', Carbon::now()->subMonth());
        }

        return $query->limit($widget->limit ?? 5)
            ->get(['id', 'title', 'slug', 'thumbnail', 'downloads', 'created_at'])
            ->toArray();
    }
}