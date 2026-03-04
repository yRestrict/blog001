<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * MODEL: SidebarWidget
 *
 * Este é o model central do sistema. Representa um bloco dentro de uma sidebar.
 *
 * IMPORTANTE: Este model carrega o "Widget Registry" — a lista de todos os tipos
 * de widget disponíveis com suas configurações esperadas. Em vez de um Service
 * ou Provider separado, essa lógica vive aqui como métodos estáticos.
 *
 * Relacionamentos:
 *   SidebarWidget → belongsTo → Sidebar
 *   SidebarWidget → hasMany   → WidgetSetting
 */
class SidebarWidget extends Model
{
    protected $table = 'sidebar_widgets';

    protected $fillable = [
        'sidebar_id',
        'type',
        'title',
        'position',
        'active',
    ];

    protected $casts = [
        'active'   => 'boolean',
        'position' => 'integer',
    ];

    // =========================================================================
    // WIDGET REGISTRY
    //
    // Este array substitui o que seria um "Service" ou "Provider" externo.
    // Define todos os tipos de widget disponíveis e suas configurações.
    //
    // Estrutura de cada tipo:
    //   'label'    → nome legível para o painel
    //   'icon'     → ícone (Bootstrap Icons, FontAwesome, etc.)
    //   'settings' → array de configurações que este widget aceita
    //
    // Estrutura de cada setting:
    //   'type'     → tipo do valor: 'string', 'integer', 'boolean', 'json', 'select'
    //   'label'    → label do campo no formulário
    //   'default'  → valor padrão
    //   'options'  → para type 'select', os valores possíveis
    //   'min/max'  → para type 'integer', limites
    // =========================================================================

