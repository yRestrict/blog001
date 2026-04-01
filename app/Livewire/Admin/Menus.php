<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class Menus extends Component
{
    // ─── Tipo do menu ───────────────────────────────────────────────────────────
    public string $type;

    // ─── Busca e Filtros ────────────────────────────────────────────────────────
    public string $search       = '';
    public string $filterStatus = '';

    // ─── Controle do formulário ──────────────────────────────────────────────────
    public bool   $showForm          = false;
    public ?int   $editingId         = null;
    public bool   $editingHasChildren = false;  // URL fica inativa quando true

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
            // Se o item sendo editado já tem filhos, a URL é irrelevante (será '#')
            'url'       => $this->editingHasChildren ? 'nullable|string|max:255' : 'required|string|max:255',
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
        $query = Menu::where('type', $this->type)
            ->whereNull('parent_id')
            ->with(['children.children.children'])
            ->orderBy('order');

        if ($this->search) {
            // Busca no item ou nos filhos
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhereHas('children', fn($c) => $c->where('title', 'like', '%' . $search . '%'));
            });
        }

        if ($this->filterStatus !== '') {
            $active = $this->filterStatus === 'active';
            $query->where('is_active', $active);
        }

        $items = $query->get();

        $totalItems = Menu::where('type', $this->type)->count();

        // Lista plana para o select de "item pai" no formulário
        $allItems = Menu::where('type', $this->type)
            ->orderBy('order')
            ->get();

        return view('livewire.admin.menus', compact('items', 'allItems', 'totalItems'));
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

        $this->editingId          = $item->id;
        $this->editingHasChildren = $item->hasChildren();
        $this->title              = $item->title;
        $this->url                = $item->url;
        $this->target             = $item->target;
        $this->parent_id          = $item->parent_id;
        $this->is_active          = (bool) $item->is_active;
        $this->showForm           = true;
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

        // ✅ Verifica limite de raiz
        if (is_null($this->parent_id)) {
            $totalRaiz = Menu::where('type', $this->type)
                ->whereNull('parent_id')
                ->when($this->editingId, fn($q) => $q->where('id', '!=', $this->editingId))
                ->count();

            if ($totalRaiz >= 7) {
                $this->dispatch('notify', type: 'error', message: 'Limite de 7 itens no menu raiz atingido.');
                return;
            }
        }

        // ✅ Verifica limite de filhos antes de salvar
        if ($this->parent_id) {

            if (Menu::limiteFilhoAtingido($this->parent_id, $this->editingId)) {
                $this->dispatch('notify', 
                    type: 'error',
                    message: 'Limite de filhos atingido para este menu.'
                );
                return;
            }
        }

        
        if (! $this->editingId && is_null($this->parent_id)) {
            $totalRaiz = Menu::where('type', $this->type)
                ->whereNull('parent_id')
                ->count();

            if ($totalRaiz >= 7) {
                $this->dispatch('notify', 
                    type: 'error',
                    message: 'Limite de 7 itens no menu raiz atingido. Não é possível criar mais itens raiz.'
                );
                $this->showForm = false;
                return;
            }
        }

        

        $maxOrder = Menu::where('type', $this->type)
            ->where('parent_id', $this->parent_id)
            ->max('order') ?? -1;

        if ($this->editingId) {
            $item = Menu::findOrFail($this->editingId);

            // Se o item tem filhos, a URL é descartada e mantida como '#'
            $urlToSave = $item->hasChildren() ? '#' : $this->url;

            $item->update([
                'title'     => $this->title,
                'url'       => $urlToSave,
                'target'    => $this->target,
                'parent_id' => $this->parent_id,
                'is_active' => $this->is_active,
            ]);
            $this->dispatch('notify', 
                type: 'success',
                message: 'Item atualizado com sucesso!'
            );
        } else {
            $newItem = Menu::create([
                'type'      => $this->type,
                'title'     => $this->title,
                'url'       => $this->url,
                'target'    => $this->target,
                'parent_id' => $this->parent_id,
                'is_active' => $this->is_active,
                'order'     => $maxOrder + 1,
            ]);

            // Quando um item ganha o primeiro filho, sua URL passa a ser '#'
            if ($this->parent_id) {
                $parent = Menu::find($this->parent_id);
                if ($parent && $parent->url !== '#') {
                    $parent->update(['url' => '#']);
                }
            }
            $this->dispatch('notify', 
                type: 'success',
                message: 'Item criado com sucesso!'
            );
        }

        $this->cancelForm();
    }

    // ─── Excluir ─────────────────────────────────────────────────────────────────
    public function delete(int $id): void
    {
        $item = Menu::findOrFail($id);

        // Impede exclusão se tiver filhos (está com restrictOnDelete na migration)
        if ($item->children()->exists()) {
            $this->dispatch('notify', 
                type: 'error',
                message: 'Não é possível excluir um item que possui subitens. Exclua os subitens primeiro.'
            );
            return;
        }

        $item->delete();
        $this->dispatch('notify', 
            type: 'success',
            message: 'Item excluído com sucesso.'
        );
    }

    // ─── Toggle ativo/inativo ────────────────────────────────────────────────────
    public function toggleActive(int $id): void
    {
        $item = Menu::findOrFail($id);

        $item->update([
            'is_active' => ! $item->is_active,
        ]);

        $this->dispatch('notify',
            type: 'success',
            message: $item->is_active
                ? 'Item ativado com sucesso.'
                : 'Item e subitens desativados com sucesso.'
        );
    }

    // ─── Reordenação via SortableJS ──────────────────────────────────────────────
    #[\Livewire\Attributes\On('items-reordered')]
    public function updateOrder(array $orderedItems): void
    {
        foreach ($orderedItems as $orderedItem) {

            $menu = Menu::find($orderedItem['id']);

            // Está tentando virar raiz
            if (is_null($orderedItem['parent_id']) && ! is_null($menu->parent_id)) {

                $totalRaiz = Menu::where('type', $menu->type)
                    ->whereNull('parent_id')
                    ->count();

                if ($totalRaiz >= 7) {

                    // 🔥 dispara toast
                    $this->dispatch('notify', 
                        type: 'error',
                        message: 'Limite máximo de 7 menus raiz atingido.'
                    );

                    // 🔥 força Livewire re-renderizar para restaurar estrutura original
                    $this->dispatch('$refresh');

                    return;
                }
            }
        }

        // Se passou validação → salva
        foreach ($orderedItems as $orderedItem) {
            Menu::where('id', $orderedItem['id'])->update([
                'parent_id' => $orderedItem['parent_id'],
                'order'     => $orderedItem['order'],
            ]);
        }

        // Garante que qualquer item que agora tem filhos tenha url = '#'
        // e que itens que ficaram sem filhos possam recuperar sua URL (mantemos '#' por segurança,
        // o admin pode editar manualmente para restaurar a URL real)
        $affectedParentIds = collect($orderedItems)
            ->pluck('parent_id')
            ->filter()
            ->unique();

        foreach ($affectedParentIds as $pid) {
            $parent = Menu::find($pid);
            if ($parent && $parent->url !== '#') {
                $parent->update(['url' => '#']);
            }
        }

        $this->dispatch('notify', 
            type: 'success',
            message: 'Ordem atualizada com sucesso.'
        );
    }

    // ─── Helper privado ──────────────────────────────────────────────────────────
    private function resetForm(): void
    {
        $this->editingId          = null;
        $this->editingHasChildren = false;
        $this->title     = '';
        $this->url       = '#';
        $this->target    = '_self';
        $this->parent_id = null;
        $this->is_active = true;
        $this->resetValidation();
    }
}