<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tag;

class Tags extends Component
{
    use WithPagination;

    // ─── Estado do modal ──────────────────────────────────────────────────────
    public bool    $showModal  = false;
    public bool    $isEditing  = false;
    public ?int    $tagId      = null;
    public string  $tagName    = '';

    // ─── Pesquisa ─────────────────────────────────────────────────────────────
    public string  $search     = '';

    // ─── Confirmação de exclusão ──────────────────────────────────────────────
    public ?int $confirmingDelete = null;

    protected $messages = [
        'tagName.required' => 'O nome da tag é obrigatório.',
        'tagName.unique'   => 'Já existe uma tag com este nome.',
        'tagName.max'      => 'O nome não pode ter mais de 100 caracteres.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ─── Regras dinâmicas ────────────────────────────────────────────────────
    protected function tagRules(): array
    {
        $unique = 'unique:tags,name' .
                  ($this->isEditing && $this->tagId ? ',' . $this->tagId : '');

        return [
            'tagName' => ['required', 'string', 'max:100', $unique],
        ];
    }

    // ─── Abrir modal criar ────────────────────────────────────────────────────
    public function openAdd(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    // ─── Abrir modal editar ───────────────────────────────────────────────────
    public function openEdit(int $id): void
    {
        $tag = Tag::findOrFail($id);

        $this->tagId     = $tag->id;
        $this->tagName   = $tag->name;
        $this->isEditing = true;
        $this->showModal = true;
    }

    // ─── Salvar (criar ou atualizar) ──────────────────────────────────────────
    public function save(): void
    {
        $this->validate($this->tagRules());

        if ($this->isEditing) {
            $tag       = Tag::findOrFail($this->tagId);
            $tag->name = $this->tagName;
            $tag->slug = null; // força regeneração pelo Spatie
            $tag->save();

            $this->dispatch('notify', type: 'success', message: 'Tag atualizada com sucesso!');
        } else {
            Tag::create(['name' => $this->tagName]);
            $this->dispatch('notify', type: 'success', message: 'Tag criada com sucesso!');
        }

        $this->closeModal();
    }

    // ─── Fechar modal ─────────────────────────────────────────────────────────
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    // ─── Confirmação de exclusão ──────────────────────────────────────────────
    public function confirmDelete(int $id): void
    {
        $this->confirmingDelete = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = null;
    }

    public function deleteTag(int $id): void
    {
        $tag = Tag::findOrFail($id);
        $tag->posts()->detach(); // remove da pivot antes de deletar
        $tag->delete();

        $this->confirmingDelete = null;
        $this->dispatch('notify', type: 'success', message: 'Tag removida com sucesso!');
    }

    // ─── Reset form ───────────────────────────────────────────────────────────
    private function resetForm(): void
    {
        $this->tagId     = null;
        $this->tagName   = '';
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    // ─── Render ───────────────────────────────────────────────────────────────
    public function render()
    {
        $tags = Tag::withCount('posts')
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
            )
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.tags', [
            'tags' => $tags,
        ]);
    }
}