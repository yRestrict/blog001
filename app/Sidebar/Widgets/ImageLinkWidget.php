<?php

namespace App\Sidebar\Widgets;

use App\Models\Sidebar;

class ImageLinkWidget extends BaseWidget
{
    public static function type(): string  { return 'image_link'; }
    public static function label(): string { return 'Imagem com Link'; }
    public static function icon(): string  { return 'fa-image'; }
    public static function color(): string { return '#06b6d4'; }

    public static function validationRules(array $context = []): array
    {
        $isSlide        = ($context['display_mode'] ?? 'single') === 'slide';
        $hasExisting    = !empty($context['existingImage']);

        if ($isSlide) {
            return [
                'display_mode'           => 'required|in:single,slide',
                'slide_items'            => 'required|array|min:2|max:5',
                'slide_items.*.link'     => 'required|url',
                'slide_interval'         => 'nullable|integer|min:1000|max:30000',
                'image_width'            => 'nullable|integer|min:50|max:800',
                'image_height'           => 'nullable|integer|min:50|max:600',
            ];
        }

        return [
            'display_mode' => 'required|in:single,slide',
            'link'         => 'nullable|url',
            'image_width'  => 'nullable|integer|min:50|max:800',
            'image_height' => 'nullable|integer|min:50|max:600',
            'imageFile'    => $hasExisting
                              ? 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
                              : 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public static function validationMessages(): array
    {
        return [
            'imageFile.required'          => 'Uma imagem é obrigatória.',
            'imageFile.image'             => 'O arquivo deve ser uma imagem.',
            'imageFile.mimes'             => 'Formatos aceitos: jpeg, png, jpg, gif, webp.',
            'imageFile.max'               => 'A imagem não pode ultrapassar 2MB.',
            'slide_items.required'        => 'O slide precisa de ao menos 2 imagens.',
            'slide_items.min'             => 'O slide precisa de ao menos :min imagens.',
            'slide_items.max'             => 'O slide suporta no máximo :max imagens.',
            'slide_items.*.link.required' => 'Cada slide precisa de um link.',
            'slide_items.*.link.url'      => 'O link do slide deve ser uma URL válida.',
        ];
    }

    public function resolve(Sidebar $widget): array
    {
        $isSlide = $widget->slide_images && count($widget->slide_images) > 0;

        return [
            'display_mode'    => $isSlide ? 'slide' : 'single',
            'image'           => $widget->image ? asset('storage/' . $widget->image) : null,
            'link'            => $widget->link,
            'image_width'     => $widget->image_width,
            'image_height'    => $widget->image_height,
            'slide_images'    => collect($widget->slide_images ?? [])->map(fn($s) => [
                'image' => asset('storage/' . $s['image']),
                'link'  => $s['link'],
            ])->toArray(),
            'slide_interval'   => $widget->slide_interval,
            'slide_autoplay'   => $widget->slide_autoplay,
            'slide_controls'   => $widget->slide_controls,
            'slide_indicators' => $widget->slide_indicators,
        ];
    }
}