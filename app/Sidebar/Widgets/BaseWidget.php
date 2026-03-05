<?php

namespace App\Sidebar\Widgets;

use App\Sidebar\Contracts\WidgetInterface;

abstract class BaseWidget implements WidgetInterface
{
    public static function validationMessages(): array
    {
        return [];
    }

    public static function validationRules(array $context = []): array
    {
        return [];
    }

    public static function viewPath(): string
    {
        return 'components.sidebar.' . static::type();
    }
}