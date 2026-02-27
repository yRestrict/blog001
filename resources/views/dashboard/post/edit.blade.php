@extends('dashboard.master')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Editar Post')
@section('content')

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Editar Post</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Post</li>
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

<form action="{{ route('admin.posts.update', $post->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
    @csrf
    @method('PUT')

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
                               value="{{ old('title', $post->title) }}"
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
                                  placeholder="Digite o conteúdo do post aqui">{{ old('content', $post->content) }}</textarea>
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
                               value="{{ old('meta_keywords', $post->meta_keywords) }}"
                               placeholder="palavra-chave1, palavra-chave2">
                    </div>
                    <div class="form-group">
                        <label><b>Meta Description</b></label>
                        <textarea name="meta_description"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Descrição para mecanismos de busca">{{ old('meta_description', $post->meta_description) }}</textarea>
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
                    <div class="form-group">
                        <label><b>Tags</b></label>
                        <input type="text"
                               class="form-control"
                               name="tags"
                               value="{{ old('tags', $post->tags->pluck('name')->join(',')) }}"
                               data-role="tagsinput"
                               placeholder="Adicione tags separadas por vírgula">
                        <small class="text-muted">Separe as tags por vírgula. Novas tags serão criadas automaticamente.</small>
                    </div>

                    {{-- Imagem atual --}}
                    @if ($post->featured_image)
                        <div class="form-group">
                            <label><b>Imagem Atual</b></label>
                            <div class="d-block mb-2">
                                <img src="{{ asset('uploads/posts/' . $post->featured_image) }}"
                                     alt="Imagem atual"
                                     class="img-thumbnail"
                                     style="max-height: 180px;">
                            </div>
                        </div>
                    @endif

                    {{-- Nova imagem --}}
                    <div class="form-group">
                        <label><b>{{ $post->featured_image ? 'Substituir Imagem' : 'Imagem Destacada' }}</b></label>
                        <input type="file"
                               name="featured_image"
                               class="form-control-file form-control @error('featured_image') is-invalid @enderror"
                               id="featured-image-input"
                               accept="image/*">
                        @error('featured_image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-block mb-3" style="display:none;" id="preview-wrapper">
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
                                   {{ old('featured', $post->featured) ? 'checked' : '' }}>
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
                                   {{ old('comment', $post->comment) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="checkComment">Permitir comentários</label>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-group">
                        <label><b>Status</b></label>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" id="statusDraft" name="status" value="draft"
                                   class="custom-control-input"
                                   {{ old('status', $post->status) === 'draft' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusDraft">Rascunho</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" id="statusPublished" name="status" value="published"
                                   class="custom-control-input"
                                   {{ old('status', $post->status) === 'published' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusPublished">Publicado</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" id="statusPrivate" name="status" value="private"
                                   class="custom-control-input"
                                   {{ old('status', $post->status) === 'private' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusPrivate">Privado</label>
                        </div>
                        @error('status')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Info do post --}}
                    <hr>
                    <small class="text-muted d-block">
                        <i class="fa fa-calendar"></i>
                        Criado em: {{ $post->created_at->format('d/m/Y \à\s H:i') }}
                    </small>
                    @if ($post->updated_at->ne($post->created_at))
                        <small class="text-muted d-block mt-1">
                            <i class="fa fa-edit"></i>
                            Atualizado em: {{ $post->updated_at->format('d/m/Y \à\s H:i') }}
                        </small>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <div class="mb-4">
        <button type="submit" class="btn btn-success">
            <i class="fa fa-save"></i> Salvar Alterações
        </button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary ml-2">Cancelar</a>
    </div>

</form>

@endsection

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('dashboard/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('dashboard/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
<script>
    // Preview da nova imagem
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
</script>
@endpush