<?php

namespace App\Livewire\Admin;

use App\Models\Sidebar;
use Livewire\Component;
use Livewire\Attributes\Rule;

/**
 * LIVEWIRE: SidebarManager
 *
 * Gerencia a lista de sidebars (áreas do layout) no dashboard.
 * Substitui completamente o SidebarController.
 *
 * Funcionalidades:
 *  - Listar todas as sidebars
 *  - Criar nova sidebar (modal inline, sem redirect)
 *  - Editar sidebar (modal inline)
 *  - Deletar sidebar
 *  - Ativar/desativar com toggle reativo
 *
 * Como registrar a rota (routes/web.php):
 *   Route::get('/dashboard/sidebars', SidebarManager::class)
 *        ->name('dashboard.sidebars');
 *
 * Como usar na view (se preferir embutir em outra página):
 *   <livewire:dashboard.sidebar.sidebar-manager />
 */
class SidebarManager extends Component
{
    // =========================================================================
    // PROPRIEDADES PÚBLICAS
    //
    // Tudo que é público no Livewire é automaticamente sincronizado
    // com a view via wire:model. Qualquer mudança no PHP reflete na tela
    // e qualquer input do usuário atualiza a propriedade no PHP.
    // =========================================================================

    // Controla se o modal de criação/edição está visível
    public bool $showModal = false;

    // Quando editando, guarda o ID da sidebar sendo editada
    // null = modo criação, integer = modo edição
    public ?int $editingId = null;

    // -------------------------------------------------------------------------
    // Campos do formulário
    // #[Rule] define as validações diretamente na propriedade (Livewire 3)
    // Equivale ao $request->validate() do controller
    // -------------------------------------------------------------------------

    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|string|max:255|alpha_dash')]
    public string $slug = '';

    #[Rule('nullable|string|max:500')]
    public string $description = '';

    #[Rule('boolean')]
    public bool $active = true;

    // =========================================================================
    // COMPUTED PROPERTIES
    //
    // Métodos com nome começando em "get" + "Property" são lazy —
    // só executam quando chamados na view, e ficam em cache durante o request.
    // Na view: $this->sidebars ou {{ $sidebars }}
    // =========================================================================

    /**
     * Lista todas as sidebars com contagem de widgets.
     * Recalcula automaticamente quando o estado do componente muda.
     */
    public function getSidebarsProperty()
    {
        return Sidebar::withCount('widgets')->orderBy('name')->get();
    }

    // =========================================================================
    // AÇÕES (métodos chamados pela view via wire:click)
    // =========================================================================

    /**
     * Abre o modal no modo CRIAÇÃO.
     * Limpa o formulário antes de exibir.
     *
     * Na view: wire:click="openCreate"
     */
    public function openCreate(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    /**
     * Abre o modal no modo EDIÇÃO, preenchendo o formulário com os dados atuais.
     *
     * Na view: wire:click="openEdit({{ $sidebar->id }})"
     *
     * @param int $id ID da sidebar a editar
     */
    public function openEdit(int $id): void
    {
        // Busca a sidebar ou lança 404 automaticamente
        $sidebar = Sidebar::findOrFail($id);

        // Preenche as propriedades com os dados existentes
        $this->editingId   = $sidebar->id;
        $this->name        = $sidebar->name;
        $this->slug        = $sidebar->slug;
        $this->description = $sidebar->description ?? '';
        $this->active      = $sidebar->active;

        $this->showModal = true;
    }

    /**
     * Fecha o modal e limpa o formulário.
     *
     * Na view: wire:click="closeModal"
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Salva o formulário — cria ou atualiza dependendo de $editingId.
     *
     * validate() usa as regras definidas nos #[Rule] das propriedades.
     * Se falhar, os erros ficam disponíveis na view com @error('name').
     *
     * Na view: wire:click="save" ou wire:submit no form
     */
    public function save(): void
    {
        // Valida todos os campos com #[Rule]
        $this->validate();

        $data = [
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'active'      => $this->active,
        ];

        if ($this->editingId) {
            // MODO EDIÇÃO: adiciona a regra unique ignorando o registro atual
            $this->validate([
                'slug' => "required|string|max:255|alpha_dash|unique:sidebars,slug,{$this->editingId}",
            ]);

            Sidebar::findOrFail($this->editingId)->update($data);
            $message = 'Sidebar atualizada com sucesso!';
        } else {
            // MODO CRIAÇÃO: slug deve ser único sem exceção
            $this->validate([
                'slug' => 'required|string|max:255|alpha_dash|unique:sidebars,slug',
            ]);

            Sidebar::create($data);
            $message = 'Sidebar criada com sucesso!';
        }

        $this->closeModal();

        // dispatch() emite um evento do browser que pode ser capturado com
        // @this.on() na view ou pelo Alpine.js para mostrar notificações
        $this->dispatch('notify', message: $message, type: 'success');
    }

    /**
     * Deleta uma sidebar.
     * O cascadeOnDelete() da migration remove os widgets junto.
     *
     * Na view: wire:click="delete({{ $sidebar->id }})"
     *          wire:confirm="Tem certeza?" (Livewire 3 — mostra confirm nativo)
     *
     * @param int $id
     */
    public function delete(int $id): void
    {
        Sidebar::findOrFail($id)->delete();

        $this->dispatch('notify', message: 'Sidebar removida!', type: 'success');
    }

    /**
     * Ativa ou desativa uma sidebar sem recarregar a página.
     *
     * Na view: wire:click="toggle({{ $sidebar->id }})"
     *
     * @param int $id
     */
    public function toggle(int $id): void
    {
        $sidebar = Sidebar::findOrFail($id);
        $sidebar->update(['active' => !$sidebar->active]);
    }

    // =========================================================================
    // LIFECYCLE HOOKS
    //
    // Métodos especiais do Livewire chamados automaticamente.
    // updatedNomeDaPropriedade() é chamado toda vez que a propriedade muda.
    // =========================================================================

    /**
     * Gera o slug automaticamente enquanto o usuário digita o nome.
     * Só gera automaticamente no modo criação — não sobrescreve ao editar.
     */
    public function updatedName(string $value): void
    {
        // Só auto-gera o slug em modo criação
        if (!$this->editingId) {
            $this->slug = \Illuminate\Support\Str::slug($value);
        }
    }

    // =========================================================================
    // RENDER
    // =========================================================================

    public function render()
    {
        return view('livewire.admin.sidebar-manager');
            ; // seu layout do dashboard
    }

    // =========================================================================
    // HELPERS PRIVADOS
    // =========================================================================

    /**
     * Reseta todos os campos do formulário para o estado inicial.
     * resetErrorBag() limpa as mensagens de erro de validação.
     */
    private function resetForm(): void
    {
        $this->name        = '';
        $this->slug        = '';
        $this->description = '';
        $this->active      = true;
        $this->editingId   = null;
        $this->resetErrorBag();
    }
}