<?php

namespace App\Livewire\Admin;

use App\Models\Sidebar;
use App\Models\SidebarWidget;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;

/**
 * LIVEWIRE: WidgetManager
 *
 * Gerencia os widgets de UMA sidebar específica.
 * Substitui o SidebarWidgetController.
 *
 * Funcionalidades:
 *  - Listar widgets com drag & drop para reordenar
 *  - Adicionar widget (escolher tipo → formulário dinâmico aparece na hora)
 *  - Editar widget (formulário dinâmico por tipo, sem redirect)
 *  - Deletar widget
 *  - Toggle ativar/desativar
 *
 * FORMULÁRIO DINÂMICO:
 * Quando o admin escolhe o tipo (ex: 'categories'), os campos específicos
 * aparecem automaticamente via @if no Blade, sem JavaScript nenhum.
 * Isso é o grande ganho do Livewire aqui.
 *
 * Como registrar a rota:
 *   Route::get('/dashboard/sidebars/{sidebar}/widgets', WidgetManager::class)
 *        ->name('dashboard.sidebars.widgets');
 */
class WidgetManager extends Component
{
    // Permite upload de arquivos (imagens para o widget image_link)
    use WithFileUploads;

    // =========================================================================
    // PROPRIEDADES DE ESTADO
    // =========================================================================

    // A sidebar que estamos gerenciando (passada pela rota ou como parâmetro)
    public Sidebar $sidebar;

    // Controla qual modal está aberto
    public bool $showModal    = false;
    public bool $showTypeStep = true; // true = seleção de tipo, false = formulário

    // Widget sendo editado (null = modo criação)
    public ?int $editingId = null;

    // =========================================================================
    // CAMPOS BASE DO WIDGET (todos os tipos)
    // =========================================================================

    #[Rule('required|in:search,categories,tags,popular_posts,popular_downloads,social_links,image_link,custom')]
    public string $type = '';

    #[Rule('nullable|string|max:255')]
    public string $title = '';

    #[Rule('boolean')]
    public bool $active = true;

    // =========================================================================
    // CAMPOS DE SETTINGS — cada grupo pertence a um tipo de widget
    //
    // Todas as settings ficam aqui como propriedades públicas.
    // O Livewire sincroniza via wire:model automaticamente.
    // Na view, cada grupo aparece dentro de um @if($type === 'categories') etc.
    // =========================================================================

    // --- CATEGORIES & TAGS (mesmos campos, tipos diferentes) -----------------
    public string $displayType  = 'most_posts';
    public int    $limit        = 8;
    public array  $selectedIds  = []; // IDs selecionados manualmente

    // --- POPULAR DOWNLOADS ---------------------------------------------------
    public string $period       = 'week';

    // --- SOCIAL LINKS --------------------------------------------------------
    // Array de links: cada item é [name, icon, color, url]
    public array $socialLinks   = [];

    // --- IMAGE LINK ----------------------------------------------------------
    public string $displayMode  = 'single';
    public ?string $existingImage = null; // caminho da imagem atual no storage
    public $imageFile           = null;   // novo upload (WithFileUploads)
    public string $imageUrl     = '';     // link de destino
    public array  $slides       = [];     // array de slides para modo slideshow
    public int    $slideInterval = 5000;

    // --- CUSTOM --------------------------------------------------------------
    public string $content      = '';

    // =========================================================================
    // DADOS EXTRAS PARA O FORMULÁRIO
    //
    // Listas que alguns formulários precisam exibir — ex: todas as categorias
    // para o modo de seleção manual. Carregadas apenas quando necessário.
    // =========================================================================

    // Lista de categorias disponíveis (carregada quando type = 'categories')
    public $categoriesList = [];

    // Lista de tags disponíveis (carregada quando type = 'tags')
    public $tagsList = [];

    // =========================================================================
    // MOUNT
    //
    // mount() é o "construtor" do componente Livewire.
    // Chamado UMA vez quando o componente é inicializado.
    // Recebe parâmetros passados pela rota ou pela tag do componente.
    // =========================================================================

    /**
     * @param Sidebar $sidebar  Injetado pelo Route Model Binding do Laravel
     */
    public function mount(Sidebar $sidebar): void
    {
        $this->sidebar = $sidebar;
    }

