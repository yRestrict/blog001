<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\ParentCategory;
use Livewire\Component;

class CategoriesTrash extends Component
{
    public ?int $confirmingForceDelete = null;
    public ?string $confirmingType = null; // 'category' ou 'parent'

    public function restoreCategory(int $id): void
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $category);

        $category->restore();

        $this->dispatch('notify', type: 'success', message: 'Categoria restaurada com sucesso!');
    }

    public function restoreParentCategory(int $id): void
    {
        $parent = ParentCategory::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $parent);

        // Restaura a categoria pai E todas as categorias filhas deletadas junto
        $parent->restore();
        Category::onlyTrashed()
            ->where('parent_category_id', $parent->id)
            ->restore();

        $this->dispatch('notify', type: 'success', message: 'Categoria pai e subcategorias restauradas!');
    }

    public function confirmForceDelete(int $id, string $type): void
    {
        $this->confirmingForceDelete = $id;
        $this->confirmingType = $type;
    }

    public function cancelForceDelete(): void
    {
        $this->confirmingForceDelete = null;
        $this->confirmingType = null;
    }

    public function forceDeleteCategory(int $id): void
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $category);

        $category->forceDelete();

        $this->confirmingForceDelete = null;
        $this->confirmingType = null;

        $this->dispatch('notify', type: 'success', message: 'Categoria excluida permanentemente!');
    }

    public function forceDeleteParentCategory(int $id): void
    {
        $parent = ParentCategory::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $parent);

        // Exclui permanentemente as categorias filhas tambem
        Category::onlyTrashed()
            ->where('parent_category_id', $parent->id)
            ->forceDelete();

        $parent->forceDelete();

        $this->confirmingForceDelete = null;
        $this->confirmingType = null;

        $this->dispatch('notify', type: 'success', message: 'Categoria pai excluida permanentemente!');
    }

    public function restoreAll(): void
    {
        $this->authorize('restore', new Category());

        ParentCategory::onlyTrashed()->restore();
        Category::onlyTrashed()->restore();

        $this->dispatch('notify', type: 'success', message: 'Todos os itens foram restaurados!');
    }

    public function render()
    {
        return view('livewire.admin.categories-trash', [
            'trashedCategories'       => Category::onlyTrashed()
                ->with('parentCategory')
                ->latest('deleted_at')
                ->get(),
            'trashedParentCategories' => ParentCategory::onlyTrashed()
                ->withCount(['categories' => fn ($q) => $q->onlyTrashed()])
                ->latest('deleted_at')
                ->get(),
        ]);
    }
}