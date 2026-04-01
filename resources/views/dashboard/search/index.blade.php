@extends('dashboard.master')
@section('pageTitle', $pageTitle)

@section('content')

    <ul class="mir-breadcrumb">
        <li class="mir-breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house" style="font-size:.65rem"></i></a></li>
        <li class="mir-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></li>
        <li class="mir-breadcrumb-item active">Busca</li>
    </ul>

    {{-- ── Cabeçalho ──────────────────────────────────────────────────────── --}}
    <div class="sdb-section" style="margin-bottom:20px;">
        <div class="sdb-header">
            <div>
                <div class="sdb-title">
                    <i class="fa fa-search" style="color:#6366f1;margin-right:6px;"></i>
                    Resultados da Busca
                </div>
                <div class="sdb-sub">
                    @if(strlen($q) >= 2)
                        {{ $total }} resultado(s) para
                        <strong style="color:#1a1d23;">"{{ $q }}"</strong>
                    @else
                        Digite ao menos 2 caracteres para buscar
                    @endif
                </div>
            </div>

            {{-- Formulário de nova busca --}}
            <form action="{{ route('admin.search') }}" method="GET"
                  style="display:flex;gap:8px;align-items:center;">
                <div style="position:relative;">
                    <i class="fa fa-search" style="position:absolute;left:10px;top:50%;
                                                   transform:translateY(-50%);color:#9ca3af;
                                                   font-size:.8rem;pointer-events:none;"></i>
                    <input type="text" name="q" value="{{ $q }}"
                           class="mir-input"
                           style="padding-left:30px;min-width:240px;"
                           placeholder="Buscar no dashboard..."
                           autofocus>
                </div>
                <button type="submit" class="mir-btn-primary-lg">
                    <i class="fa fa-search"></i> Buscar
                </button>
            </form>
        </div>
    </div>

    @if(strlen($q) >= 2 && $total === 0)
        {{-- Empty state --}}
        <div class="sdb-section">
            <div class="mir-empty-state">
                <div class="mir-empty-icon"><i class="fa fa-search"></i></div>
                <h5 class="mir-empty-title">Nenhum resultado encontrado</h5>
                <p class="mir-empty-desc">Tente outros termos de busca.</p>
            </div>
        </div>
    @endif

    @if($total > 0)
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

            {{-- ── Posts ───────────────────────────────────────────────────── --}}
            @if($posts->count() > 0)
                <div class="sdb-section" style="grid-column:1/-1;">
                    <div class="sdb-header" style="padding:12px 16px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="width:28px;height:28px;border-radius:7px;
                                         background:rgba(99,102,241,.12);color:#6366f1;
                                         display:flex;align-items:center;justify-content:center;
                                         font-size:.75rem;">
                                <i class="fa fa-file-text-o"></i>
                            </span>
                            <span class="sdb-title" style="font-size:.88rem;">Posts</span>
                            <span style="display:inline-flex;align-items:center;justify-content:center;
                                         min-width:20px;height:20px;padding:0 6px;border-radius:50px;
                                         background:#6366f1;color:#fff;font-size:.65rem;font-weight:700;">
                                {{ $posts->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.posts.index') }}"
                           style="font-size:.75rem;color:#6366f1;text-decoration:none;font-weight:600;">
                            Ver todos →
                        </a>
                    </div>
                    <div class="sdb-list">
                        @foreach($posts as $post)
                            <div class="sdb-row" style="gap:12px;">
                                {{-- Thumbnail --}}
                                @if($post->thumbnail)
                                    <img src="{{ asset('uploads/posts/'.$post->thumbnail) }}"
                                         style="width:40px;height:40px;border-radius:7px;
                                                object-fit:cover;flex-shrink:0;">
                                @else
                                    <span style="width:40px;height:40px;border-radius:7px;
                                                 background:#f3f4f6;display:flex;align-items:center;
                                                 justify-content:center;color:#9ca3af;flex-shrink:0;">
                                        <i class="fa fa-file-text-o"></i>
                                    </span>
                                @endif

                                {{-- Info --}}
                                <div style="flex:1;min-width:0;">
                                    <div style="font-size:.83rem;font-weight:600;color:#1a1d23;
                                                white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $post->title }}
                                    </div>
                                    <div style="font-size:.72rem;color:#9ca3af;margin-top:2px;
                                                display:flex;align-items:center;gap:8px;">
                                        <span>{{ $post->author->name }}</span>
                                        <span>·</span>
                                        <span>{{ $post->category->name }}</span>
                                        <span>·</span>
                                        <span>{{ $post->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>

                                {{-- Status --}}
                                @php
                                    $statusColor = match($post->status) {
                                        'published'     => ['bg'=>'#d1fae5','color'=>'#065f46'],
                                        'draft'         => ['bg'=>'#f3f4f6','color'=>'#6d7279'],
                                        'pending_review'=> ['bg'=>'#fef3c7','color'=>'#92400e'],
                                        default         => ['bg'=>'#f3f4f6','color'=>'#6d7279'],
                                    };
                                @endphp
                                <span style="padding:3px 9px;border-radius:50px;font-size:.68rem;
                                             font-weight:700;flex-shrink:0;
                                             background:{{ $statusColor['bg'] }};
                                             color:{{ $statusColor['color'] }};">
                                    {{ ucfirst($post->status) }}
                                </span>

                                {{-- Ação --}}
                                <a href="{{ route('admin.posts.edit', $post) }}"
                                   class="mir-action-btn mir-action-edit"
                                   title="Editar post">
                                    <i class="fa fa-edit" style="font-size:.75rem;"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── Mídia ────────────────────────────────────────────────────── --}}
            @if($media->count() > 0)
                <div class="sdb-section">
                    <div class="sdb-header" style="padding:12px 16px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="width:28px;height:28px;border-radius:7px;
                                         background:rgba(16,185,129,.12);color:#059669;
                                         display:flex;align-items:center;justify-content:center;
                                         font-size:.75rem;">
                                <i class="fa fa-photo"></i>
                            </span>
                            <span class="sdb-title" style="font-size:.88rem;">Mídia</span>
                            <span style="display:inline-flex;align-items:center;justify-content:center;
                                         min-width:20px;height:20px;padding:0 6px;border-radius:50px;
                                         background:#10b981;color:#fff;font-size:.65rem;font-weight:700;">
                                {{ $media->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.media') }}"
                           style="font-size:.75rem;color:#6366f1;text-decoration:none;font-weight:600;">
                            Ver todos →
                        </a>
                    </div>
                    <div class="sdb-list">
                        @foreach($media as $file)
                            <div class="sdb-row" style="gap:10px;">
                                <span style="width:34px;height:34px;border-radius:8px;flex-shrink:0;
                                             display:flex;align-items:center;justify-content:center;
                                             font-size:.85rem;
                                             background:{{ $file->icon_color }}1a;
                                             color:{{ $file->icon_color }};">
                                    <i class="fa {{ $file->icon }}"></i>
                                </span>
                                <div style="flex:1;min-width:0;">
                                    <div style="font-size:.8rem;font-weight:600;color:#1a1d23;
                                                white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"
                                         title="{{ $file->original_name }}">
                                        {{ $file->original_name }}
                                    </div>
                                    <div style="font-size:.7rem;color:#9ca3af;margin-top:1px;">
                                        {{ $file->formatted_size }}
                                        · {{ strtoupper($file->extension) }}
                                        · {{ number_format($file->downloads) }} downloads
                                    </div>
                                </div>
                                <button type="button"
                                        onclick="copyMediaLink('{{ $file->download_url }}', this)"
                                        style="display:inline-flex;align-items:center;gap:4px;
                                               padding:4px 9px;border-radius:6px;
                                               border:1px solid #e5e7eb;background:#fff;
                                               color:#6d7279;font-size:.7rem;font-weight:600;
                                               cursor:pointer;transition:.15s;flex-shrink:0;"
                                        onmouseover="this.style.borderColor='#6366f1';this.style.color='#6366f1'"
                                        onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#6d7279'">
                                    <i class="fa fa-copy"></i> Link
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── Usuários ─────────────────────────────────────────────────── --}}
            @if($users->count() > 0)
                <div class="sdb-section">
                    <div class="sdb-header" style="padding:12px 16px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="width:28px;height:28px;border-radius:7px;
                                         background:rgba(245,158,11,.12);color:#d97706;
                                         display:flex;align-items:center;justify-content:center;
                                         font-size:.75rem;">
                                <i class="fa fa-users"></i>
                            </span>
                            <span class="sdb-title" style="font-size:.88rem;">Usuários</span>
                            <span style="display:inline-flex;align-items:center;justify-content:center;
                                         min-width:20px;height:20px;padding:0 6px;border-radius:50px;
                                         background:#f59e0b;color:#fff;font-size:.65rem;font-weight:700;">
                                {{ $users->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.users.index') }}"
                           style="font-size:.75rem;color:#6366f1;text-decoration:none;font-weight:600;">
                            Ver todos →
                        </a>
                    </div>
                    <div class="sdb-list">
                        @foreach($users as $user)
                            <div class="sdb-row" style="gap:10px;">
                                <img src="{{ $user->picture }}"
                                     style="width:34px;height:34px;border-radius:50%;
                                            object-fit:cover;flex-shrink:0;">
                                <div style="flex:1;min-width:0;">
                                    <div style="font-size:.82rem;font-weight:600;color:#1a1d23;">
                                        {{ $user->name }}
                                    </div>
                                    <div style="font-size:.7rem;color:#9ca3af;">
                                        @{{ $user->username }} · {{ $user->email }}
                                    </div>
                                </div>
                                <span style="padding:2px 8px;border-radius:50px;font-size:.68rem;
                                             font-weight:700;background:#f3f4f6;color:#6d7279;">
                                    {{ ucfirst($user->role->value) }}
                                </span>
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="mir-action-btn mir-action-edit"
                                   title="Editar usuário">
                                    <i class="fa fa-edit" style="font-size:.75rem;"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── Categorias ───────────────────────────────────────────────── --}}
            @if($categories->count() > 0)
                <div class="sdb-section">
                    <div class="sdb-header" style="padding:12px 16px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="width:28px;height:28px;border-radius:7px;
                                         background:rgba(236,72,153,.12);color:#db2777;
                                         display:flex;align-items:center;justify-content:center;
                                         font-size:.75rem;">
                                <i class="fa fa-folder-o"></i>
                            </span>
                            <span class="sdb-title" style="font-size:.88rem;">Categorias</span>
                            <span style="display:inline-flex;align-items:center;justify-content:center;
                                         min-width:20px;height:20px;padding:0 6px;border-radius:50px;
                                         background:#ec4899;color:#fff;font-size:.65rem;font-weight:700;">
                                {{ $categories->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.categories.index') }}"
                           style="font-size:.75rem;color:#6366f1;text-decoration:none;font-weight:600;">
                            Ver todos →
                        </a>
                    </div>
                    <div class="sdb-list">
                        @foreach($categories as $cat)
                            <div class="sdb-row" style="gap:10px;">
                                <span style="width:34px;height:34px;border-radius:8px;flex-shrink:0;
                                             background:rgba(236,72,153,.1);color:#db2777;
                                             display:flex;align-items:center;justify-content:center;
                                             font-size:.8rem;">
                                    <i class="fa fa-folder-o"></i>
                                </span>
                                <div style="flex:1;min-width:0;">
                                    <div style="font-size:.82rem;font-weight:600;color:#1a1d23;">
                                        {{ $cat->name }}
                                    </div>
                                    <div style="font-size:.7rem;color:#9ca3af;">
                                        {{ $cat->posts_count }} post(s)
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── Tags ─────────────────────────────────────────────────────── --}}
            @if($tags->count() > 0)
                <div class="sdb-section">
                    <div class="sdb-header" style="padding:12px 16px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="width:28px;height:28px;border-radius:7px;
                                         background:rgba(139,92,246,.12);color:#7c3aed;
                                         display:flex;align-items:center;justify-content:center;
                                         font-size:.75rem;">
                                <i class="fa fa-tag"></i>
                            </span>
                            <span class="sdb-title" style="font-size:.88rem;">Tags</span>
                            <span style="display:inline-flex;align-items:center;justify-content:center;
                                         min-width:20px;height:20px;padding:0 6px;border-radius:50px;
                                         background:#8b5cf6;color:#fff;font-size:.65rem;font-weight:700;">
                                {{ $tags->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.tags.index') }}"
                           style="font-size:.75rem;color:#6366f1;text-decoration:none;font-weight:600;">
                            Ver todos →
                        </a>
                    </div>
                    <div class="sdb-list">
                        @foreach($tags as $tag)
                            <div class="sdb-row" style="gap:10px;">
                                <span style="width:34px;height:34px;border-radius:8px;flex-shrink:0;
                                             background:rgba(139,92,246,.1);color:#7c3aed;
                                             display:flex;align-items:center;justify-content:center;
                                             font-size:.8rem;">
                                    <i class="fa fa-tag"></i>
                                </span>
                                <div style="flex:1;min-width:0;">
                                    <div style="font-size:.82rem;font-weight:600;color:#1a1d23;">
                                        {{ $tag->name }}
                                    </div>
                                    <div style="font-size:.7rem;color:#9ca3af;">
                                        {{ $tag->posts_count }} post(s)
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    @endif

@endsection

@push('scripts')
<script>
function copyMediaLink(url, btn) {
    navigator.clipboard.writeText(url).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-check"></i> Copiado!';
        btn.style.borderColor = '#10b981';
        btn.style.color = '#059669';
        setTimeout(() => {
            btn.innerHTML = original;
            btn.style.borderColor = '#e5e7eb';
            btn.style.color = '#6d7279';
        }, 2000);
    });
}
</script>
@endpush