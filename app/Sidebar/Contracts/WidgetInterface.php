<?php

namespace App\Sidebar\Contracts;

use App\Models\Sidebar;

interface WidgetInterface
{
    /**
     * Tipo único do widget (deve bater com o campo `type` na DB).
     */
    public static function type(): string;

    /**
     * Label legível para humanos exibido no painel.
     */
    public static function label(): string;

    /**
     * Ícone FontAwesome (ex: "fa-search").
     */
    public static function icon(): string;

    /**
     * Cor hex padrão do widget no painel (ex: "#6366f1").
     */
    public static function color(): string;

    /**
     * Regras de validação Laravel (mescladas com as regras base do componente).
     */
    public static function validationRules(array $context = []): array;

    /**
     * Mensagens de validação customizadas.
     */
    public static function validationMessages(): array;

    /**
     * Dados resolvidos para renderização no front-end.
     * Chamado pelo SidebarService — já cacheado externamente.
     */
    public function resolve(Sidebar $widget): array;

    /**
     * Nome da view parcial para o front-end.
     * Ex: "components.sidebar.search"
     */
    public static function viewPath(): string;
}