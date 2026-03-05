<?php

namespace App\Sidebar;

use App\Models\Sidebar;
use App\Sidebar\Contracts\WidgetInterface;
use App\Sidebar\Widgets\SearchWidget;
use App\Sidebar\Widgets\CategoriesWidget;
use App\Sidebar\Widgets\PopularPostsWidget;
use App\Sidebar\Widgets\PopularDownloadsWidget;
use App\Sidebar\Widgets\TagsWidget;
use App\Sidebar\Widgets\SocialLinksWidget;
use App\Sidebar\Widgets\ImageLinkWidget;
use App\Sidebar\Widgets\CustomWidget;
use InvalidArgumentException;

class WidgetRegistry
{
    /**
     * Mapa de type => classe do widget.
     * Para adicionar um novo tipo: basta registrá-lo aqui.
     */
    private static array $registry = [
        'search'            => SearchWidget::class,
        'categories'        => CategoriesWidget::class,
        'popular_posts'     => PopularPostsWidget::class,
        'popular_downloads' => PopularDownloadsWidget::class,
        'tags'              => TagsWidget::class,
        'social_links'      => SocialLinksWidget::class,
        'image_link'        => ImageLinkWidget::class,
        'custom'            => CustomWidget::class,
    ];

    // ─── Registro dinâmico ────────────────────────────────────────────────────

    /**
     * Registra um novo tipo de widget em runtime.
     * Útil para pacotes ou módulos externos.
     */
    public static function register(string $class): void
    {
        if (!is_subclass_of($class, WidgetInterface::class)) {
            throw new InvalidArgumentException("{$class} deve implementar WidgetInterface.");
        }
        self::$registry[$class::type()] = $class;
    }

    // ─── Consulta ─────────────────────────────────────────────────────────────

    /**
     * Retorna todos os tipos registrados com seus metadados.
     * Usado no painel para exibir o seletor de tipo.
     */
    public static function all(): array
    {
        return collect(self::$registry)->map(fn($class) => [
            'type'  => $class::type(),
            'label' => $class::label(),
            'icon'  => $class::icon(),
            'color' => $class::color(),
        ])->values()->all();
    }

    /**
     * Retorna a classe do widget para o tipo informado.
     *
     * @throws InvalidArgumentException
     */
    public static function get(string $type): string
    {
        if (!isset(self::$registry[$type])) {
            throw new InvalidArgumentException("Tipo de widget desconhecido: '{$type}'.");
        }
        return self::$registry[$type];
    }

    /**
     * Instancia o widget para o tipo informado.
     */
    public static function make(string $type): WidgetInterface
    {
        $class = self::get($type);
        return new $class();
    }

    /**
     * Retorna regras de validação do tipo + contexto do formulário.
     */
    public static function validationRules(string $type, array $context = []): array
    {
        return self::get($type)::validationRules($context);
    }

    /**
     * Retorna mensagens de validação do tipo.
     */
    public static function validationMessages(string $type): array
    {
        return self::get($type)::validationMessages();
    }

    /**
     * Resolve os dados de um widget para renderização no front-end.
     */
    public static function resolve(Sidebar $widget): array
    {
        return self::make($widget->type)->resolve($widget);
    }

    /**
     * Retorna a view path do widget.
     */
    public static function viewPath(string $type): string
    {
        return self::get($type)::viewPath();
    }

    /**
     * Verifica se um tipo está registrado.
     */
    public static function has(string $type): bool
    {
        return isset(self::$registry[$type]);
    }
}