<?php

namespace App\Sidebar\Widgets;

use App\Models\Category;
use App\Models\Sidebar;

class CategoriesWidget extends BaseWidget
{
    public static function type(): string  { return 'categories'; }
    public static function label(): string { return 'Categorias'; }
    public static function icon(): string  { return 'fa-folder'; }
    public static function color(): string { return '#f59e0b'; }

    public static function validationRules(array $context = []): array
    {
        $isManual = ($context['category_display_type'] ?? '') === 'manual';

        return [
            'category_display_type' => 'required|in:most_posts,most_visited,manual',
            'category_limit'        => 'required|integer|min:1|max:8',
            'selected_categories'   => $isManual
                                       ? 'required|array|min:1|max:8'
                                       : 'nullable|array',
            'selected_categories.*' => 'integer|exists:categories,id',
        ];
    }

    public static function validationMessages(): array
    {
        return [
            'selected_categories.required' => 'Selecione ao menos uma categoria.',
            'selected_categories.min'      => 'Selecione ao menos :min categoria.',
        ];
    }

    public function resolve(Sidebar $widget): array
    {
        $query = Category::where('status', true)
            ->withCount(['posts' => fn($q) => $q->where('status', 'published')]);

        return match ($widget->category_display_type) {
            'most_visited' => $query->orderByDesc('views')
                                    ->limit($widget->category_limit)
                                    ->get(['id', 'name', 'slug', 'views', 'posts_count'])
                                    ->filter(fn($cat) => $cat->posts_count > 0)
                                    ->values()
                                    ->toArray(),
            'manual'       => !empty($widget->selected_categories)
                            ? $query->whereIn('id', $widget->selected_categories)
                                    ->get(['id', 'name', 'slug', 'views', 'posts_count'])
                                    ->sortBy(fn($cat) => array_search($cat->id, $widget->selected_categories))
                                    ->values()
                                    ->toArray()
                            : [],
            default        => $query->orderByDesc('posts_count')
                                    ->limit($widget->category_limit)
                                    ->get(['id', 'name', 'slug', 'views', 'posts_count'])
                                    ->filter(fn($cat) => $cat->posts_count > 0)
                                    ->values()
                                    ->toArray(),
        };
    }
}