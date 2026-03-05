<?php

namespace App\Sidebar\Widgets;

use App\Models\Tag;
use App\Models\Sidebar;

class TagsWidget extends BaseWidget
{
    public static function type(): string  { return 'tags'; }
    public static function label(): string { return 'Tags'; }
    public static function icon(): string  { return 'fa-tags'; }
    public static function color(): string { return '#3b82f6'; }

    public static function validationRules(array $context = []): array
    {
        $isManual = ($context['tag_display_type'] ?? '') === 'manual';

        return [
            'tag_display_type' => 'required|in:most_posts,most_visited,manual',
            'tag_limit'        => 'required|integer|min:1|max:12',
            'selected_tags'    => $isManual
                                   ? 'required|array|min:1|max:12'
                                   : 'nullable|array',
            'selected_tags.*'  => 'integer|exists:tags,id',
        ];
    }

    public static function validationMessages(): array
    {
        return [
            'selected_tags.required' => 'Selecione ao menos uma tag.',
        ];
    }

    public function resolve(Sidebar $widget): array
    {
        $query = Tag::has('posts')
            ->withCount(['posts' => fn($q) => $q->where('status', 'published')]);
            

        return match ($widget->tag_display_type) {
            'most_visited' => $query->orderByDesc('views')
                                    ->limit($widget->tag_limit)
                                    ->get(['id', 'name', 'slug', 'views', 'posts_count'])
                                    ->filter(fn($tag) => $tag->posts_count > 0)
                                    ->values()
                                    ->toArray(),
            'manual'       => !empty($widget->selected_tags)
                            ? $query->whereIn('id', $widget->selected_tags)
                                    ->get(['id', 'name', 'slug', 'views', 'posts_count'])
                                    ->sortBy(fn($tag) => array_search($tag->id, $widget->selected_tags))
                                    ->values()
                                    ->toArray()
                            : [],
            default        => $query->orderByDesc('posts_count')
                                    ->limit($widget->tag_limit)
                                    ->get(['id', 'name', 'slug', 'views', 'posts_count'])
                                    ->filter(fn($tag) => $tag->posts_count > 0)
                                    ->values()
                                    ->toArray(),
        };
    }
}