@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Editar Post')

@section('content')

    <ul class="mir-breadcrumb">
        <li class="mir-breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house" style="font-size:.65rem"></i></a></li>
        <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
        <li class="mir-breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
        <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
        <li class="mir-breadcrumb-item active">Editar</li>
    </ul>

    <!-- Page Header Action -->
    <div class="page-header-action">
        <div class="page-header-left">
            <div class="page-header-title">Editar Post</div>
            <div class="page-header-sub">Atualize os dados do post</div>
        </div>
        <div class="page-header-right">
            <a href="{{ route('admin.posts.index') }}" class="mir-btn-neutral">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
            <button type="submit" form="post-form" class="mir-btn-primary-lg">
                <i class="fa-solid fa-floppy-disk"></i> Salvar Alterações
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="warning-box" style="margin-bottom: 20px;">
            <strong><i class="fa fa-exclamation-triangle"></i> Corrija os erros abaixo:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" autocomplete="off"
          enctype="multipart/form-data" id="post-form">
        @csrf
        @method('PUT')

        <div class="form-layout">

            {{-- ── Coluna principal ─────────────────────────────────────────── --}}
            <div>

                {{-- Conteúdo --}}
                <div class="post-section">
                    <div class="post-section-header">
                        <div class="section-icon-header">
                            <span class="section-icon section-icon-indigo"><i class="fa-solid fa-pen-nib"></i></span>
                            <div>
                                <div class="post-section-title">Conteúdo do Post</div>
                                <div class="post-section-sub">Preencha o título e o corpo do post</div>
                            </div>
                        </div>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label class="mir-label">Título <span class="mir-required">*</span></label>
                            <input type="text"
                                   class="mir-input @error('title') is-invalid @enderror"
                                   name="title" value="{{ old('title', $post->title) }}"
                                   placeholder="Digite o título do post">
                            @error('title')<span class="invalid-feedback" style="display:block;">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="mir-label">Conteúdo <span class="mir-required">*</span></label>
                            <input type="hidden" name="content" id="content-input">
                            <div id="quill-editor">{!! old('content', $post->content) !!}</div>
                            {{-- Preserva espaços de indentação nos code-blocks ANTES do Quill inicializar --}}
                            <script>
                            (function () {
                                document.querySelectorAll('#quill-editor .ql-code-block').forEach(function (block) {
                                    block.innerHTML = block.innerHTML.replace(/^ /gm, '\u00a0');
                                });
                            })();
                            </script>
                            @error('content')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="post-section">
                    <div class="post-section-header">
                        <div class="section-icon-header">
                            <span class="section-icon section-icon-green"><i class="fa-solid fa-magnifying-glass-chart"></i></span>
                            <div>
                                <div class="post-section-title">SEO</div>
                                <div class="post-section-sub">Otimização para mecanismos de busca</div>
                            </div>
                        </div>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label class="mir-label">Meta Keywords <span style="color:#9ca3af; font-weight:400;">(separadas por vírgula)</span></label>
                            <input type="text" class="mir-input" name="meta_keywords"
                                value="{{ old('meta_keywords', $post->meta_keywords) }}"
                                placeholder="palavra-chave1, palavra-chave2">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="mir-label">Meta Description</label>
                            <textarea name="meta_description" class="mir-input" rows="3"
                                placeholder="Descrição para mecanismos de busca...">{{ old('meta_description', $post->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── Coluna lateral (sidebar) ────────────────────────────────── --}}
            <div>

                {{-- Publicação --}}
                <div class="post-section">
                    <div class="post-section-header">
                        <div class="section-icon-header">
                            <span class="section-icon section-icon-indigo"><i class="fa-solid fa-sliders"></i></span>
                            <div>
                                <div class="post-section-title">Publicação</div>
                                <div class="post-section-sub">Configurações do post</div>
                            </div>
                        </div>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label class="mir-label">Categoria <span class="mir-required">*</span></label>
                            <select name="category_id"
                                class="mir-input @error('category_id') is-invalid @enderror">
                                {!! $categorieshtml !!}
                            </select>
                            @error('category_id')<span class="invalid-feedback" style="display:block;">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group" style="position: relative;">
                            <label class="mir-label">Tags <span style="color:#9ca3af; font-weight:400;">(separe por vírgula)</span></label>
                            <input type="text" id="tag-input" name="tags" class="mir-input"
                                placeholder="Ex: LARAVEL, PHP"
                                value="{{ old('tags', $currentTags ?? '') }}"
                                autocomplete="off" style="text-transform: uppercase;">
                            <ul id="tag-suggestions"
                                style="display:none;position:absolute;z-index:1000;background:#fff;
                                       border:1px solid #ced4da;border-top:none;width:100%;
                                       max-height:200px;overflow-y:auto;list-style:none;
                                       margin:0;padding:0;border-radius:0 0 4px 4px;
                                       box-shadow:0 4px 12px rgba(0,0,0,.1);">
                            </ul>
                        </div>

                        <hr class="form-divider">

                        <div class="form-group">
                            <label class="mir-label">Status</label>
                            <div class="status-pills">
                                <label class="status-pill {{ old('status', $post->status) === 'draft' ? 'selected-draft' : '' }}">
                                    <input type="radio" name="status" value="draft"
                                        {{ old('status', $post->status) === 'draft' ? 'checked' : '' }}>
                                    <span class="status-pill-ring"></span> Rascunho
                                </label>
                                <label class="status-pill {{ old('status', $post->status) === 'published' ? 'selected-published' : '' }}">
                                    <input type="radio" name="status" value="published"
                                        {{ old('status', $post->status) === 'published' ? 'checked' : '' }}>
                                    <span class="status-pill-ring"></span> Publicado
                                </label>
                                <label class="status-pill {{ old('status', $post->status) === 'private' ? 'selected-private' : '' }}">
                                    <input type="radio" name="status" value="private"
                                        {{ old('status', $post->status) === 'private' ? 'checked' : '' }}>
                                    <span class="status-pill-ring"></span> Privado
                                </label>
                            </div>
                            @error('status')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <hr class="form-divider">

                        <div class="form-group" style="margin-bottom: 12px;">
                            <div class="mir-switch-wrap">
                                <input type="hidden" name="featured" value="0">
                                <input type="checkbox" name="featured" value="1" id="featured"
                                    class="mir-switch-input" {{ old('featured', $post->featured) ? 'checked' : '' }}>
                                <label for="featured" class="mir-switch-label">
                                    <span class="mir-switch-track"><span class="mir-switch-thumb"></span></span>
                                    <span class="mir-switch-text">Post em destaque</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <div class="mir-switch-wrap">
                                <input type="hidden" name="comment" value="0">
                                <input type="checkbox" name="comment" value="1" id="comment"
                                    class="mir-switch-input" {{ old('comment', $post->comment) ? 'checked' : '' }}>
                                <label for="comment" class="mir-switch-label">
                                    <span class="mir-switch-track"><span class="mir-switch-thumb"></span></span>
                                    <span class="mir-switch-text">Permitir comentários</span>
                                </label>
                            </div>
                        </div>

                        {{-- Timestamps --}}
                        <div class="form-timestamps" style="margin-top: 16px;">
                            <span>
                                <i class="fa fa-calendar" style="width: 14px; color: #c4c9d4;"></i>
                                Criado em: {{ $post->created_at->format('d/m/Y \à\s H:i') }}
                            </span>
                            @if ($post->updated_at->ne($post->created_at))
                                <span>
                                    <i class="fa fa-edit" style="width: 14px; color: #c4c9d4;"></i>
                                    Atualizado em: {{ $post->updated_at->format('d/m/Y \à\s H:i') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Imagem Destacada --}}
                <div class="post-section">
                    <div class="post-section-header">
                        <div class="section-icon-header">
                            <span class="section-icon section-icon-amber"><i class="fa-solid fa-image"></i></span>
                            <div>
                                <div class="post-section-title">Imagem Destacada</div>
                                <div class="post-section-sub">Imagem principal do post</div>
                            </div>
                        </div>
                    </div>
                    <div style="padding: 20px;">
                        @if ($post->thumbnail)
                            <img src="{{ asset('uploads/posts/' . $post->thumbnail) }}"
                                 alt="Imagem atual" class="img-preview" id="current-image">
                        @endif

                        <div id="preview-wrapper" style="display:none; margin-bottom: 12px;">
                            <img src="" id="featured-image-preview" class="img-preview">
                        </div>

                        <label class="upload-area" for="featured-image-input" style="margin-bottom: 0;">
                            <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                            <div class="upload-text">{{ $post->thumbnail ? 'Clique para substituir' : 'Clique para enviar' }}</div>
                            <div class="upload-hint">ou arraste uma imagem aqui</div>
                            <input type="file" name="thumbnail"
                                class="@error('thumbnail') is-invalid @enderror"
                                id="featured-image-input" accept="image/*"
                                style="display: none;">
                        </label>
                        @error('thumbnail')<span class="text-danger small d-block mt-1">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Downloads --}}
                <div class="post-section">
                    <div class="post-section-header">
                        <div class="section-icon-header">
                            <span class="section-icon section-icon-cyan"><i class="fa-solid fa-download"></i></span>
                            <div>
                                <div class="post-section-title">Downloads</div>
                                <div class="post-section-sub">Botões de download do post</div>
                            </div>
                        </div>
                    </div>
                    <div style="padding: 20px;">
                        <livewire:admin.post-downloads :post="$post" />
                    </div>
                </div>

            </div>

        </div>

    </form>

    {{-- Modais do editor --}}
    @include('dashboard.post.inc.quill-modals')

@endsection

@include('dashboard.post.inc.quill-scripts')

@push('scripts')
<script>
    // Status pills toggle
    document.querySelectorAll('.status-pill').forEach(pill => {
        pill.addEventListener('click', function() {
            document.querySelectorAll('.status-pill').forEach(p => {
                p.classList.remove('selected-published', 'selected-draft', 'selected-private');
            });
            const val = this.querySelector('input').value;
            this.classList.add('selected-' + val);
        });
    });

    // Hide current image when new one is selected
    (function() {
        const input = document.getElementById('featured-image-input');
        const current = document.getElementById('current-image');
        if (input && current) {
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    current.style.display = 'none';
                }
            });
        }
    })();
</script>
@endpush