    /**
     * Retorna a definição de todos os tipos de widget disponíveis.
     * Chame com: SidebarWidget::availableTypes()
     *
     * Para adicionar um novo tipo: basta adicionar uma entrada aqui.
     * Não precisa de migration, não precisa de coluna nova na tabela.
     *
     * @return array<string, array>
     */
    public static function availableTypes(): array
    {
        return [
            // ------------------------------------------------------------------
            // BUSCA — sem configurações extras, apenas exibe um campo de busca
            // ------------------------------------------------------------------
            'search' => [
                'label'    => 'Busca',
                'icon'     => 'bi-search',
                'settings' => [], // nenhuma configuração necessária
            ],

            // ------------------------------------------------------------------
            // CATEGORIAS — exibe lista de categorias com opções de ordenação
            // ------------------------------------------------------------------
            'categories' => [
                'label' => 'Categorias',
                'icon'  => 'bi-folder',
                'settings' => [
                    'display_type' => [
                        'type'    => 'select',
                        'label'   => 'Tipo de Exibição',
                        'default' => 'most_posts',
                        // Cada opção é 'valor' => 'Label'
                        'options' => [
                            'most_posts'   => 'Mais Posts',
                            'most_visited' => 'Mais Visitadas',
                            'manual'       => 'Seleção Manual',
                        ],
                    ],
                    'limit' => [
                        'type'    => 'integer',
                        'label'   => 'Quantidade',
                        'default' => 8,
                        'min'     => 1,
                        'max'     => 20,
                    ],
                    // Usado apenas quando display_type === 'manual'
                    'selected_ids' => [
                        'type'    => 'json',
                        'label'   => 'Categorias Selecionadas',
                        'default' => [],
                    ],
                ],
            ],

            // ------------------------------------------------------------------
            // TAGS — mesma lógica das categorias, mas para tags
            // ------------------------------------------------------------------
            'tags' => [
                'label' => 'Tags',
                'icon'  => 'bi-tags',
                'settings' => [
                    'display_type' => [
                        'type'    => 'select',
                        'label'   => 'Tipo de Exibição',
                        'default' => 'most_posts',
                        'options' => [
                            'most_posts'   => 'Mais Posts',
                            'most_visited' => 'Mais Visitadas',
                            'manual'       => 'Seleção Manual',
                        ],
                    ],
                    'limit' => [
                        'type'    => 'integer',
                        'label'   => 'Quantidade de Tags',
                        'default' => 12,
                        'min'     => 1,
                        'max'     => 30,
                    ],
                    'selected_ids' => [
                        'type'    => 'json',
                        'label'   => 'Tags Selecionadas',
                        'default' => [],
                    ],
                ],
            ],

            // ------------------------------------------------------------------
            // POSTS POPULARES — exibe os posts com mais visualizações
            // ------------------------------------------------------------------
            'popular_posts' => [
                'label' => 'Posts Populares',
                'icon'  => 'bi-fire',
                'settings' => [
                    'limit' => [
                        'type'    => 'integer',
                        'label'   => 'Quantidade de Posts',
                        'default' => 5,
                        'min'     => 1,
                        'max'     => 10,
                    ],
                ],
            ],

            // ------------------------------------------------------------------
            // DOWNLOADS POPULARES — exibe os downloads mais acessados
            // ------------------------------------------------------------------
            'popular_downloads' => [
                'label' => 'Downloads Populares',
                'icon'  => 'bi-download',
                'settings' => [
                    'limit' => [
                        'type'    => 'integer',
                        'label'   => 'Quantidade',
                        'default' => 5,
                        'min'     => 1,
                        'max'     => 20,
                    ],
                    'period' => [
                        'type'    => 'select',
                        'label'   => 'Período',
                        'default' => 'week',
                        'options' => [
                            'week'  => 'Semana',
                            'month' => 'Mês',
                            'total' => 'Total',
                        ],
                    ],
                ],
            ],

            // ------------------------------------------------------------------
            // REDES SOCIAIS — links customizados com ícone e cor
            // ------------------------------------------------------------------
            'social_links' => [
                'label' => 'Redes Sociais',
                'icon'  => 'bi-share',
                'settings' => [
                    // JSON array de objetos: [{name, icon, color, url}]
                    'links' => [
                        'type'    => 'json',
                        'label'   => 'Links',
                        'default' => [],
                    ],
                ],
            ],

            // ------------------------------------------------------------------
            // IMAGEM COM LINK — exibe uma imagem clicável (modo único ou slide)
            // ------------------------------------------------------------------
            'image_link' => [
                'label' => 'Imagem com Link',
                'icon'  => 'bi-image',
                'settings' => [
                    'display_mode' => [
                        'type'    => 'select',
                        'label'   => 'Modo de Exibição',
                        'default' => 'single',
                        'options' => [
                            'single' => 'Imagem Única',
                            'slide'  => 'Slideshow',
                        ],
                    ],
                    // Modo single: caminho da imagem e link de destino
                    'image' => [
                        'type'    => 'string',
                        'label'   => 'Imagem',
                        'default' => null,
                    ],
                    'link' => [
                        'type'    => 'string',
                        'label'   => 'Link de Destino',
                        'default' => null,
                    ],
                    // Modo slide: JSON array de {image, link}
                    'slides' => [
                        'type'    => 'json',
                        'label'   => 'Slides',
                        'default' => [],
                    ],
                    'slide_interval' => [
                        'type'    => 'integer',
                        'label'   => 'Intervalo (ms)',
                        'default' => 5000,
                        'min'     => 1000,
                        'max'     => 30000,
                    ],
                ],
            ],

            // ------------------------------------------------------------------
            // WIDGET CUSTOMIZADO — HTML livre inserido pelo administrador
            // ------------------------------------------------------------------
            'custom' => [
                'label' => 'Customizado (HTML)',
                'icon'  => 'bi-code-slash',
                'settings' => [
                    'content' => [
                        'type'    => 'string',
                        'label'   => 'Conteúdo HTML',
                        'default' => '',
                    ],
                ],
            ],
        ];
    }

    /**
     * Retorna a definição de um tipo específico de widget.
     * Lança exceção se o tipo não existir — assim o erro é claro durante desenvolvimento.
     *
     * Uso: SidebarWidget::typeDefinition('categories')
     *
     * @param  string $type
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function typeDefinition(string $type): array
    {
        $types = static::availableTypes();

        if (!isset($types[$type])) {
            throw new \InvalidArgumentException("Tipo de widget '{$type}' não encontrado.");
        }

        return $types[$type];
    }

    // =========================================================================
    // RELACIONAMENTOS
    // =========================================================================

    /**
     * A sidebar à qual este widget pertence.
     * Uso: $widget->sidebar → retorna instância de Sidebar
     */
    public function sidebar(): BelongsTo
    {
        return $this->belongsTo(Sidebar::class);
    }

