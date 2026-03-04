{{--
    VIEW: livewire/dashboard/sidebar/sidebar-manager.blade.php

    View do componente Livewire SidebarManager.

    Conceitos Livewire usados aqui:
    - wire:click       → chama um método no PHP ao clicar
    - wire:model       → sincroniza o valor do input com a propriedade PHP
    - wire:submit      → chama um método ao submeter o form
    - wire:confirm     → mostra um confirm nativo do browser antes de executar
    - wire:loading     → mostra/esconde elemento enquanto o Livewire processa
    - @error('campo')  → exibe mensagem de validação do campo

    Nenhum JavaScript foi escrito manualmente para as interações abaixo.
--}}

<div>

    {{-- ================================================================
         CABEÇALHO DA PÁGINA
         ================================================================ --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Gerenciar Sidebars</h1>
            <p class="text-muted small mb-0">Áreas de widgets do seu blog</p>
        </div>

        {{--
            wire:click="openCreate"
            Ao clicar, chama o método openCreate() no PHP.
            O método define showModal = true, e o modal aparece instantaneamente.
            Sem JavaScript, sem redirect, sem reload de página.
        --}}
        <button wire:click="openCreate" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Nova Sidebar
        </button>
    </div>

    {{-- ================================================================
         NOTIFICAÇÕES FLASH
         Escuta o evento 'notify' emitido via $this->dispatch() no PHP.
         Você pode usar Alpine.js ou Toastr para isso.
         ================================================================ --}}
    <div
        x-data="{ show: false, message: '', type: '' }"
        x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="alert"
        :class="type === 'success' ? 'alert-success' : 'alert-danger'"
    >
        <span x-text="message"></span>
    </div>

    {{-- ================================================================
         LISTA DE SIDEBARS
         $this->sidebars vem da computed property getSidebarsProperty().
         Recalcula automaticamente após create/update/delete.
         ================================================================ --}}
    @if($this->sidebars->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-layout-sidebar fs-1 d-block mb-2"></i>
            <p>Nenhuma sidebar criada ainda.</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($this->sidebars as $sidebar)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 {{ $sidebar->active ? '' : 'opacity-50' }}">
                        <div class="card-body">

                            {{-- Nome e badge de status --}}
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $sidebar->name }}</h5>
                                {{--
                                    wire:click="toggle({{ $sidebar->id }})"
                                    Chama toggle() no PHP passando o ID.
                                    O Livewire re-renderiza a lista inteira com o novo status.
                                    Sem reload de página.
                                --}}
                                <button
                                    wire:click="toggle({{ $sidebar->id }})"
                                    class="badge border-0 {{ $sidebar->active ? 'bg-success' : 'bg-secondary' }}"
                                    title="Clique para {{ $sidebar->active ? 'desativar' : 'ativar' }}"
                                >
                                    {{ $sidebar->active ? 'Ativo' : 'Inativo' }}
                                </button>
                            </div>

                            {{-- Slug --}}
                            <code class="small text-muted">{{ $sidebar->slug }}</code>

                            @if($sidebar->description)
                                <p class="text-muted small mt-2 mb-0">{{ $sidebar->description }}</p>
                            @endif

                            {{-- Contagem de widgets --}}
                            <p class="mt-2 mb-0 small">
                                <i class="bi bi-puzzle me-1"></i>
                                {{ $sidebar->widgets_count }} widget(s)
                            </p>
                        </div>

                        <div class="card-footer bg-transparent d-flex gap-2">
                            {{-- Gerenciar Widgets — vai para a rota do WidgetManager --}}
                            <a
                                href="{{ route('admin.sidebars.widgets', $sidebar) }}"
                                class="btn btn-sm btn-outline-primary flex-grow-1"
                            >
                                <i class="bi bi-puzzle me-1"></i> Widgets
                            </a>

                            {{-- Editar sidebar --}}
                            <button
                                wire:click="openEdit({{ $sidebar->id }})"
                                class="btn btn-sm btn-outline-secondary"
                                title="Editar"
                            >
                                <i class="bi bi-pencil"></i>
                            </button>

                            {{--
                                wire:confirm → mostra um confirm nativo ANTES de executar.
                                Recurso nativo do Livewire 3, sem JavaScript extra.
                            --}}
                            <button
                                wire:click="delete({{ $sidebar->id }})"
                                wire:confirm="Tem certeza? Todos os widgets serão removidos."
                                class="btn btn-sm btn-outline-danger"
                                title="Remover"
                            >
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ================================================================
         MODAL DE CRIAÇÃO / EDIÇÃO
         Controlado pela propriedade $showModal no PHP.
         @if($showModal) → renderiza o modal apenas quando necessário.
         ================================================================ --}}
    @if($showModal)
        {{-- Overlay: wire:click="closeModal" fecha ao clicar fora --}}
        <div
            class="modal d-block"
            style="background: rgba(0,0,0,0.5)"
            wire:click.self="closeModal"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $editingId ? 'Editar Sidebar' : 'Nova Sidebar' }}
                        </h5>
                        <button wire:click="closeModal" class="btn-close"></button>
                    </div>

                    {{--
                        wire:submit="save"
                        Ao submeter o form, chama save() no PHP.
                        Se a validação falhar, o modal fica aberto e
                        os erros aparecem via @error().
                        Se passar, o modal fecha e a lista atualiza.
                    --}}
                    <form wire:submit="save">
                        <div class="modal-body">

                            {{-- Nome --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nome <span class="text-danger">*</span></label>
                                {{--
                                    wire:model="name"
                                    Sincroniza o input com $name no PHP em tempo real.
                                    O lifecycle hook updatedName() gera o slug automaticamente.
                                --}}
                                <input
                                    wire:model="name"
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Ex: Sidebar Principal"
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Slug --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Slug <span class="text-danger">*</span>
                                    <small class="text-muted fw-normal">(gerado automaticamente)</small>
                                </label>
                                <input
                                    wire:model="slug"
                                    type="text"
                                    class="form-control @error('slug') is-invalid @enderror"
                                    placeholder="ex: sidebar-principal"
                                >
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Use nas views: <code>&lt;x-frontend.sidebar-area slug="{{ $slug }}" /&gt;</code>
                                </div>
                            </div>

                            {{-- Descrição --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Descrição</label>
                                <input
                                    wire:model="description"
                                    type="text"
                                    class="form-control"
                                    placeholder="Onde esta sidebar aparece..."
                                >
                            </div>

                            {{-- Ativo --}}
                            <div class="form-check form-switch">
                                <input
                                    wire:model="active"
                                    class="form-check-input"
                                    type="checkbox"
                                    id="activeCheck"
                                >
                                <label class="form-check-label" for="activeCheck">
                                    Sidebar ativa
                                </label>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" wire:click="closeModal" class="btn btn-secondary">
                                Cancelar
                            </button>

                            {{--
                                wire:loading.attr="disabled"
                                Desativa o botão enquanto o Livewire está processando.
                                wire:loading.class="opacity-50" adiciona a classe durante o load.
                            --}}
                            <button
                                type="submit"
                                class="btn btn-primary"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove>
                                    <i class="bi bi-check-lg me-1"></i>
                                    {{ $editingId ? 'Salvar' : 'Criar Sidebar' }}
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Salvando...
                                </span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif

</div>