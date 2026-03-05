@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title Here')
@section('content')

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Criar Post</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Criar Post</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-primary">Ver Todos os Posts</a>
        </div>
    </div>
</div>

{{-- Erros de validação --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ATENÇÃO: action aponta para posts.store (POST), NÃO para posts.create --}}
<form action="{{ route('admin.posts.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
    @csrf

    <div class="row">

        {{-- ── Coluna principal ─────────────────────────────────────────── --}}
        <div class="col-md-8">

            {{-- Título e Conteúdo --}}
            <div class="card card-box mb-2">
                <div class="card-body">
                    <div class="form-group">
                        <label><b>Título</b></label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               name="title"
                               value="{{ old('title') }}"
                               placeholder="Digite o título do post">
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label><b>Conteúdo</b></label>
                        <textarea name="content"
                                  class="form-control @error('content') is-invalid @enderror"
                                  rows="12"
                                  placeholder="Digite o conteúdo do post aqui">{{ old('content') }}</textarea>
                        @error('content')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="card card-box mb-2">
                <div class="card-header weight-500">SEO</div>
                <div class="card-body">
                    <div class="form-group">
                        <label><b>Meta Keywords</b> <small>(separadas por vírgula)</small></label>
                        <input type="text"
                               class="form-control"
                               name="meta_keywords"
                               value="{{ old('meta_keywords') }}"
                               placeholder="palavra-chave1, palavra-chave2">
                    </div>
                    <div class="form-group">
                        <label><b>Meta Description</b></label>
                        <textarea name="meta_description"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Descrição para mecanismos de busca">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Coluna lateral ────────────────────────────────────────────── --}}
        <div class="col-md-4">
            <div class="card card-box mb-2">
                <div class="card-body">

                    {{-- Categoria --}}
                    <div class="form-group">
                        <label><b>Categoria</b></label>
                        <select name="category_id"
                                class="custom-select form-control @error('category_id') is-invalid @enderror">
                            {!! $categorieshtml !!}
                        </select>
                        @error('category_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tags --}}
                    <div class="form-group" style="position: relative;">
                        <label><b>Tags</b> <small class="text-muted">(separe por vírgula)</small></label>

                        <input type="text"
                            id="tag-input"
                            name="tags"
                            class="form-control"
                            placeholder="Ex: LARAVEL, PHP, JAVASCRIPT"
                            value="{{ old('tags') }}"
                            autocomplete="off"
                            style="text-transform: uppercase;">

                        <ul id="tag-suggestions"
                            style="display:none; position:absolute; z-index:1000; background:#fff;
                                border:1px solid #ced4da; border-top:none; width:100%;
                                max-height:200px; overflow-y:auto; list-style:none;
                                margin:0; padding:0; border-radius:0 0 4px 4px;
                                box-shadow: 0 4px 12px rgba(0,0,0,.1);">
                        </ul>

                        <small class="form-text text-muted">
                            Digite e selecione sugestões ou crie novas tags.
                        </small>
                    </div>

                    {{-- Imagem destacada --}}
                    <div class="form-group">
                        <label><b>Imagem Destacada</b></label>
                        <input type="file"
                               name="thumbnail"
                               class="form-control-file form-control @error('thumbnail') is-invalid @enderror"
                               id="featured-image-input"
                               accept="image/*">
                        @error('thumbnail')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-block mb-3" style="max-width: 250px; display:none;" id="preview-wrapper">
                        <img src="" alt="Preview" class="img-thumbnail" id="featured-image-preview" style="max-height: 180px;">
                    </div>

                    <hr>

                    {{-- Destaque --}}
                    <div class="form-group">
                        <label><b>Destaque</b></label>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox"
                                   name="featured"
                                   value="1"
                                   class="custom-control-input"
                                   id="checkFeatured"
                                   {{ old('featured') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="checkFeatured">Ativar post em destaque</label>
                        </div>
                    </div>

                    {{-- Comentários --}}
                    <div class="form-group">
                        <label><b>Comentários</b></label>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox"
                                   name="comment"
                                   value="1"
                                   class="custom-control-input"
                                   id="checkComment"
                                   {{ old('comment', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="checkComment">Permitir comentários</label>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-group">
                        <label><b>Status</b></label>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" id="statusDraft" name="status" value="draft"
                                   class="custom-control-input"
                                   {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusDraft">Rascunho</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" id="statusPublished" name="status" value="published"
                                   class="custom-control-input"
                                   {{ old('status') === 'published' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusPublished">Publicado</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" id="statusPrivate" name="status" value="private"
                                   class="custom-control-input"
                                   {{ old('status') === 'private' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusPrivate">Privado</label>
                        </div>
                        @error('status')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="mb-4">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> Criar Post
        </button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary ml-2">Cancelar</a>
    </div>

</form>

@endsection


@push('scripts')
<script>
    // Preview da imagem destacada
    document.getElementById('featured-image-input').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (ev) {
            document.getElementById('featured-image-preview').src = ev.target.result;
            document.getElementById('preview-wrapper').style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    (function () {
        const input       = document.getElementById('tag-input');
        const suggestions = document.getElementById('tag-suggestions');
        const searchUrl   = "{{ route('admin.tags.tags.search') }}"; {{-- ← aspas duplas aqui --}}

        let debounceTimer = null;

        input.addEventListener('input', function () {
            const cursorPos = this.selectionStart;
            this.value = this.value.toUpperCase();
            this.setSelectionRange(cursorPos, cursorPos);
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fetchSuggestions(), 250);
        });

        function fetchSuggestions() {
            const parts    = input.value.split(',');
            const lastPart = parts[parts.length - 1].trim();
            if (lastPart.length < 1) { hideSuggestions(); return; }

            fetch(searchUrl + '?q=' + encodeURIComponent(lastPart))
                .then(r => r.json())
                .then(tags => renderSuggestions(tags, parts))
                .catch(() => hideSuggestions());
        }

        function renderSuggestions(tags, parts) {
            suggestions.innerHTML = '';
            const already  = parts.slice(0, -1).map(t => t.trim().toUpperCase());
            const filtered = tags.filter(t => !already.includes(t.toUpperCase()));
            if (filtered.length === 0) { hideSuggestions(); return; }

            filtered.forEach(tag => {
                const li = document.createElement('li');
                li.textContent = tag;
                li.style.cssText = 'padding:8px 12px; cursor:pointer; font-size:.875rem;';
                li.addEventListener('mouseenter', () => li.style.background = '#f3f4f6');
                li.addEventListener('mouseleave', () => li.style.background = '');
                li.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    selectTag(tag, parts);
                });
                suggestions.appendChild(li);
            });

            suggestions.style.display = 'block';
        }

        function selectTag(tag, parts) {
            parts[parts.length - 1] = ' ' + tag;
            input.value = parts.join(',').replace(/^,\s*/, '') + ', ';
            hideSuggestions();
            input.focus();
        }

        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                hideSuggestions();
            }
        });

        input.addEventListener('keydown', function (e) {
            const items  = suggestions.querySelectorAll('li');
            const active = suggestions.querySelector('li.active');
            let idx      = Array.from(items).indexOf(active);

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (active) active.classList.remove('active');
                const next = items[idx + 1] || items[0];
                if (next) { next.classList.add('active'); next.style.background = '#ede9fe'; }
            }
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (active) active.classList.remove('active');
                const prev = items[idx - 1] || items[items.length - 1];
                if (prev) { prev.classList.add('active'); prev.style.background = '#ede9fe'; }
            }
            if (e.key === 'Enter' && active) {
                e.preventDefault();
                const parts = input.value.split(',');
                selectTag(active.textContent, parts);
            }
            if (e.key === 'Escape') hideSuggestions();
        });

        function hideSuggestions() {
            suggestions.style.display = 'none';
            suggestions.innerHTML     = '';
        }
    })();
</script>
@endpush