<?php

namespace App\Livewire\Admin;

use App\Models\Sidebar as SidebarModel;
use App\Models\Category;
use App\Models\Tag;
use App\Services\SidebarService;
use App\Sidebar\Support\ImageLinkProcessor;
use App\Sidebar\WidgetRegistry;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Sidebar extends Component
{
    use WithFileUploads;

    // ─── Estado UI ────────────────────────────────────────────────────────────
    public bool   $showModal       = false;
    public bool   $isEditing       = false;
    public ?int   $editingId       = null;
    public bool   $showTypePicker  = true;
    public ?int   $confirmDeleteId = null;

    // ─── Campos do formulário ─────────────────────────────────────────────────
    public string  $type            = '';
    public string  $title           = '';
    public bool    $status          = true;
    public int     $limit           = 5;
    public string  $period_type     = 'week';
    public string  $content         = '';
    public string  $link            = '';

    // categories
    public string  $category_display_type = 'most_posts';
    public int     $category_limit        = 8;
    public array   $selected_categories   = [];

    // tags
    public string  $tag_display_type = 'most_posts';
    public int     $tag_limit        = 12;
    public array   $selected_tags    = [];

    // social_links
    public array   $social_data = [];

    // image_link
    public string  $display_mode     = 'single';
    public         $imageFile        = null;
    public string  $existingImage    = '';
    public int     $image_width      = 300;
    public int     $image_height     = 150;
    public array   $slide_items      = [];
    public int     $slide_interval   = 5000;
    public bool    $slide_autoplay   = true;
    public bool    $slide_controls   = true;
    public bool    $slide_indicators = true;

    // ─── Validação dinâmica via Registry ─────────────────────────────────────

    protected function rules(): array
    {
        $base = [
            'title'  => 'nullable|string|max:255',
            'status' => 'boolean',
        ];

        if (!$this->type || !WidgetRegistry::has($this->type)) {
            return $base;
        }

        $context = [
            'category_display_type' => $this->category_display_type,
            'tag_display_type'      => $this->tag_display_type,
            'display_mode'          => $this->display_mode,
            'existingImage'         => $this->existingImage,
        ];

        return array_merge($base, WidgetRegistry::validationRules($this->type, $context));
    }

    protected function messages(): array
    {
        $base = ['title.max' => 'O título não pode passar de 255 caracteres.'];

        if (!$this->type || !WidgetRegistry::has($this->type)) {
            return $base;
        }

        return array_merge($base, WidgetRegistry::validationMessages($this->type));
    }

    // =========================================================================
    // MODAL
    // =========================================================================

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing      = false;
        $this->editingId      = null;
        $this->showTypePicker = true;
        $this->showModal      = true;
    }

    public function selectType(string $type): void
    {
        abort_unless(WidgetRegistry::has($type), 422, 'Tipo de widget inválido.');
        $this->type           = $type;
        $this->showTypePicker = false;
    }

    public function backToTypePicker(): void
    {
        $this->showTypePicker = true;
        $this->type           = '';
        $this->resetErrorBag();
    }

    public function openEdit(int $id): void
    {
        $widget = SidebarModel::findOrFail($id);

        $this->resetForm();
        $this->isEditing      = true;
        $this->editingId      = $id;
        $this->showTypePicker = false;

        $this->fill([
            'type'                   => $widget->type,
            'title'                  => $widget->title ?? '',
            'status'                 => $widget->status,
            'limit'                  => $widget->limit ?? 5,
            'period_type'            => $widget->period_type ?? 'week',
            'content'                => $widget->content ?? '',
            'link'                   => $widget->link ?? '',
            'category_display_type'  => $widget->category_display_type ?? 'most_posts',
            'category_limit'         => $widget->category_limit ?? 8,
            'selected_categories'    => $widget->selected_categories ?? [],
            'tag_display_type'       => $widget->tag_display_type ?? 'most_posts',
            'tag_limit'              => $widget->tag_limit ?? 12,
            'selected_tags'          => $widget->selected_tags ?? [],
            'social_data'            => $widget->social_data ?? [],
            'existingImage'          => $widget->image ?? '',
            'display_mode'           => ($widget->slide_images && count($widget->slide_images) > 0) ? 'slide' : 'single',
            'image_width'            => $widget->image_width ?? 300,
            'image_height'           => $widget->image_height ?? 150,
            'slide_interval'         => $widget->slide_interval ?? 5000,
            'slide_autoplay'         => $widget->slide_autoplay ?? true,
            'slide_controls'         => $widget->slide_controls ?? true,
            'slide_indicators'       => $widget->slide_indicators ?? true,
        ]);

        $this->slide_items = collect($widget->slide_images ?? [])->map(fn($s) => [
            'existing' => $s['image'] ?? '',
            'file'     => null,
            'link'     => $s['link'] ?? '',
        ])->all();

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    // =========================================================================
    // CRUD
    // =========================================================================

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'  => $this->title ?: WidgetRegistry::get($this->type)::label(),
            'status' => $this->status,
        ];

        $data = array_merge($data, $this->resolveWidgetData());

        if ($this->isEditing) {
            SidebarModel::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', type: 'success', message: 'Widget atualizado com sucesso!');
        } else {
            $data['type']  = $this->type;
            $data['fixed'] = false;
            $data['order'] = SidebarModel::max('order') + 1;
            SidebarModel::create($data);
            $this->dispatch('notify', type: 'success', message: 'Widget criado com sucesso!');
        }

        $this->closeModal();
    }

    public function toggleStatus(int $id): void
    {
        $widget = app(SidebarService::class)->toggleStatus($id);
        $label  = $widget->status ? 'ativado' : 'desativado';
        $this->dispatch('notify', type: 'info', message: "Widget {$label}.");
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmDeleteId = null;
    }

    public function delete(int $id): void
    {
        $widget = SidebarModel::findOrFail($id);

        if ($widget->fixed) {
            $this->dispatch('notify', type: 'error', message: 'Widgets fixos não podem ser removidos.');
            $this->confirmDeleteId = null;
            return;
        }

        $this->cleanWidgetFiles($widget);

        $order = $widget->order;
        $widget->delete();
        SidebarModel::where('order', '>', $order)->decrement('order');

        $this->confirmDeleteId = null;
        $this->dispatch('notify', type: 'success', message: 'Widget removido com sucesso!');
    }

    public function updateOrder(array $items): void
    {
        app(SidebarService::class)->reorder($items);
        $this->dispatch('notify', type: 'success', message: 'Ordem atualizada!');
    }

    // ─── Social Links helpers ─────────────────────────────────────────────────

    public function addSocialLink(): void
    {
        $this->social_data[] = ['name' => '', 'icon' => '', 'color' => '#6366f1', 'link' => ''];
    }

    public function removeSocialLink(int $index): void
    {
        array_splice($this->social_data, $index, 1);
        $this->social_data = array_values($this->social_data);
    }

    // ─── Slide helpers ────────────────────────────────────────────────────────

    public function addSlideItem(): void
    {
        if (count($this->slide_items) < 5) {
            $this->slide_items[] = ['existing' => '', 'file' => null, 'link' => ''];
        }
    }

    public function removeSlideItem(int $index): void
    {
        $this->deleteIfExists($this->slide_items[$index]['existing'] ?? '');
        array_splice($this->slide_items, $index, 1);
        $this->slide_items = array_values($this->slide_items);
    }

    // =========================================================================
    // INTERNOS
    // =========================================================================

    /**
     * Monta o array de dados específicos do widget para salvar no BD.
     */
    private function resolveWidgetData(): array
    {
        return match ($this->type) {
            'categories' => [
                'category_display_type' => $this->category_display_type,
                'category_limit'        => $this->category_limit,
                'selected_categories'   => $this->category_display_type === 'manual' ? $this->selected_categories : null,
            ],
            'popular_posts' => [
                'limit' => $this->limit,
            ],
            'popular_downloads' => [
                'limit'       => $this->limit,
                'period_type' => $this->period_type,
            ],
            'tags' => [
                'tag_display_type' => $this->tag_display_type,
                'tag_limit'        => $this->tag_limit,
                'selected_tags'    => $this->tag_display_type === 'manual' ? $this->selected_tags : null,
            ],
            'social_links' => [
                'social_data' => $this->social_data,
            ],
            'image_link' => app(ImageLinkProcessor::class)->process([
                'display_mode'    => $this->display_mode,
                'imageFile'       => $this->imageFile,
                'existingImage'   => $this->existingImage,
                'link'            => $this->link,
                'image_width'     => $this->image_width,
                'image_height'    => $this->image_height,
                'slide_items'     => $this->slide_items,
                'slide_interval'  => $this->slide_interval,
                'slide_autoplay'  => $this->slide_autoplay,
                'slide_controls'  => $this->slide_controls,
                'slide_indicators'=> $this->slide_indicators,
            ]),
            'custom' => [
                'content' => $this->content,
                'link'    => $this->link ?: null,
            ],
            default => [],
        };
    }

    private function cleanWidgetFiles(SidebarModel $widget): void
    {
        $this->deleteIfExists($widget->image);

        foreach ($widget->slide_images ?? [] as $slide) {
            $this->deleteIfExists($slide['image'] ?? null);
        }
    }

    private function deleteIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function resetForm(): void
    {
        $this->fill([
            'type'                   => '',
            'title'                  => '',
            'status'                 => true,
            'limit'                  => 5,
            'period_type'            => 'week',
            'content'                => '',
            'link'                   => '',
            'category_display_type'  => 'most_posts',
            'category_limit'         => 8,
            'selected_categories'    => [],
            'tag_display_type'       => 'most_posts',
            'tag_limit'              => 12,
            'selected_tags'          => [],
            'social_data'            => [],
            'display_mode'           => 'single',
            'imageFile'              => null,
            'existingImage'          => '',
            'image_width'            => 300,
            'image_height'           => 150,
            'slide_items'            => [],
            'slide_interval'         => 5000,
            'slide_autoplay'         => true,
            'slide_controls'         => true,
            'slide_indicators'       => true,
            'showTypePicker'         => true,
        ]);
        $this->resetErrorBag();
    }

    // =========================================================================
    // RENDER
    // =========================================================================

    public function render()
    {
        $widgets     = SidebarModel::orderBy('order')->get();
        $widgetTypes = collect(WidgetRegistry::all())
                        ->keyBy('type')        // ← adicionar isso
                        ->toArray();

        $categoriesList = collect();
        $tagsList       = collect();

        if ($this->type === 'categories') {
            $categoriesList = Category::where('status', true)
                ->withCount(['posts' => fn($q) => $q->where('status', true)])
                ->orderBy('title')
                ->get();
        }

        if ($this->type === 'tags') {
            $tagsList = Tag::has('posts')
                ->withCount(['posts' => fn($q) => $q->where('status', true)])
                ->orderBy('name')
                ->get();
        }

        return view('livewire.admin.sidebar', compact('widgets', 'widgetTypes', 'categoriesList', 'tagsList'));
    }
}