    /**
     * Todas as configurações deste widget.
     * Uso: $widget->settings → retorna Collection de WidgetSetting
     */
    public function settings(): HasMany
    {
        return $this->hasMany(WidgetSetting::class, 'widget_id');
    }

    // =========================================================================
    // LEITURA E ESCRITA DE SETTINGS
    // =========================================================================

    /**
     * Lê uma configuração deste widget com cast automático.
     *
     * Exemplos:
     *   $widget->getSetting('limit')           → 8   (integer)
     *   $widget->getSetting('display_type')    → 'most_posts'  (string)
     *   $widget->getSetting('selected_ids')    → [1, 2, 3]     (array)
     *   $widget->getSetting('active')          → true           (boolean)
     *   $widget->getSetting('missing', 'foo')  → 'foo'          (default)
     *
     * @param  string $key     Nome da configuração
     * @param  mixed  $default Valor padrão se não encontrado
     * @return mixed
     */
    public function getSetting(string $key, mixed $default = null): mixed
    {
        // Busca na Collection já carregada (sem nova query se usou eager loading)
        $setting = $this->settings->firstWhere('key', $key);

        // Configuração não encontrada → retorna o default
        if (!$setting) {
            return $default;
        }

        // Cast baseado no type armazenado na tabela
        // match() é como switch, mas mais moderno e retorna valor
        return match ($setting->type) {
            'integer' => (int) $setting->value,
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'json'    => json_decode($setting->value, true) ?? [],
            default   => $setting->value, // 'string' e qualquer outro → retorna como está
        };
    }

    /**
     * Salva ou atualiza uma configuração deste widget.
     *
     * updateOrCreate() faz:
     *   → Se existe um WidgetSetting com esse widget_id e key: ATUALIZA o value e type
     *   → Se não existe: CRIA um novo registro
     *
     * Exemplos:
     *   $widget->setSetting('limit', 8, 'integer')
     *   $widget->setSetting('display_type', 'most_posts')
     *   $widget->setSetting('selected_ids', [1, 2, 3], 'json')
     *
     * @param  string $key
     * @param  mixed  $value
     * @param  string $type  'string' | 'integer' | 'boolean' | 'json'
     * @return void
     */
    public function setSetting(string $key, mixed $value, string $type = 'string'): void
    {
        // Arrays e objetos precisam virar JSON para caber na coluna text
        $serialized = is_array($value)
            ? json_encode($value)
            : (string) $value;

        $this->settings()->updateOrCreate(
            ['key' => $key],                           // condição de busca
            ['value' => $serialized, 'type' => $type]  // dados para salvar/atualizar
        );
    }

    /**
     * Salva um conjunto de settings de uma vez.
     * Útil no controller ao processar o formulário de edição.
     *
     * Uso:
     *   $widget->saveSettings([
     *       'limit'        => [8, 'integer'],
     *       'display_type' => ['most_posts', 'string'],
     *       'selected_ids' => [[1, 2, 3], 'json'],
     *   ]);
     *
     * @param  array $settings ['key' => [value, type]]
     * @return void
     */
    public function saveSettings(array $settings): void
    {
        foreach ($settings as $key => [$value, $type]) {
            $this->setSetting($key, $value, $type);
        }

        // Invalida o cache da sidebar pai após salvar
        $this->sidebar->clearCache();
    }

    // =========================================================================
    // RESOLUÇÃO DE DADOS PARA O FRONTEND
    //
    // Este método substitui o que seria um "WidgetRenderer" Service.
    // Cada tipo de widget sabe buscar seus próprios dados.
    // =========================================================================

    /**
     * Retorna os dados necessários para renderizar este widget no frontend.
     *
     * O controller e o Blade Component chamam este método — eles não precisam
     * saber como cada tipo de widget busca seus dados.
     *
     * @return array
     */
    public function resolveData(): array
    {
        // match() chama o método privado correspondente ao tipo
        return match ($this->type) {
            'categories'        => $this->resolveCategoriesData(),
            'tags'              => $this->resolveTagsData(),
            'popular_posts'     => $this->resolvePopularPostsData(),
            'popular_downloads' => $this->resolvePopularDownloadsData(),
            'social_links'      => $this->resolveSocialLinksData(),
            'image_link'        => $this->resolveImageLinkData(),
            'custom'            => $this->resolveCustomData(),
            default             => [], // 'search' e outros sem dados
        };
    }

