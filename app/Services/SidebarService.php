<?php

namespace App\Services;

use App\Models\Sidebar;
use App\Sidebar\WidgetRegistry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SidebarService
{
    /**
     * Tempo de cache em segundos (1 hora).
     * Sobrescreva via config('sidebar.cache_ttl').
     */
    private int $cacheTtl;

    /**
     * Chave de cache dos widgets renderizados.
     */
    private const CACHE_KEY = 'sidebar:rendered_widgets';

    public function __construct()
    {
        $this->cacheTtl = (int) config('sidebar.cache_ttl', 3600);
    }

    // ─── Front-end ────────────────────────────────────────────────────────────

    /**
     * Retorna os widgets ativos e ordenados, com dados resolvidos para o front.
     * O resultado é cacheado para evitar N queries por request.
     */
    public function getRenderedWidgets(): Collection
    {
        return Cache::remember(self::CACHE_KEY, $this->cacheTtl, function () {
            return Sidebar::active()
                ->ordered()
                ->get()
                ->map(function (Sidebar $widget) {
                    $widget->resolved_data = WidgetRegistry::has($widget->type)
                        ? WidgetRegistry::resolve($widget)
                        : [];
                    $widget->view_path = WidgetRegistry::has($widget->type)
                        ? WidgetRegistry::viewPath($widget->type)
                        : null;
                    return $widget;
                })
                ->filter(fn($w) => $w->view_path !== null);
        });
    }

    // ─── Cache management ─────────────────────────────────────────────────────

    /**
     * Invalida o cache da sidebar.
     * Chamado automaticamente pelo SidebarObserver.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Força reconstrução do cache imediatamente (warm-up).
     */
    public function warmUpCache(): void
    {
        self::clearCache();
        $this->getRenderedWidgets();
    }

    // ─── Admin ────────────────────────────────────────────────────────────────

    /**
     * Reordena os widgets salvando o campo `order` em batch.
     * Muito mais eficiente do que salvar um por um.
     */
    public function reorder(array $items): void
    {
        // $items = [['id' => 1, 'order' => 0], ['id' => 3, 'order' => 1], ...]
        foreach ($items as $item) {
            Sidebar::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        self::clearCache();
    }

    /**
     * Ativa/desativa um widget e invalida o cache.
     */
    public function toggleStatus(int $id): Sidebar
    {
        $widget = Sidebar::findOrFail($id);
        $widget->update(['status' => !$widget->status]);
        self::clearCache();
        return $widget->fresh();
    }
}