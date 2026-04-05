<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use App\Models\ParentCategory;
use Illuminate\Support\Facades\Auth;

class Categories extends Component
{
    // ─── Busca e Filtros ──────────────────────────────────────────────────────
    public string $searchParent   = '';
    public string $searchCategory = '';
    public string $filterStatus   = '';
    public string $filterParent   = '';

    // ─── Estado dos modais ────────────────────────────────────────────────────
    public bool $showParentCategoryModal = false;
    public bool $showCategoryModal       = false;

    // ─── Formulário: Parent Category ─────────────────────────────────────────
    public ?int   $parentCategoryId   = null;
    public string $parentCategoryName = '';
    public bool   $isEditingParent    = false;

    // ─── Formulário: Category ────────────────────────────────────────────────
    public ?int   $categoryId          = null;
    public string $categoryName        = '';
    public ?int   $categoryParentId    = null;
    public string $categoryDescription = '';
    public bool   $categoryStatus      = true;
    public bool   $isEditingCategory   = false;

    protected $messages = [
        'parentCategoryName.required' => 'O nome da categoria pai é obrigatório.',
        'parentCategoryName.unique'   => 'Já existe uma categoria pai com este nome.',
        'categoryName.required'       => 'O nome da categoria é obrigatório.',
        'categoryName.unique'         => 'Já existe uma categoria com este nome.',
        'categoryParentId.exists'     => 'A categoria pai selecionada não existe.',
    ];

    // ─── Helpers de permissão ─────────────────────────────────────────────────

    private function canCreate(): bool
    {
        $user = Auth::user();
        return $user->isOwner() || ($user->isAuthor() && $user->autoApprovePosts());
    }

    private function canDelete(): bool
    {
        return Auth::user()->isOwner();
    }

    // ─── Regras dinâmicas ─────────────────────────────────────────────────────

    protected function parentCategoryRules(): array
    {
        $unique = 'unique:parent_categories,name' .
                  ($this->isEditingParent && $this->parentCategoryId ? ',' . $this->parentCategoryId : '');

        return [
            'parentCategoryName' => ['required', 'string', 'max:255', $unique],
        ];
    }

    protected function categoryRules(): array
    {
        $unique = 'unique:categories,name' .
                  ($this->isEditingCategory && $this->categoryId ? ',' . $this->categoryId : '');

        return [
            'categoryName'        => ['required', 'string', 'max:255', $unique],
            'categoryParentId'    => ['required', 'exists:parent_categories,id'],
            'categoryDescription' => ['nullable', 'string'],
            'categoryStatus'      => ['boolean'],
        ];
    }

    // =========================================================================
    // PARENT CATEGORIES — CRUD
    // =========================================================================

    public function openAddParentCategory(): void
    {
        if (! $this->canCreate()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para criar categorias.');
            return;
        }

        $this->resetParentCategoryForm();
        $this->showParentCategoryModal = true;
    }

    public function openEditParentCategory(int $id): void
    {
        if (! $this->canCreate()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para editar categorias.');
            return;
        }

        $item = ParentCategory::findOrFail($id);

        $this->parentCategoryId        = $item->id;
        $this->parentCategoryName      = $item->name;
        $this->isEditingParent         = true;
        $this->showParentCategoryModal = true;
    }

    public function saveParentCategory(): void
    {
        if (! $this->canCreate()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para salvar categorias.');
            return;
        }

        $this->validate($this->parentCategoryRules());

        if ($this->isEditingParent) {
            $item       = ParentCategory::findOrFail($this->parentCategoryId);
            $item->name = $this->parentCategoryName;
            $item->slug = null;
            $item->save();
            $this->dispatch('notify', type: 'success', message: 'Categoria pai atualizada com sucesso!');
        } else {
            ParentCategory::create(['name' => $this->parentCategoryName]);
            $this->dispatch('notify', type: 'success', message: 'Categoria pai criada com sucesso!');
        }

        $this->closeParentCategoryModal();
    }

    public function deleteParentCategory(int $id): void
    {
        if (! $this->canDelete()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para excluir categorias.');
            return;
        }

        $item = ParentCategory::findOrFail($id);
        $item->categories()->update(['parent_category_id' => null]);
        $item->delete();

        $this->dispatch('notify', type: 'success', message: 'Categoria pai removida com sucesso!');
    }

    public function reorderParentCategories(array $ordered): void
    {
        if (! $this->canCreate()) return;

        foreach ($ordered as $entry) {
            ParentCategory::where('id', $entry['id'])->update(['ordering' => $entry['ordering']]);
        }
    }

