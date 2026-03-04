{{--
    VIEW: livewire/dashboard/sidebar/widget-manager.blade.php

    View do componente WidgetManager.

    O grande diferencial desta view é o FORMULÁRIO DINÂMICO:
    quando o admin seleciona um tipo de widget, os campos corretos
    aparecem automaticamente via @if($type === 'categories') etc.
    Sem JavaScript, sem Alpine para isso — o Livewire re-renderiza
    somente a parte necessária quando o tipo muda.

    Para drag & drop, usamos o pacote livewire-sortable:
      npm install --save @livewire-ui/sortable
      Depois adicione @script ou importe no app.js conforme a doc.
--}}

<div>

    {{-- ================================================================
         CABEÇALHO
         ================================================================ --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.sidebars') }}">Sidebars</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $sidebar->name }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">Widgets — {{ $sidebar->name }}</h1>
            <code class="small text-muted">{{ $sidebar->slug }}</code>
        </div>

        <button wire:click="openCreate" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Adicionar Widget
        </button>
    </div>

    {{-- Notificações --}}
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
         LISTA DE WIDGETS COM DRAG & DROP
         wire:sortable="reorder" → quando o usuário solta o drag,
         chama reorder() no PHP com os IDs na nova ordem.
         ================================================================ --}}
    @if($this->widgets->isEmpty())
        <div class="text-center py-5 text-muted border rounded">
            <i class="bi bi-puzzle fs-1 d-block mb-2"></i>
            <p>Nenhum widget ainda. Clique em "Adicionar Widget" para começar.</p>
        </div>
    @else
        {{--
            wire:sortable="reorder"
            Ativa o drag & drop. Ao soltar, chama reorder() com os novos índices.
            Requer o pacote livewire-sortable instalado.
        --}}
        <div wire:sortable="reorder" class="d-flex flex-column gap-2">

            @foreach($this->widgets as $widget)
                {{--
                    wire:sortable.item="{{ $widget->id }}"
                    Identifica cada item para o sortable.
                    wire:sortable.handle → o ícone de drag é a alça de arrasto.
                --}}
                <div
                    wire:sortable.item="{{ $widget->id }}"
                    wire:key="widget-{{ $widget->id }}"
                    class="card {{ $widget->active ? '' : 'opacity-50' }}"
                >
                    <div class="card-body d-flex align-items-center gap-3 py-2">

                        {{-- Alça de drag & drop --}}
                        <div wire:sortable.handle class="text-muted" style="cursor: grab">
                            <i class="bi bi-grip-vertical fs-5"></i>
                        </div>

                        {{-- Ícone do tipo --}}
                        <div class="text-primary">
                            <i class="bi {{ $this->availableTypes[$widget->type]['icon'] ?? 'bi-puzzle' }} fs-5"></i>
                        </div>

                        {{-- Info do widget --}}
                        <div class="flex-grow-1">
                            <div class="fw-semibold">
                                {{ $widget->title ?: ($this->availableTypes[$widget->type]['label'] ?? $widget->type) }}
                            </div>
                            <small class="text-muted">
                                {{ $this->availableTypes[$widget->type]['label'] ?? $widget->type }}
                                · Posição {{ $widget->position }}
                            </small>
                        </div>

                        {{-- Ações --}}
                        <div class="d-flex gap-2 align-items-center">

                            {{-- Toggle status --}}
                            <button
                                wire:click="toggle({{ $widget->id }})"
                                class="badge border-0 {{ $widget->active ? 'bg-success' : 'bg-secondary' }}"
                                title="{{ $widget->active ? 'Desativar' : 'Ativar' }}"
                            >
                                {{ $widget->active ? 'Ativo' : 'Inativo' }}
                            </button>

                            {{-- Editar --}}
                            <button
                                wire:click="openEdit({{ $widget->id }})"
                                class="btn btn-sm btn-outline-secondary"
                            >
                                <i class="bi bi-pencil"></i>
                            </button>

                            {{-- Deletar --}}
                            <button
                                wire:click="delete({{ $widget->id }})"
                                wire:confirm="Remover este widget?"
                                class="btn btn-sm btn-outline-danger"
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
         MODAL — PASSO 1: SELEÇÃO DO TIPO
         Exibido apenas quando showTypeStep = true
         ================================================================ --}}
    @if($showModal && $showTypeStep)
        <div class="modal d-block" style="background: rgba(0,0,0,0.5)" wire:click.self="closeModal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Escolha o Tipo de Widget</h5>
                        <button wire:click="closeModal" class="btn-close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">

                            {{--
                                Itera sobre availableTypes() do Model.
                                Para cada tipo, um card clicável.
                                wire:click="selectType('categories')"
                                → chama selectType() no PHP
                                → $type = 'categories', $showTypeStep = false
                                → o modal troca automaticamente para o passo 2
                            --}}
                            @foreach($this->availableTypes as $typeKey => $typeDef)
                                <div class="col-md-4 col-6">
                                    <button
                                        wire:click="selectType('{{ $typeKey }}')"
                                        class="card w-100 text-start border-0 shadow-sm p-3 h-100"
                                        style="cursor: pointer; transition: all 0.15s"
                                        onmouseover="this.style.transform='translateY(-2px)'"
                                        onmouseout="this.style.transform='translateY(0)'"
                                    >
                                        <i class="bi {{ $typeDef['icon'] }} fs-3 text-primary mb-2"></i>
                                        <span class="fw-semibold">{{ $typeDef['label'] }}</span>
                                    </button>
                                </div>
                            @endforeach

                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif


    {{-- ================================================================
         MODAL — PASSO 2: FORMULÁRIO DINÂMICO POR TIPO
         Exibido quando showModal = true e showTypeStep = false.

         A chave aqui: @if($type === 'categories') etc.
         Quando o admin escolhe o tipo, o Livewire re-renderiza
         o modal exibindo apenas os campos corretos.
         Sem JavaScript para show/hide — é PHP puro no servidor.
         ================================================================ --}}
    @if($showModal && !$showTypeStep)
        <div class="modal d-block" style="background: rgba(0,0,0,0.5)" wire:click.self="closeModal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title">
                                {{ $editingId ? 'Editar Widget' : 'Novo Widget' }}
                                <span class="badge bg-primary ms-2">
                                    {{ $this->availableTypes[$type]['label'] ?? $type }}
                                </span>
                            </h5>
                        </div>
                        <button wire:click="closeModal" class="btn-close"></button>
                    </div>

                    <form wire:submit="save">
                        <div class="modal-body">

                            {{-- ---- CAMPO TÍTULO (todos os tipos) ---- --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Título do Widget</label>
                                <input
                                    wire:model="title"
                                    type="text"
                                    class="form-control"
                                    placeholder="Ex: Posts Populares (deixe vazio para não exibir)"
                                >
                            </div>

                            <hr>

                            {{-- ============================================================
                                 CAMPOS ESPECÍFICOS POR TIPO
                                 Cada bloco @if só renderiza para o tipo correspondente.
                                 ============================================================ --}}

                            {{-- ---- CATEGORIES & TAGS ---- --}}
                            @if(in_array($type, ['categories', 'tags']))

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Tipo de Exibição</label>
                                    {{--
                                        wire:model="displayType"
                                        Quando o usuário troca o select, $displayType muda no PHP.
                                        O bloco @if abaixo reage instantaneamente, sem JS.
                                    --}}
                                    <select wire:model="displayType" class="form-select">
                                        <option value="most_posts">Mais Posts</option>
                                        <option value="most_visited">Mais Visitadas</option>
                                        <option value="manual">Seleção Manual</option>
                                    </select>
                                </div>

                                @if($displayType !== 'manual')
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Quantidade de {{ $type === 'tags' ? 'Tags' : 'Categorias' }}
                                        </label>
                                        <input wire:model="limit" type="number" class="form-control"
                                               min="1" max="{{ $type === 'tags' ? 30 : 20 }}">
                                        @error('limit')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                {{--
                                    Este bloco aparece APENAS quando displayType === 'manual'.
                                    O Livewire detecta a mudança no select e re-renderiza.
                                    Mostra a lista de categorias/tags para o admin escolher.
                                --}}
                                @if($displayType === 'manual')
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Selecione as {{ $type === 'tags' ? 'Tags' : 'Categorias' }}
                                            <small class="text-muted fw-normal">(máx. {{ $type === 'tags' ? 30 : 20 }})</small>
                                        </label>
                                        <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto">
                                            @foreach($type === 'tags' ? $tagsList : $categoriesList as $item)
                                                <div class="form-check">
                                                    <input
                                                        wire:model="selectedIds"
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        value="{{ $item->id }}"
                                                        id="item-{{ $item->id }}"
                                                    >
                                                    <label class="form-check-label" for="item-{{ $item->id }}">
                                                        {{ $item->title ?? $item->name }}
                                                        <span class="text-muted">({{ $item->posts_count }} posts)</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('selectedIds')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                            @endif
                            {{-- /CATEGORIES & TAGS --}}


                            {{-- ---- POPULAR POSTS ---- --}}
                            @if($type === 'popular_posts')
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Quantidade de Posts</label>
                                    <input wire:model="limit" type="number" class="form-control" min="1" max="10">
                                    @error('limit') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            @endif


                            {{-- ---- POPULAR DOWNLOADS ---- --}}
                            @if($type === 'popular_downloads')
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Quantidade</label>
                                        <input wire:model="limit" type="number" class="form-control" min="1" max="20">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Período</label>
                                        <select wire:model="period" class="form-select">
                                            <option value="week">Esta Semana</option>
                                            <option value="month">Este Mês</option>
                                            <option value="total">Total</option>
                                        </select>
                                    </div>
                                </div>
                            @endif


                            {{-- ---- SOCIAL LINKS ---- --}}
                            @if($type === 'social_links')
                                <div class="mb-2 d-flex justify-content-between align-items-center">
                                    <label class="form-label fw-semibold mb-0">Links de Redes Sociais</label>
                                    <button type="button" wire:click="addSocialLink" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-plus-lg"></i> Adicionar
                                    </button>
                                </div>

                                @foreach($socialLinks as $index => $link)
                                    <div class="card mb-2" wire:key="social-{{ $index }}">
                                        <div class="card-body p-2">
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-3">
                                                    <label class="form-label small">Nome</label>
                                                    {{-- wire:model="socialLinks.{{ $index }}.name" → acessa o item do array --}}
                                                    <input wire:model="socialLinks.{{ $index }}.name" type="text" class="form-control form-control-sm" placeholder="Instagram">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small">Ícone (Bootstrap Icons)</label>
                                                    <input wire:model="socialLinks.{{ $index }}.icon" type="text" class="form-control form-control-sm" placeholder="bi-instagram">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">Cor</label>
                                                    <input wire:model="socialLinks.{{ $index }}.color" type="color" class="form-control form-control-sm form-control-color w-100">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small">URL</label>
                                                    <input wire:model="socialLinks.{{ $index }}.url" type="url" class="form-control form-control-sm" placeholder="https://...">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" wire:click="removeSocialLink({{ $index }})" class="btn btn-sm btn-outline-danger w-100">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if(empty($socialLinks))
                                    <p class="text-muted small">Clique em "Adicionar" para inserir um link.</p>
                                @endif
                                @error('socialLinks') <div class="text-danger small">{{ $message }}</div> @enderror
                            @endif


                            {{-- ---- IMAGE LINK ---- --}}
                            @if($type === 'image_link')
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Modo de Exibição</label>
                                    <select wire:model="displayMode" class="form-select">
                                        <option value="single">Imagem Única</option>
                                        <option value="slide">Slideshow</option>
                                    </select>
                                </div>

                                {{-- Modo imagem única --}}
                                @if($displayMode === 'single')
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Imagem</label>
                                        {{--
                                            wire:model="imageFile"
                                            WithFileUploads do Livewire cuida do upload temporário.
                                            A imagem fica em storage/app/livewire-tmp enquanto o form não é salvo.
                                        --}}
                                        <input wire:model="imageFile" type="file" class="form-control" accept="image/*">
                                        @error('imageFile') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

                                        {{-- Preview da imagem existente --}}
                                        @if($existingImage)
                                            <img src="{{ Storage::url($existingImage) }}" class="mt-2 img-thumbnail" style="max-height: 100px">
                                        @endif

                                        {{-- Preview do novo upload em tempo real --}}
                                        @if($imageFile)
                                            <img src="{{ $imageFile->temporaryUrl() }}" class="mt-2 img-thumbnail" style="max-height: 100px">
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Link de Destino</label>
                                        <input wire:model="imageUrl" type="url" class="form-control" placeholder="https://...">
                                    </div>
                                @endif

                                {{-- Modo slideshow --}}
                                @if($displayMode === 'slide')
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Intervalo entre Slides (ms)</label>
                                        <input wire:model="slideInterval" type="number" class="form-control" min="1000" max="30000" step="500">
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-semibold mb-0">Slides (mín. 2, máx. 5)</label>
                                        <button type="button" wire:click="addSlide" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-plus-lg"></i> Slide
                                        </button>
                                    </div>

                                    @foreach($slides as $index => $slide)
                                        <div class="card mb-2" wire:key="slide-{{ $index }}">
                                            <div class="card-body p-2">
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <label class="form-label small">Imagem</label>
                                                        <input wire:model="slides.{{ $index }}.file" type="file" class="form-control form-control-sm" accept="image/*">
                                                        @if(isset($slide['existing']))
                                                            <img src="{{ Storage::url($slide['existing']) }}" class="mt-1 img-thumbnail" style="max-height: 60px">
                                                        @endif
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="form-label small">Link</label>
                                                        <input wire:model="slides.{{ $index }}.link" type="url" class="form-control form-control-sm" placeholder="https://...">
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <button type="button" wire:click="removeSlide({{ $index }})" class="btn btn-sm btn-outline-danger w-100">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endif


                            {{-- ---- CUSTOM HTML ---- --}}
                            @if($type === 'custom')
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Conteúdo HTML</label>
                                    <textarea
                                        wire:model="content"
                                        class="form-control @error('content') is-invalid @enderror"
                                        rows="8"
                                        placeholder="<p>Seu HTML aqui...</p>"
                                    ></textarea>
                                    @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="form-text text-warning">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        O HTML será renderizado sem escape. Use apenas com conteúdo confiável.
                                    </div>
                                </div>
                            @endif

                            {{-- ---- SEARCH (sem campos extras) ---- --}}
                            @if($type === 'search')
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    O widget de busca não tem configurações adicionais.
                                    Ele exibe automaticamente um campo de pesquisa.
                                </div>
                            @endif

                            {{-- ---- ATIVO (todos os tipos) ---- --}}
                            <hr>
                            <div class="form-check form-switch">
                                <input wire:model="active" class="form-check-input" type="checkbox" id="widgetActive">
                                <label class="form-check-label" for="widgetActive">Widget ativo</label>
                            </div>

                        </div>

                        <div class="modal-footer">
                            {{-- Voltar para seleção de tipo (só no modo criação) --}}
                            @if(!$editingId)
                                <button type="button" wire:click="$set('showTypeStep', true)" class="btn btn-link text-muted me-auto">
                                    <i class="bi bi-arrow-left me-1"></i> Trocar tipo
                                </button>
                            @endif

                            <button type="button" wire:click="closeModal" class="btn btn-secondary">Cancelar</button>

                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="bi bi-check-lg me-1"></i>
                                    {{ $editingId ? 'Salvar' : 'Adicionar Widget' }}
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