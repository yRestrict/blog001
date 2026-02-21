<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class Menus extends Component
{
    // ─── Tipo do menu ───────────────────────────────────────────────────────────
    public string $type;

    // ─── Controle do formulário ──────────────────────────────────────────────────
    public bool   $showForm  = false;
    public ?int   $editingId = null;

    // ─── Campos do formulário ────────────────────────────────────────────────────
    public string  $title     = '';
    public string  $url       = '#';
    public string  $target    = '_self';
    public ?int    $parent_id = null;
    public bool    $is_active = true;

    // ─── Validações ──────────────────────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'title'     => 'required|string|max:100',
            'url'       => 'required|string|max:255',
            'target'    => 'required|in:_self,_blank',
            'parent_id' => [
                'nullable',
                'integer',
                'exists:menus,id',
                // Impede que um item seja pai de si mesmo
                function ($attribute, $value, $fail) {
                    if ($this->editingId && (int) $value === $this->editingId) {
                        $fail('Um item não pode ser pai de si mesmo.');
                    }
                },
            ],
            'is_active' => 'boolean',
        ];
    }

    protected array $messages = [
        'title.required' => 'O título é obrigatório.',
        'title.max'      => 'O título pode ter no máximo 100 caracteres.',
        'url.required'   => 'A URL é obrigatória.',
        'url.max'        => 'A URL pode ter no máximo 255 caracteres.',
        'target.in'      => 'Selecione uma opção de abertura válida.',
        'parent_id.exists' => 'O item pai selecionado não existe.',
    ];

    // ─── Mount ───────────────────────────────────────────────────────────────────
    public function mount(string $type): void
    {
        if (! in_array($type, ['header', 'footer'])) {
            abort(404);
        }
        $this->type = $type;
    }

    // ─── Render ──────────────────────────────────────────────────────────────────
    public function render(): View
    {
        // Árvore de itens (raiz com filhos carregados recursivamente)
        $items = Menu::where('type', $this->type)
            ->whereNull('parent_id')
            ->with(['children.children.children'])
            ->orderBy('order')
            ->get();

        // Lista plana para o select de "item pai" no formulário
        $allItems = Menu::where('type', $this->type)
            ->orderBy('order')
            ->get();

        return view('livewire.admin.menus', compact('items', 'allItems'));
    }

    // ─── Formulário: Abrir para adicionar ────────────────────────────────────────
    public function openAddForm(?int $parentId = null): void
    {
        $this->resetForm();
        $this->parent_id = $parentId;
        $this->showForm  = true;
    }

    // ─── Formulário: Abrir para editar ───────────────────────────────────────────
    public function edit(int $id): void
    {
        $item = Menu::findOrFail($id);

        $this->editingId = $item->id;
        $this->title     = $item->title;
        $this->url       = $item->url;
        $this->target    = $item->target;
        $this->parent_id = $item->parent_id;
        $this->is_active = (bool) $item->is_active;
        $this->showForm  = true;
    }

    // ─── Formulário: Cancelar ────────────────────────────────────────────────────
    public function cancelForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    // ─── Salvar (criar ou atualizar) ─────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        $maxOrder = Menu::where('type', $this->type)
            ->where('parent_id', $this->parent_id)
            ->max('order') ?? -1;

        if ($this->editingId) {
            $item = Menu::findOrFail($this->editingId);
            $item->update([
                'title'     => $this->title,
                'url'       => $this->url,
                'target'    => $this->target,
                'parent_id' => $this->parent_id,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Item atualizado com sucesso!');
        } else {
            Menu::create([
                'type'      => $this->type,
                'title'     => $this->title,
                'url'       => $this->url,
                'target'    => $this->target,
                'parent_id' => $this->parent_id,
                'is_active' => $this->is_active,
                'order'     => $maxOrder + 1,
            ]);
            session()->flash('success', 'Item adicionado com sucesso!');
        }

        $this->cancelForm();
    }

    // ─── Excluir ─────────────────────────────────────────────────────────────────
    public function delete(int $id): void
    {
        $item = Menu::findOrFail($id);

        // Impede exclusão se tiver filhos (está com restrictOnDelete na migration)
        if ($item->children()->exists()) {
            session()->flash('error', 'Remova os subitens antes de excluir este item.');
            return;
        }

        $item->delete();
        session()->flash('success', 'Item excluído com sucesso!');
    }

    // ─── Toggle ativo/inativo ────────────────────────────────────────────────────
    public function toggleActive(int $id): void
    {
        $item = Menu::findOrFail($id);
        $item->update(['is_active' => ! $item->is_active]);
    }

    // ─── Reordenação via SortableJS ──────────────────────────────────────────────
    #[\Livewire\Attributes\On('items-reordered')]
    public function updateOrder(array $orderedItems): void
    {
        foreach ($orderedItems as $orderedItem) {
            Menu::where('id', $orderedItem['id'])->update([
                'parent_id' => $orderedItem['parent_id'],
                'order'     => $orderedItem['order'],
            ]);
        }
    }

    // ─── Helper privado ──────────────────────────────────────────────────────────
    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title     = '';
        $this->url       = '#';
        $this->target    = '_self';
        $this->parent_id = null;
        $this->is_active = true;
        $this->resetValidation();
    }
}