    public function closeParentCategoryModal(): void
    {
        $this->showParentCategoryModal = false;
        $this->resetParentCategoryForm();
    }

    private function resetParentCategoryForm(): void
    {
        $this->parentCategoryId   = null;
        $this->parentCategoryName = '';
        $this->isEditingParent    = false;
        $this->resetErrorBag();
    }

    // =========================================================================
    // CATEGORIES — CRUD
    // =========================================================================

    public function openAddCategory(): void
    {
        if (! $this->canCreate()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para criar categorias.');
            return;
        }

        $this->resetCategoryForm();
        $this->showCategoryModal = true;
    }

    public function openEditCategory(int $id): void
    {
        if (! $this->canCreate()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para editar categorias.');
            return;
        }

        $cat = Category::findOrFail($id);

        $this->categoryId          = $cat->id;
        $this->categoryName        = $cat->name;
        $this->categoryParentId    = $cat->parent_category_id;
        $this->categoryDescription = $cat->description ?? '';
        $this->categoryStatus      = $cat->status;
        $this->isEditingCategory   = true;
        $this->showCategoryModal   = true;
    }

    public function saveCategory(): void
    {
        if (! $this->canCreate()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para salvar categorias.');
            return;
        }

        $this->validate($this->categoryRules());

        $data = [
            'name'               => $this->categoryName,
            'parent_category_id' => $this->categoryParentId ?: null,
            'description'        => $this->categoryDescription ?: null,
            'status'             => $this->categoryStatus,
        ];

        if ($this->isEditingCategory) {
            $cat       = Category::findOrFail($this->categoryId);
            $cat->slug = null;
            $cat->update($data);
            $this->dispatch('notify', type: 'success', message: 'Categoria atualizada com sucesso!');
        } else {
            Category::create($data);
            $this->dispatch('notify', type: 'success', message: 'Categoria criada com sucesso!');
        }

        $this->closeCategoryModal();
    }

    public function deleteCategory(int $id): void
    {
        if (! $this->canDelete()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para excluir categorias.');
            return;
        }

        Category::findOrFail($id)->delete();
        $this->dispatch('notify', type: 'success', message: 'Categoria removida com sucesso!');
    }

    public function toggleCategoryStatus(int $id): void
    {
        if (! $this->canCreate()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para alterar o status.');
            return;
        }

        $cat = Category::findOrFail($id);
        $cat->update(['status' => ! $cat->status]);

        $label = $cat->status ? 'ativada' : 'desativada';
        $this->dispatch('notify', type: 'info', message: "Categoria {$label}.");
    }

    public function reorderCategories(array $ordered): void
    {
        if (! $this->canCreate()) return;

        foreach ($ordered as $entry) {
            Category::where('id', $entry['id'])->update(['ordering' => $entry['ordering']]);
        }
    }

    public function closeCategoryModal(): void
    {
        $this->showCategoryModal = false;
        $this->resetCategoryForm();
    }

    private function resetCategoryForm(): void
    {
        $this->categoryId          = null;
        $this->categoryName        = '';
        $this->categoryParentId    = null;
        $this->categoryDescription = '';
        $this->categoryStatus      = true;
        $this->isEditingCategory   = false;
        $this->resetErrorBag();
    }

    // =========================================================================
    // RENDER
    // =========================================================================

    public function render()
    {
        $user = Auth::user();

        $catQuery = Category::with('parentCategory')->withCount('posts')->orderBy('ordering');

        if ($this->searchCategory) {
            $catQuery->where('name', 'like', '%' . $this->searchCategory . '%');
        }

        if ($this->filterStatus !== '') {
            $catQuery->where('status', $this->filterStatus === 'active');
        }

        if ($this->filterParent !== '') {
            $catQuery->where('parent_category_id', $this->filterParent);
        }

        $parentQuery = ParentCategory::withCount('categories')->orderBy('ordering');

        if ($this->searchParent) {
            $parentQuery->where('name', 'like', '%' . $this->searchParent . '%');
        }

        return view('livewire.admin.categories', [
            'parentCategories'    => $parentQuery->get(),
            'categories'          => $catQuery->get(),
            'allCategories'       => Category::count(),
            'allParentCategories' => ParentCategory::orderBy('name')->get(),
            'canCreate'           => $this->canCreate(),
            'canDelete'           => $this->canDelete(),
            'isOwner'             => $user->isOwner(),
        ]);
    }
}