<?php

namespace App\Sidebar\Widgets;

use App\Models\Sidebar;

class SocialLinksWidget extends BaseWidget
{
    public static function type(): string  { return 'social_links'; }
    public static function label(): string { return 'Redes Sociais'; }
    public static function icon(): string  { return 'fa-share-alt'; }
    public static function color(): string { return '#8b5cf6'; }

    const COLOR_REGEX = '/^(#([0-9a-f]{3}|[0-9a-f]{6})|rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*(,\s*[01]?(\.\d+)?)?\s*\))$/i';

    public static function validationRules(array $context = []): array
    {
        return [
            'social_data'          => 'required|array|min:1',
            'social_data.*.name'   => 'required|string|max:100',
            'social_data.*.icon'   => 'required|string|max:100',
            'social_data.*.color'  => ['required', 'string', 'regex:' . self::COLOR_REGEX],
            'social_data.*.link'   => 'required|url|max:255',
        ];
    }

    public static function validationMessages(): array
    {
        return [
            'social_data.required'          => 'Adicione ao menos uma rede social.',
            'social_data.min'               => 'Adicione ao menos uma rede social.',
            'social_data.*.name.required'   => 'O nome da rede social é obrigatório.',
            'social_data.*.icon.required'   => 'O ícone é obrigatório.',
            'social_data.*.color.required'  => 'A cor é obrigatória.',
            'social_data.*.color.regex'     => 'Cor inválida. Use hex (#fff) ou rgb/rgba.',
            'social_data.*.link.required'   => 'O link é obrigatório.',
            'social_data.*.link.url'        => 'O link deve ser uma URL válida.',
        ];
    }

    public function resolve(Sidebar $widget): array
    {
        return $widget->social_data ?? [];
    }
}