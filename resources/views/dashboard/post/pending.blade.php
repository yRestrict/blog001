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

    <!-- Page Header Action -->
    <div class="page-header-action">
        <div class="page-header-left">
            <div class="page-header-title">
                Posts Pendentes
                <span class="page-header-title-count" style="background: #fef3c7; color: #92400e;">{{ $posts->total() }}</span>
            </div>
            <div class="page-header-sub">Posts aguardando aprovação</div>
        </div>
        <div class="page-header-right">
            <a href="{{ route('admin.posts.index') }}" class="mir-btn-neutral">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mir-toast" style="position: relative; bottom: auto; right: auto; margin-bottom: 20px; display: flex;"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)">
            <div style="display:flex;align-items:center;gap:8px;padding:12px 16px;background:#d1fae5;color:#065f46;
                        border-radius:8px;font-size:.82rem;font-weight:600;border:1px solid #6ee7b7;">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Section Card -->
    <div class="post-section">

        {{-- Table Header --}}
        <div class="mir-table-header">
            <div style="flex: 1 1 0; min-width: 0;">Post</div>
            <div style="width: 120px; flex-shrink: 0;">Autor</div>
            <div style="width: 120px; flex-shrink: 0;">Categoria</div>
            <div style="width: 120px; flex-shrink: 0; text-align: right;">Submetido em</div>
            <div class="mir-divider"></div>
            <div style="width: 100px; flex-shrink: 0; text-align: center;">Ações</div>
        </div>

        {{-- Data Rows --}}
        <div class="post-list">
            @forelse($posts as $post)
                <div class="post-row">
                    {{-- Avatar --}}
                    <div style="width: 36px; height: 36px; border-radius: 50%; overflow: hidden; flex-shrink: 0;
                                border: 1px solid #e5e7eb;">
                        <img src="{{ $post->author->picture }}"
                             alt="{{ $post->author->name }}"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    </div>

                    {{-- Título + Meta --}}
                    <div class="post-body">
                        <div class="post-name">{{ $post->title }}</div>
                        @if($post->meta_description)
                            <div class="post-info">{{ Str::limit($post->meta_description, 80) }}</div>
                        @endif
                    </div>

                    {{-- Autor --}}
                    <div style="width: 120px; flex-shrink: 0;">
                        <span style="font-size: .78rem; font-weight: 600; color: #374151;">{{ $post->author->name }}</span>
                    </div>

                    {{-- Categoria --}}
                    <div style="width: 120px; flex-shrink: 0;">
                        <span class="mir-badge-parent">{{ $post->category->name }}</span>
                    </div>

                    {{-- Data --}}
                    <div style="width: 120px; flex-shrink: 0; text-align: right;">
                        <span style="font-size: .75rem; color: #9ca3af;">{{ $post->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="mir-divider"></div>

                    {{-- Ações --}}
                    <div style="width: 100px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; gap: 4px;">

                        {{-- Visualizar --}}
                        <a href="{{ route('admin.posts.edit', $post) }}"
                           class="mir-action-btn mir-action-edit"
                           data-tooltip="Visualizar post" target="_blank">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </a>

                        {{-- Aprovar --}}
                        <form action="{{ route('admin.posts.approve', $post) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="mir-action-btn mir-action-restore"
                                    data-tooltip="Aprovar post">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </button>
                        </form>

                        {{-- Rejeitar — abre modal --}}
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
                        <i class="fa-solid fa-check-circle" style="color: #10b981;"></i>
                    </div>
                    <div class="mir-empty-title">Tudo em dia!</div>
                    <div class="mir-empty-desc">Nenhum post aguardando aprovação.</div>
                    <a href="{{ route('admin.posts.index') }}" class="mir-btn-primary-lg">
                        <i class="fa-solid fa-arrow-left"></i> Voltar para Posts
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Paginação --}}
        @if($posts->hasPages())
            <div class="post-pagination">
                <div class="pagination-left">
                    <span class="pagination-right">Mostrando {{ $posts->firstItem() }}–{{ $posts->lastItem() }} de {{ $posts->total() }}</span>
                </div>
                <div class="pagination-center">
                    @if($posts->onFirstPage())
                        <span class="page-btn disabled"><i class="fa fa-chevron-left" style="font-size:.6rem;"></i></span>
                    @else
                        <a href="{{ $posts->previousPageUrl() }}" class="page-btn"><i class="fa fa-chevron-left" style="font-size:.6rem;"></i></a>
                    @endif

                    @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $posts->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach

                    @if($posts->hasMorePages())
                        <a href="{{ $posts->nextPageUrl() }}" class="page-btn"><i class="fa fa-chevron-right" style="font-size:.6rem;"></i></a>
                    @else
                        <span class="page-btn disabled"><i class="fa fa-chevron-right" style="font-size:.6rem;"></i></span>
                    @endif
                </div>
                <div class="pagination-right"></div>
            </div>
        @endif

    </div>

    {{-- ── Modal de Rejeição ─────────────────────────────────────────────────── --}}
    <div id="reject-modal" style="display:none; position:fixed; inset:0; z-index:9999;
         background:rgba(0,0,0,.45); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:12px; padding:28px; width:100%; max-width:460px;
                    margin:16px; box-shadow:0 20px 60px rgba(0,0,0,.2);">

            {{-- Header --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:18px;">
                <div>
                    <div style="font-size:.95rem; font-weight:700; color:#1a1d23;">Rejeitar Post</div>
                    <div id="reject-post-title" style="font-size:.78rem; color:#9ca3af; margin-top:2px;"></div>
                </div>
                <button onclick="closeRejectModal()"
                        style="background:none; border:none; cursor:pointer; color:#9ca3af; padding:4px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <form id="reject-form" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="mir-label" style="margin-bottom:6px; display:block;">
                        Motivo da rejeição
                        <span style="color:#9ca3af; font-weight:400;">(opcional)</span>
                    </label>
                    <textarea name="reason" rows="4" class="mir-input"
                              placeholder="Explique ao autor o que precisa ser corrigido..."
                              style="resize:vertical;"></textarea>
                    <div style="font-size:.72rem; color:#9ca3af; margin-top:4px;">
                        O autor será notificado com esta mensagem.
                    </div>
                </div>

                <div style="display:flex; gap:8px; justify-content:flex-end;">
                    <button type="button" onclick="closeRejectModal()" class="mir-btn-neutral">
                        Cancelar
                    </button>
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px;
                                   background:#fee2e2; color:#991b1b; border:1px solid #fca5a5;
                                   border-radius:7px; font-size:.82rem; font-weight:600; cursor:pointer;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        Rejeitar Post
                    </button>
                </div>
            </form>
        </div>
    </div>

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

    // Fecha ao clicar fora do modal
    document.getElementById('reject-modal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
</script>
@endpush