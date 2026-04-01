<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tag;

class Tags extends Component
{
    use WithPagination;

    public int     $perPage          = 10;
    public bool    $showModal        = false;
    public bool    $isEditing        = false;
    public ?int    $tagId            = null;
    public string  $tagName          = '';
    public string  $search           = '';
    public ?int    $deletingTagId    = null;
    public string  $deletingTagName  = '';

    protected $messages = [
        'tagName.required' => 'O nome da tag é obrigatório.',
        'tagName.unique'   => 'Já existe uma tag com este nome.',
        'tagName.max'      => 'O nome não pode ter mais de 100 caracteres.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    // ─── Converte para maiúsculo enquanto digita ──────────────────────────────
    public function updatedTagName(): void
    {
        $this->tagName = mb_strtoupper($this->tagName, 'UTF-8');
    }

    protected function tagRules(): array
    {
        $unique = 'unique:tags,name' .
                  ($this->isEditing && $this->tagId ? ',' . $this->tagId : '');

        return [
            'tagName' => ['required', 'string', 'max:100', $unique],
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $tag = Tag::findOrFail($id);

        $this->tagId     = $tag->id;
        $this->tagName   = mb_strtoupper($tag->name, 'UTF-8');
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function saveTag(): void
    {
        $this->tagName = mb_strtoupper(trim($this->tagName), 'UTF-8');
        $this->validate($this->tagRules());

        if ($this->isEditing) {
            $tag       = Tag::findOrFail($this->tagId);
            $tag->name = $this->tagName;
            $tag->slug = null;
            $tag->save();

            $this->dispatch('notify', type: 'success', message: 'Tag atualizada com sucesso!');
        } else {
            Tag::create(['name' => $this->tagName]);
            $this->dispatch('notify', type: 'success', message: 'Tag criada com sucesso!');
        }

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function prepareDelete(int $id): void
    {
        $tag = Tag::findOrFail($id);
        $this->deletingTagId   = $tag->id;
        $this->deletingTagName = $tag->name;
    }

    public function cancelDelete(): void
    {
        $this->deletingTagId   = null;
        $this->deletingTagName = '';
    }

    public function deleteTag(): void
    {
        $tag = Tag::findOrFail($this->deletingTagId);
        $tag->posts()->detach();
        $tag->delete();

        $this->deletingTagId   = null;
        $this->deletingTagName = '';
        $this->dispatch('notify', type: 'success', message: 'Tag removida com sucesso!');
    }

    private function resetForm(): void
    {
        $this->tagId     = null;
        $this->tagName   = '';
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        $tags = Tag::withCount('posts')
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', '%' . mb_strtoupper($this->search, 'UTF-8') . '%')
            )
            ->orderBy('name')
            ->paginate($this->perPage);

        $totalTags = Tag::count();

        return view('livewire.admin.tags', [
            'tags'      => $tags,
            'totalTags' => $totalTags,
        ]);
    }
}
