<?php

namespace App\Sidebar\Widgets;

use App\Models\Sidebar;

class SearchWidget extends BaseWidget
{
    public static function type(): string  { return 'search'; }
    public static function label(): string { return 'Busca'; }
    public static function icon(): string  { return 'fa-search'; }
    public static function color(): string { return '#6366f1'; }

    public function resolve(Sidebar $widget): array
    {
        return []; // Widget puramente visual, sem dados dinâmicos
    }
}