    // =========================================================================
    // COMPUTED PROPERTIES
    // =========================================================================

    /**
     * Lista de widgets da sidebar, com settings carregadas (eager loading).
     * Recalcula toda vez que o estado do componente muda.
     */
    public function getWidgetsProperty()
    {
        return $this->sidebar
                    ->widgets()
                    ->with('settings')
                    ->orderBy('position')
                    ->get();
    }

    /**
     * Todos os tipos de widget disponíveis.
     * Vem do registry no Model — sem Service.
     */
    public function getAvailableTypesProperty(): array
    {
        return SidebarWidget::availableTypes();
    }

    // =========================================================================
    // LIFECYCLE HOOKS
    // =========================================================================

    /**
     * Chamado automaticamente quando $type muda (wire:model="type").
     * Atualiza o limit padrão e carrega dados extras conforme o tipo.
     */
    public function updatedType(string $value): void
    {
        // Reset dos campos ao trocar de tipo
        $this->selectedIds = [];
        $this->socialLinks = [];
        $this->slides      = [];

        // Carrega o limit padrão do registry para o tipo escolhido
        $definition  = SidebarWidget::availableTypes()[$value] ?? [];
        $this->limit = $definition['settings']['limit']['default'] ?? 5;

        // Carrega dados extras necessários para o formulário
        $this->loadExtraData($value);
    }

