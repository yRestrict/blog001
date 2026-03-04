<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

/**
 * MODEL: Sidebar
 *
 * Representa uma ÁREA do layout que pode conter widgets.
 * Exemplos: "Sidebar Principal", "Sidebar do Post", "Footer Lateral"
 *
 * Relacionamentos:
 *   Sidebar → hasMany → SidebarWidget
 *
 * Como usar nas views:
 *   <x-sidebar area="main-sidebar" />
 */
class Sidebar extends Model
{
    /**
     * Campos que podem ser preenchidos via mass assignment (create/update).
     * Campos fora desta lista serão ignorados por segurança.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'active',
    ];

    /**
     * Casts automáticos — o Laravel converte o valor do banco para o tipo correto.
     * 'active' no banco é 0/1, mas o PHP vai receber true/false.
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    // =========================================================================
    // RELACIONAMENTOS
    // =========================================================================

    /**
     * Todos os widgets desta sidebar, ordenados por posição.
     *
     * Uso: $sidebar->widgets → retorna uma Collection de SidebarWidget
     */
    public function widgets(): HasMany
    {
        return $this->hasMany(SidebarWidget::class)->orderBy('position');
    }

    /**
     * Apenas os widgets ATIVOS desta sidebar, ordenados por posição.
     * Usado no frontend para não exibir widgets desativados.
     *
     * Uso: $sidebar->activeWidgets → retorna Collection filtrada
     */
    public function activeWidgets(): HasMany
    {
        return $this->hasMany(SidebarWidget::class)
                    ->where('active', true)
                    ->orderBy('position');
    }

    // =========================================================================
    // SCOPES
    // Scopes são filtros reutilizáveis para queries.
    // Uso: Sidebar::active()->get() em vez de Sidebar::where('active', true)->get()
    // =========================================================================

    /**
     * Scope: apenas sidebars ativas.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // =========================================================================
    // MÉTODOS ESTÁTICOS (lógica de busca sem Service)
    // =========================================================================

    /**
     * Busca uma sidebar pelo slug, com seus widgets e settings carregados.
     * Usa cache para não fazer a mesma query toda requisição.
     *
     * O "with()" é eager loading — carrega tudo de uma vez (3 queries)
     * em vez de fazer N queries (problema N+1).
     *
     * Uso: Sidebar::getBySlug('main-sidebar')
     *
     * @param  string $slug
     * @return static|null
     */
    public static function getBySlug(string $slug): ?static
    {
        // Cache::remember(chave, segundos, função)
        // Se a chave existir no cache, retorna direto. Senão, executa a função e salva.
        return Cache::remember("sidebar:{$slug}", 3600, function () use ($slug) {
            return static::with([
                        // Carrega os widgets ativos com suas settings — eager loading
                        'activeWidgets',
                        'activeWidgets.settings',
                    ])
                    ->where('slug', $slug)
                    ->where('active', true)
                    ->first();
        });
    }

    /**
     * Invalida o cache desta sidebar.
     * Chamado quando qualquer widget é salvo ou deletado.
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget("sidebar:{$this->slug}");
    }

    // =========================================================================
    // EVENTOS DO MODEL (boot)
    // Executados automaticamente pelo Laravel em certas ações.
    // =========================================================================

    /**
     * O método boot() registra eventos do model.
     * Aqui usamos para invalidar o cache automaticamente ao salvar/deletar.
     */
    protected static function boot(): void
    {
        parent::boot();

        // Toda vez que uma sidebar for salva (create ou update), limpa o cache
        static::saved(function (Sidebar $sidebar) {
            $sidebar->clearCache();
        });

        // Toda vez que uma sidebar for deletada, limpa o cache
        static::deleted(function (Sidebar $sidebar) {
            $sidebar->clearCache();
        });
    }
}