    // -------------------------------------------------------------------------
    // Métodos privados de resolução — um por tipo de widget
    // Privados pois são detalhes de implementação, não API pública do model
    // -------------------------------------------------------------------------

    private function resolveCategoriesData(): array
    {
        $displayType = $this->getSetting('display_type', 'most_posts');
        $limit       = $this->getSetting('limit', 8);

        $query = \App\Models\Category::query()->where('status', true);

        if ($displayType === 'manual') {
            // Seleção manual: busca apenas as categorias escolhidas pelo admin
            $ids = $this->getSetting('selected_ids', []);
            return $query->whereIn('id', $ids)->get()->toArray();
        }

        if ($displayType === 'most_visited') {
            // Ordena pelo campo de visitas (ajuste conforme sua tabela)
            return $query->orderByDesc('views_count')->limit($limit)->get()->toArray();
        }

        // Padrão: mais posts — withCount faz JOIN contando os posts
        return $query->withCount('posts')
                     ->orderByDesc('posts_count')
                     ->limit($limit)
                     ->get()
                     ->toArray();
    }

    private function resolveTagsData(): array
    {
        $displayType = $this->getSetting('display_type', 'most_posts');
        $limit       = $this->getSetting('limit', 12);

        $query = \App\Models\Tag::query()->has('posts');

        if ($displayType === 'manual') {
            $ids = $this->getSetting('selected_ids', []);
            return $query->whereIn('id', $ids)->get()->toArray();
        }

        if ($displayType === 'most_visited') {
            return $query->orderByDesc('views_count')->limit($limit)->get()->toArray();
        }

        return $query->withCount('posts')
                     ->orderByDesc('posts_count')
                     ->limit($limit)
                     ->get()
                     ->toArray();
    }

    private function resolvePopularPostsData(): array
    {
        $limit = $this->getSetting('limit', 5);

        return \App\Models\Post::query()
            ->where('status', true)
            ->orderByDesc('views_count') // ajuste conforme seu campo de views
            ->limit($limit)
            ->get(['id', 'title', 'slug', 'thumbnail', 'views_count'])
            ->toArray();
    }

    private function resolvePopularDownloadsData(): array
    {
        $limit  = $this->getSetting('limit', 5);
        $period = $this->getSetting('period', 'week');

        // O escopo do período é aplicado condicionalmente
        $query = \App\Models\Post::query()->where('status', true);

        // Filtra por período apenas se não for 'total'
        if ($period !== 'total') {
            $date = $period === 'week'
                ? now()->subWeek()
                : now()->subMonth();

            // Supondo que você tem uma tabela de downloads com created_at
            $query->whereHas('downloads', fn($q) => $q->where('created_at', '>=', $date));
        }

        return $query->withCount('downloads')
                     ->orderByDesc('downloads_count')
                     ->limit($limit)
                     ->get(['id', 'title', 'slug', 'downloads_count'])
                     ->toArray();
    }

    private function resolveSocialLinksData(): array
    {
        // Retorna o array de links com name, icon, color, url
        return $this->getSetting('links', []);
    }

    private function resolveImageLinkData(): array
    {
        $mode = $this->getSetting('display_mode', 'single');

        if ($mode === 'slide') {
            return [
                'mode'           => 'slide',
                'slides'         => $this->getSetting('slides', []),
                'slide_interval' => $this->getSetting('slide_interval', 5000),
            ];
        }

        return [
            'mode'  => 'single',
            'image' => $this->getSetting('image'),
            'link'  => $this->getSetting('link'),
        ];
    }

    private function resolveCustomData(): array
    {
        return [
            'content' => $this->getSetting('content', ''),
        ];
    }

    // =========================================================================
    // EVENTOS DO MODEL
    // =========================================================================

    protected static function boot(): void
    {
        parent::boot();

        // Quando um widget é salvo ou deletado, invalida o cache da sidebar pai
        static::saved(function (SidebarWidget $widget) {
            // Precisa carregar a sidebar se não estiver no eager loading
            $widget->load('sidebar');
            $widget->sidebar?->clearCache();
        });

        static::deleted(function (SidebarWidget $widget) {
            $widget->sidebar?->clearCache();
        });
    }
}