    /**
     * Gera preview da imagem ao fazer upload.
     * Chamado automaticamente quando $imageFile muda.
     */
    public function updatedImageFile(): void
    {
        $this->validate(['imageFile' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048']);
    }

    // =========================================================================
    // AÇÕES — ABERTURA DE MODAL
    // =========================================================================

    /**
     * Abre o modal na etapa de seleção de tipo (passo 1).
     * Na view: wire:click="openCreate"
     */
    public function openCreate(): void
    {
        $this->resetForm();
        $this->editingId    = null;
        $this->showTypeStep = true;
        $this->showModal    = true;
    }

    /**
     * Avança para o passo 2: formulário do tipo selecionado.
     * Na view: wire:click="selectType('categories')"
     *
     * @param string $type
     */
    public function selectType(string $type): void
    {
        $this->type         = $type;
        $this->showTypeStep = false;
        $this->loadExtraData($type);
    }

    /**
     * Abre o modal em modo EDIÇÃO, preenchendo todos os campos.
     * Na view: wire:click="openEdit({{ $widget->id }})"
     *
     * @param int $id
     */
    public function openEdit(int $id): void
    {
        $widget = SidebarWidget::with('settings')->findOrFail($id);

        // Preenche campos base
        $this->editingId    = $widget->id;
        $this->type         = $widget->type;
        $this->title        = $widget->title ?? '';
        $this->active       = $widget->active;
        $this->showTypeStep = false; // no modo edição, vai direto pro formulário

        // Preenche settings conforme o tipo — getSetting() faz o cast automático
        $this->fillSettingsFromWidget($widget);

        // Carrega dados extras (lista de categorias, tags, etc.)
        $this->loadExtraData($widget->type);

        $this->showModal = true;
    }

    /**
     * Fecha o modal.
     * Na view: wire:click="closeModal"
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    // =========================================================================
    // AÇÕES — CRUD
    // =========================================================================

    /**
     * Salva o widget — cria ou atualiza dependendo de $editingId.
     * Na view: wire:submit="save" no form
     */
    public function save(): void
    {
        // Validação base
        $this->validate([
            'title'  => 'nullable|string|max:255',
            'active' => 'boolean',
        ]);

        // Validação específica do tipo
        $this->validateByType();

        if ($this->editingId) {
            // EDIÇÃO: atualiza o widget existente
            $widget = SidebarWidget::findOrFail($this->editingId);
            $widget->update([
                'title'  => $this->title ?: null,
                'active' => $this->active,
            ]);
        } else {
            // CRIAÇÃO: cria o widget na última posição
            $position = $this->sidebar->widgets()->max('position') + 1;

            $widget = $this->sidebar->widgets()->create([
                'type'     => $this->type,
                'title'    => $this->title ?: null,
                'active'   => $this->active,
                'position' => $position,
            ]);
        }

        // Recarrega as settings antes de salvar (para getSetting funcionar no edit)
        $widget->load('settings');

        // Salva as settings específicas do tipo
        $this->saveSettingsByType($widget);

        $this->closeModal();

        $action = $this->editingId ? 'atualizado' : 'adicionado';
        $this->dispatch('notify', message: "Widget {$action} com sucesso!", type: 'success');
    }

    /**
     * Deleta um widget e suas settings (cascadeOnDelete).
     * Na view: wire:click="delete({{ $widget->id }})" wire:confirm="Tem certeza?"
     *
     * @param int $id
     */
    public function delete(int $id): void
    {
        $widget = SidebarWidget::findOrFail($id);

        // Reordena os demais widgets para não deixar buracos
        $this->sidebar->widgets()
                      ->where('position', '>', $widget->position)
                      ->decrement('position');

        $widget->delete();
        $this->sidebar->clearCache();

        $this->dispatch('notify', message: 'Widget removido!', type: 'success');
    }

    /**
     * Ativa ou desativa um widget.
     * Na view: wire:click="toggle({{ $widget->id }})"
     *
     * @param int $id
     */
    public function toggle(int $id): void
    {
        $widget = SidebarWidget::findOrFail($id);
        $widget->update(['active' => !$widget->active]);
        $this->sidebar->clearCache();
    }

    /**
     * Reordena os widgets após drag & drop.
     *
     * O Livewire Sortable (livewire-sortable) chama este método automaticamente
     * quando o usuário termina de arrastar. Recebe o array na nova ordem.
     *
     * Instale com: npm install --save @livewire-ui/sortable
     * Na view: wire:sortable="reorder" nos itens da lista
     *
     * @param array $order  [['value' => id, 'order' => posicao], ...]
     */
    public function reorder(array $order): void
    {
        foreach ($order as $item) {
            SidebarWidget::where('id', $item['value'])
                         ->update(['position' => $item['order']]);
        }

        $this->sidebar->clearCache();
    }

    /**
     * Adiciona um link vazio ao array de redes sociais.
     * Na view: wire:click="addSocialLink"
     */
    public function addSocialLink(): void
    {
        // Limite de 8 links por widget
        if (count($this->socialLinks) >= 8) return;

        $this->socialLinks[] = ['name' => '', 'icon' => '', 'color' => '#000000', 'url' => ''];
    }

    /**
     * Remove um link do array de redes sociais.
     * Na view: wire:click="removeSocialLink({{ $index }})"
     *
     * @param int $index
     */
    public function removeSocialLink(int $index): void
    {
        // array_values() reindexar o array após remover
        unset($this->socialLinks[$index]);
        $this->socialLinks = array_values($this->socialLinks);
    }

    /**
     * Adiciona um slide vazio ao slideshow.
     * Na view: wire:click="addSlide"
     */
    public function addSlide(): void
    {
        if (count($this->slides) >= 5) return;

        $this->slides[] = ['existing' => null, 'file' => null, 'link' => ''];
    }

    /**
     * Remove um slide do array.
     * Na view: wire:click="removeSlide({{ $index }})"
     *
     * @param int $index
     */
    public function removeSlide(int $index): void
    {
        unset($this->slides[$index]);
        $this->slides = array_values($this->slides);
    }

    // =========================================================================
    // RENDER
    // =========================================================================

    public function render()
    {
        return view('livewire.admin.widget-manager');
            ;
    }

    // =========================================================================
    // MÉTODOS PRIVADOS
    // =========================================================================

    /**
     * Preenche as propriedades do formulário com os dados de um widget existente.
     * Usado no openEdit() para popular o modal de edição.
     *
     * @param SidebarWidget $widget
     */
    private function fillSettingsFromWidget(SidebarWidget $widget): void
    {
        // match() chama o preenchimento correto para cada tipo
        match ($widget->type) {
            'categories', 'tags' => function () use ($widget) {
                $this->displayType = $widget->getSetting('display_type', 'most_posts');
                $this->limit       = $widget->getSetting('limit', 8);
                $this->selectedIds = $widget->getSetting('selected_ids', []);
            },
            'popular_posts' => function () use ($widget) {
                $this->limit = $widget->getSetting('limit', 5);
            },
            'popular_downloads' => function () use ($widget) {
                $this->limit  = $widget->getSetting('limit', 5);
                $this->period = $widget->getSetting('period', 'week');
            },
            'social_links' => function () use ($widget) {
                $this->socialLinks = $widget->getSetting('links', []);
            },
            'image_link' => function () use ($widget) {
                $this->displayMode    = $widget->getSetting('display_mode', 'single');
                $this->existingImage  = $widget->getSetting('image');
                $this->imageUrl       = $widget->getSetting('link', '');
                $this->slides         = $widget->getSetting('slides', []);
                $this->slideInterval  = $widget->getSetting('slide_interval', 5000);
            },
            'custom' => function () use ($widget) {
                $this->content = $widget->getSetting('content', '');
            },
            default => null,
        };

        // Executa as closures (match retorna a closure, precisamos chamá-la)
        // Nota: em PHP 8.1+ match com closures precisa ser invocado
        // Alternativa mais simples abaixo:
        $this->fillByType($widget);
    }

    /**
     * Versão alternativa mais clara do fillSettingsFromWidget.
     * Usa if/match direto em vez de closures.
     */
    private function fillByType(SidebarWidget $widget): void
    {
        match ($widget->type) {
            'categories', 'tags' => $this->fillCategoryTagSettings($widget),
            'popular_posts'      => $this->fillPopularPostsSettings($widget),
            'popular_downloads'  => $this->fillPopularDownloadsSettings($widget),
            'social_links'       => $this->fillSocialLinksSettings($widget),
            'image_link'         => $this->fillImageLinkSettings($widget),
            'custom'             => $this->fillCustomSettings($widget),
            default              => null,
        };
    }

    private function fillCategoryTagSettings(SidebarWidget $widget): void
    {
        $defaultLimit      = $widget->type === 'tags' ? 12 : 8;
        $this->displayType = $widget->getSetting('display_type', 'most_posts');
        $this->limit       = $widget->getSetting('limit', $defaultLimit);
        $this->selectedIds = $widget->getSetting('selected_ids', []);
    }

    private function fillPopularPostsSettings(SidebarWidget $widget): void
    {
        $this->limit = $widget->getSetting('limit', 5);
    }

    private function fillPopularDownloadsSettings(SidebarWidget $widget): void
    {
        $this->limit  = $widget->getSetting('limit', 5);
        $this->period = $widget->getSetting('period', 'week');
    }

    private function fillSocialLinksSettings(SidebarWidget $widget): void
    {
        $this->socialLinks = $widget->getSetting('links', []);
    }

    private function fillImageLinkSettings(SidebarWidget $widget): void
    {
        $this->displayMode   = $widget->getSetting('display_mode', 'single');
        $this->existingImage = $widget->getSetting('image');
        $this->imageUrl      = $widget->getSetting('link', '');
        $this->slides        = $widget->getSetting('slides', []);
        $this->slideInterval = $widget->getSetting('slide_interval', 5000);
    }

    private function fillCustomSettings(SidebarWidget $widget): void
    {
        $this->content = $widget->getSetting('content', '');
    }

    /**
     * Carrega dados extras para o formulário.
     * Em vez de um Service, chama os Models diretamente.
     *
     * @param string $type
     */
    private function loadExtraData(string $type): void
    {
        match ($type) {
            'categories' => $this->categoriesList = \App\Models\Category::where('status', true)
                ->withCount('posts')
                ->orderBy('title')
                ->get(['id', 'title', 'posts_count']),

            'tags' => $this->tagsList = \App\Models\Tag::has('posts')
                ->withCount('posts')
                ->orderBy('name')
                ->get(['id', 'name', 'posts_count']),

            default => null,
        };
    }

    /**
     * Validação específica por tipo antes de salvar.
     */
    private function validateByType(): void
    {
        match ($this->type) {
            'categories', 'tags' => $this->validate([
                'displayType' => 'required|in:most_posts,most_visited,manual',
                'limit'       => 'required|integer|min:1|max:30',
                'selectedIds' => 'required_if:displayType,manual|array',
            ]),
            'popular_posts' => $this->validate([
                'limit' => 'required|integer|min:1|max:10',
            ]),
            'popular_downloads' => $this->validate([
                'limit'  => 'required|integer|min:1|max:20',
                'period' => 'required|in:week,month,total',
            ]),
            'social_links' => $this->validate([
                'socialLinks'          => 'required|array|min:1',
                'socialLinks.*.name'   => 'required|string|max:100',
                'socialLinks.*.icon'   => 'required|string|max:100',
                'socialLinks.*.color'  => 'required|string|max:20',
                'socialLinks.*.url'    => 'required|url',
            ]),
            'image_link' => $this->validate([
                'displayMode'   => 'required|in:single,slide',
                'imageFile'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'imageUrl'      => 'nullable|url',
                'slideInterval' => 'nullable|integer|min:1000|max:30000',
            ]),
            'custom' => $this->validate([
                'content' => 'required|string',
            ]),
            default => null,
        };
    }

    /**
     * Salva as settings específicas de cada tipo no banco.
     * Chama saveSettings() do Model que faz updateOrCreate por chave.
     *
     * @param SidebarWidget $widget
     */
    private function saveSettingsByType(SidebarWidget $widget): void
    {
        match ($widget->type) {
            'categories', 'tags' => $widget->saveSettings([
                'display_type' => [$this->displayType, 'string'],
                'limit'        => [$this->limit, 'integer'],
                'selected_ids' => [$this->selectedIds, 'json'],
            ]),
            'popular_posts' => $widget->saveSettings([
                'limit' => [$this->limit, 'integer'],
            ]),
            'popular_downloads' => $widget->saveSettings([
                'limit'  => [$this->limit, 'integer'],
                'period' => [$this->period, 'string'],
            ]),
            'social_links' => $widget->saveSettings([
                'links' => [$this->socialLinks, 'json'],
            ]),
            'image_link'   => $this->saveImageLinkSettingsToModel($widget),
            'custom'       => $widget->saveSettings([
                'content' => [$this->content, 'string'],
            ]),
            default => null,
        };
    }

    /**
     * Trata upload de imagem e salva settings do widget image_link.
     *
     * @param SidebarWidget $widget
     */
    private function saveImageLinkSettingsToModel(SidebarWidget $widget): void
    {
        if ($this->displayMode === 'single') {
            $imagePath = $this->existingImage; // mantém existente por padrão

            // Se veio nova imagem, faz o upload
            if ($this->imageFile) {
                // Deleta a antiga se existir
                if ($imagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
                }
                // store() do Livewire WithFileUploads faz o upload
                $imagePath = $this->imageFile->store('widget-images', 'public');
            }

            $widget->saveSettings([
                'display_mode' => [$this->displayMode, 'string'],
                'image'        => [$imagePath, 'string'],
                'link'         => [$this->imageUrl, 'string'],
                'slides'       => [[], 'json'],
            ]);

        } else {
            // Modo slide — processa cada slide do array
            $slides = [];
            foreach ($this->slides as $slide) {
                $path = $slide['existing'] ?? null;
                if (isset($slide['file']) && $slide['file'] instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    $path = $slide['file']->store('widget-slides', 'public');
                }
                if ($path) {
                    $slides[] = ['image' => $path, 'link' => $slide['link'] ?? ''];
                }
            }

            $widget->saveSettings([
                'display_mode'   => [$this->displayMode, 'string'],
                'slides'         => [$slides, 'json'],
                'slide_interval' => [$this->slideInterval, 'integer'],
                'image'          => [null, 'string'],
                'link'           => [null, 'string'],
            ]);
        }
    }

    /**
     * Reseta todo o formulário para o estado inicial.
     */
    private function resetForm(): void
    {
        $this->type          = '';
        $this->title         = '';
        $this->active        = true;
        $this->displayType   = 'most_posts';
        $this->limit         = 8;
        $this->selectedIds   = [];
        $this->period        = 'week';
        $this->socialLinks   = [];
        $this->displayMode   = 'single';
        $this->existingImage = null;
        $this->imageFile     = null;
        $this->imageUrl      = '';
        $this->slides        = [];
        $this->slideInterval = 5000;
        $this->content       = '';
        $this->categoriesList = [];
        $this->tagsList       = [];
        $this->resetErrorBag();
    }
}