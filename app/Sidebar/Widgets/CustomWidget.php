<?php

namespace App\Sidebar\Widgets;

use App\Models\Sidebar;

class CustomWidget extends BaseWidget
{
    public static function type(): string  { return 'custom'; }
    public static function label(): string { return 'Customizado'; }
    public static function icon(): string  { return 'fa-code'; }
    public static function color(): string { return '#64748b'; }

    public static function validationRules(array $context = []): array
    {
        return [
            'content' => 'required|string',
            'link'    => 'nullable|url',
        ];
    }

    public static function validationMessages(): array
    {
        return [
            'content.required' => 'O conteúdo é obrigatório.',
            'link.url'         => 'O link deve ser uma URL válida.',
        ];
    }

    public function resolve(Sidebar $widget): array
    {
        return [
            'content' => $widget->content,
            'link'    => $widget->link,
        ];
    }
}