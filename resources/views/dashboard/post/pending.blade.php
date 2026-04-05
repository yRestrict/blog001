@extends('dashboard.master')
@section('pageTitle', $pageTitle)
@section('content')

    <ul class="mir-breadcrumb">
        <li class="mir-breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house" style="font-size:.65rem"></i></a></li>
        <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
        <li class="mir-breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
        <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
        <li class="mir-breadcrumb-item active">Pendentes</li>
    </ul>

    {{-- ================================================================ --}}
    {{-- PAGE HEADER                                                       --}}
    {{-- ================================================================ --}}
    <div class="page-header-action">
        <div class="page-header-left">
            <h1 class="page-header-title">
                Posts Pendentes
                <span class="page-header-title-count" style="background:#fef3c7;color:#92400e;">{{ $posts->total() }}</span>
            </h1>
            <span class="page-header-sub">Posts aguardando aprovação</span>
        </div>
        <div class="page-header-right">
            <a href="{{ route('admin.posts.index') }}" class="mir-btn-neutral">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- TOAST SESSION                                                     --}}
    {{-- ================================================================ --}}
    @if(session('success'))
        <div id="pending-session-toast" style="display:flex;margin-bottom:20px;">
            <div style="display:flex;align-items:center;gap:8px;padding:12px 16px;
                        background:#d1fae5;color:#065f46;border-radius:8px;
                        font-size:.82rem;font-weight:600;border:1px solid #6ee7b7;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        </div>
        <script>
            setTimeout(() => {
                const t = document.getElementById('pending-session-toast');
                if (t) t.style.display = 'none';
            }, 3500);
        </script>
    @endif

    {{-- ================================================================ --}}
    {{-- SECTION CARD                                                      --}}
    {{-- ================================================================ --}}
    <div class="post-section">

        {{-- Table Header --}}
        <div class="mir-table-header">
            <span class="plh-thumb"></span>
            <span class="plh-body">Post</span>
            <span style="width:130px;flex-shrink:0;">Autor</span>
            <span style="width:130px;flex-shrink:0;">Categoria</span>
            <span style="width:120px;flex-shrink:0;text-align:right;">Submetido em</span>
            <span class="plh-divider"></span>
            <span style="width:100px;flex-shrink:0;text-align:center;">Ações</span>
        </div>

        {{-- Data Rows --}}
        <div class="mir-data-list">
            @forelse($posts as $post)
                <div class="mir-data-row">

                    {{-- Thumbnail --}}
                    <div class="post-thumb">
                        @if($post->thumbnail)
                            <img src="{{ asset('uploads/posts/' . $post->thumbnail) }}" alt="{{ $post->title }}">
                        @else
                            <img src="{{ $post->author->picture }}" alt="{{ $post->author->name }}">
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="post-body">
                        <div class="post-name">
                            <span class="post-name-text">{{ $post->title }}</span>
                            <span class="mir-badge-pending">
                                <i class="fa-solid fa-clock" style="font-size:.55rem"></i> Pendente
                            </span>
                        </div>
                        @if($post->meta_description)
                            <div class="post-info">{{ Str::limit($post->meta_description, 80) }}</div>
                        @endif
                    </div>

                    {{-- Autor --}}
                    <div style="width:130px;flex-shrink:0;">
                        <span style="font-size:.78rem;font-weight:600;color:#374151;">{{ $post->author->name }}</span>
                    </div>

                    {{-- Categoria --}}
                    <div style="width:130px;flex-shrink:0;">
                        <span class="mir-badge-parent">{{ $post->category->name }}</span>
                    </div>

                    {{-- Data --}}
                    <div style="width:120px;flex-shrink:0;text-align:right;">
                        <span style="font-size:.75rem;color:#374151;">{{ $post->created_at->format('d/m/Y') }}</span>
                        <div style="font-size:.7rem;color:#9ca3af;">{{ $post->created_at->format('H:i') }}</div>
                    </div>

                    <div class="mir-divider"></div>

                    {{-- Ações --}}
                    <div class="mir-actions" style="width:100px;justify-content:center;">

                        {{-- Visualizar --}}
                        <a href="{{ route('admin.posts.edit', $post) }}"
                           class="mir-action-btn mir-action-edit"
                           data-tooltip="Visualizar post"
                           target="_blank">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </a>

                        {{-- Aprovar --}}
                        <form action="{{ route('admin.posts.approve', $post) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="mir-action-btn mir-action-restore"
                                    data-tooltip="Aprovar post">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </button>
                        </form>

                        {{-- Rejeitar --}}
                        <button type="button"
                                class="mir-action-btn mir-action-delete"
                                data-tooltip="Rejeitar post"
                                onclick="openRejectModal({{ $post->id }}, '{{ addslashes($post->title) }}')">
                            <svg width="12" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="mir-empty-state">
                    <div class="mir-empty-icon">
                        <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
                    </div>
                    <h3 class="mir-empty-title">Tudo em dia!</h3>
                    <p class="mir-empty-desc">Nenhum post aguardando aprovação.</p>
                    <a href="{{ route('admin.posts.index') }}" class="mir-btn-primary-lg">
                        <i class="fa-solid fa-arrow-left"></i> Voltar para Posts
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Paginação --}}
        @if($posts->hasPages())
            <div class="mir-pagination">
                <div class="mir-pagination-left"></div>
                <div class="mir-pagination-center">
                    @if($posts->onFirstPage())
                        <span class="mir-page-btn disabled"><i class="fa-solid fa-chevron-left" style="font-size:.6rem;"></i></span>
                    @else
                        <a href="{{ $posts->previousPageUrl() }}" class="mir-page-btn"><i class="fa-solid fa-chevron-left" style="font-size:.6rem;"></i></a>
                    @endif

                    @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="mir-page-btn {{ $page == $posts->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach

                    @if($posts->hasMorePages())
                        <a href="{{ $posts->nextPageUrl() }}" class="mir-page-btn"><i class="fa-solid fa-chevron-right" style="font-size:.6rem;"></i></a>
                    @else
                        <span class="mir-page-btn disabled"><i class="fa-solid fa-chevron-right" style="font-size:.6rem;"></i></span>
                    @endif
                </div>
                <div class="mir-pagination-right">
                    Mostrando {{ $posts->firstItem() }}–{{ $posts->lastItem() }} de {{ $posts->total() }}
                </div>
            </div>
        @endif

    </div>

    {{-- ================================================================ --}}
    {{-- MODAL DE REJEIÇÃO                                                 --}}
    {{-- ================================================================ --}}
    <div id="reject-modal" style="display:none; position:fixed; inset:0; z-index:9999;
         background:rgba(0,0,0,.45); align-items:center; justify-content:center;">
        <div class="mir-modal-dialog" style="max-width:460px;">
            <div class="mir-modal-content">

                <div class="mir-modal-header">
                    <div class="mir-modal-title">
                        <div class="mir-modal-icon mir-modal-icon-delete">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                        <div>
                            <div class="mir-modal-title-text">Rejeitar Post</div>
                            <div id="reject-post-title" class="mir-modal-subtitle"></div>
                        </div>
                    </div>
                    <button onclick="closeRejectModal()" class="mir-modal-close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form id="reject-form" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mir-modal-body">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="mir-label" style="margin-bottom:6px;display:block;">
                                Motivo da rejeição
                                <span style="color:#9ca3af;font-weight:400;">(opcional)</span>
                            </label>
                            <textarea name="reason" rows="4" class="mir-input"
                                      placeholder="Explique ao autor o que precisa ser corrigido..."
                                      style="resize:vertical;"></textarea>
                            <div style="font-size:.72rem;color:#9ca3af;margin-top:4px;">
                                O autor será notificado com esta mensagem.
                            </div>
                        </div>
                    </div>
                    <div class="mir-modal-footer">
                        <button type="button" onclick="closeRejectModal()" class="mir-btn-ghost">Cancelar</button>
                        <button type="submit" class="mir-btn-danger">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                            Rejeitar Post
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- SCOPED STYLES                                                    --}}
    {{-- ================================================================ --}}
    <style>
        .post-section {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            margin-bottom: 24px;
        }
        .post-thumb {
            width: 48px; height: 48px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            flex-shrink: 0;
            background: #f3f4f6;
            overflow: hidden;
        }
        .post-thumb img {
            width: 100%; height: 100%; object-fit: cover; display: block;
        }
        .post-body { flex: 1 1 0; min-width: 0; }
        .post-name {
            font-size: .875rem; font-weight: 600; color: #1a1d23;
            display: flex; align-items: center; gap: 6px; min-width: 0;
        }
        .post-name-text {
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0;
        }
        .post-info { font-size: .72rem; color: #9ca3af; margin-top: 2px; }
        .mir-badge-pending {
            display: inline-flex; align-items: center; gap: 3px;
            padding: 2px 7px; border-radius: 50px;
            font-size: .65rem; font-weight: 700;
            background: #fef3c7; color: #92400e; flex-shrink: 0;
        }
    </style>

@endsection

@push('scripts')
<script>
    function openRejectModal(postId, postTitle) {
        const base = '{{ url("admin/posts") }}';
        document.getElementById('reject-form').action = base + '/' + postId + '/reject';
        document.getElementById('reject-post-title').textContent = postTitle;
        document.getElementById('reject-modal').style.display = 'flex';
    }

    function closeRejectModal() {
        document.getElementById('reject-modal').style.display = 'none';
        document.querySelector('#reject-form textarea[name="reason"]').value = '';
    }

    document.getElementById('reject-modal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
</script>
@endpush