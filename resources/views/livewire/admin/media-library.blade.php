<div>

    {{-- ================================================================ --}}
    {{-- PAGE HEADER ACTION                                               --}}
    {{-- ================================================================ --}}
    <div class="page-header-action">
        <div class="page-header-left">
            <h1 class="page-header-title">
                Mídia
                <span class="page-header-title-count">{{ $files->total() }}</span>
            </h1>
            <span class="page-header-sub">Gerencie arquivos e copie links para usar nos posts</span>
        </div>
        <div class="page-header-right">
            <button class="mir-btn-primary-lg" wire:click="openUpload">
                <i class="fa-solid fa-cloud-arrow-up"></i> Enviar Arquivo
            </button>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- SECTION CARD                                                     --}}
    {{-- ================================================================ --}}
    <div class="mda-section">

        {{-- Camada 1 — Section Header --}}
        <div class="mda-section-header">
            <div class="mda-section-header-left">
                <h3 class="mda-section-title">Biblioteca de Mídia</h3>
                <p class="mda-section-sub">Gerencie arquivos e copie links para usar nos posts</p>
            </div>
        </div>

        {{-- Camada 2 — Filter Header --}}
        <div class="mda-filter-header">
            <div style="position:relative; flex:1; max-width:280px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.75rem;"></i>
                <input type="text" wire:model.live.debounce.400ms="search" class="mir-input" placeholder="Buscar por nome..." style="padding-left:32px;">
            </div>
            <select wire:model.live="filterExt" class="mir-input" style="width:160px;">
                <option value="">Todos os tipos</option>
                @foreach($extensions as $ext)
                    <option value="{{ $ext }}">{{ strtoupper($ext) }}</option>
                @endforeach
            </select>
            @if($search || $filterExt)
                <button wire:click="$set('search','');$set('filterExt','')"
                        class="mir-btn-ghost" style="padding:8px 12px;font-size:.78rem;">
                    <i class="fa-solid fa-xmark"></i> Limpar
                </button>
            @endif
        </div>

        {{-- Loading bar --}}
        <div wire:loading class="mir-loading-bar"></div>

        {{-- Table Header --}}
        <div class="mir-table-header">
            <span class="mda-plh-file">Arquivo</span>
            <span class="mda-plh-uploader">Enviado por</span>
            <span class="mda-plh-size" style="text-align:center;">Tamanho</span>
            <span class="mda-plh-downloads" style="text-align:center;">Downloads</span>
            <span class="mda-plh-date" style="text-align:center;">Data</span>
            <span class="plh-divider"></span>
            <span class="mda-plh-actions" style="text-align:center;">Ações</span>
        </div>

        {{-- Data Rows --}}
        @if($files->isEmpty())
            <div class="mir-empty-state">
                <div class="mir-empty-icon"><i class="fa-solid fa-folder-open"></i></div>
                @if($search || $filterExt)
                    <h5 class="mir-empty-title">Nenhum arquivo encontrado</h5>
                    <p class="mir-empty-desc">Tente ajustar os filtros ou termos de busca.</p>
                @else
                    <h5 class="mir-empty-title">Nenhum arquivo enviado</h5>
                    <p class="mir-empty-desc">Envie o primeiro arquivo para começar.</p>
                    <button class="mir-btn-primary-lg" wire:click="openUpload">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Enviar agora
                    </button>
                @endif
            </div>
        @else
            <div class="mir-data-list" wire:loading.class="mir-loading-overlay">
                @foreach($files as $file)
                    <div class="mir-data-row" wire:key="media-{{ $file->id }}">

                        {{-- Arquivo (ícone + nome + extensão) --}}
                        <div class="mda-file">
                            <span class="mda-file-icon" style="background:{{ $file->icon_color }}1a;color:{{ $file->icon_color }};">
                                <i class="fa {{ $file->icon }}"></i>
                            </span>
                            <div class="mda-file-info">
                                <div class="mda-file-name" data-tooltip="{{ $file->original_name }}">{{ $file->original_name }}</div>
                                <span class="mda-file-ext">{{ strtoupper($file->extension) }}</span>
                            </div>
                        </div>

                        {{-- Enviado por --}}
                        <div class="mda-uploader">
                            <img src="{{ $file->uploader->picture }}" alt="" class="mda-uploader-avatar">
                            <span class="mda-uploader-name">{{ $file->uploader->name }}</span>
                        </div>

                        {{-- Tamanho --}}
                        <div class="mda-size">{{ $file->formatted_size }}</div>

                        {{-- Downloads --}}
                        <div class="mda-downloads">
                            <i class="fa-solid fa-download" style="color:#6366f1;font-size:.65rem;"></i>
                            {{ number_format($file->downloads) }}
                        </div>

                        {{-- Data --}}
                        <div class="mda-date">
                            <span>{{ $file->created_at->format('d/m/Y') }}</span>
                            <span class="mda-date-time">{{ $file->created_at->format('H:i') }}</span>
                        </div>

                        <div class="mir-divider"></div>

                        {{-- Ações --}}
                        <div class="mir-actions">
                            <button type="button"
                                    class="mir-action-btn mda-action-copy"
                                    onclick="copyMediaLink('{{ $file->download_url }}', this)"
                                    data-tooltip="Copiar link">
                                <i class="fa-solid fa-copy" style="font-size:.7rem;"></i>
                            </button>
                            <a href="{{ $file->download_url }}"
                               target="_blank"
                               class="mir-action-btn mda-action-download"
                               data-tooltip="Download">
                                <i class="fa-solid fa-download" style="font-size:.7rem;"></i>
                            </a>
                            <button type="button"
                                    class="mir-action-btn mir-action-delete"
                                    wire:click="confirmDelete({{ $file->id }})"
                                    data-tooltip="Remover arquivo">
                                <i class="fa-solid fa-trash-can" style="font-size:.7rem;"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginação --}}
            <div class="mir-pagination">
                <div class="mir-pagination-left"></div>
                <div class="mir-pagination-center">
                    {{ $files->links() }}
                </div>
                <div class="mir-pagination-right">
                    @if($files->total() > 0)
                        Mostrando {{ $files->firstItem() }}–{{ $files->lastItem() }} de {{ $files->total() }}
                    @endif
                </div>
            </div>
        @endif
    </div>


    {{-- ================================================================ --}}
    {{-- MODAL: UPLOAD                                                     --}}
    {{-- ================================================================ --}}
    @if($showUploadModal)
        <div class="mir-modal-overlay" wire:key="modal-upload">
            <div class="mir-modal-dialog" style="max-width:540px;">
                <div class="mir-modal-content">

                    <div class="mir-modal-header">
                        <div class="mir-modal-title">
                            <div class="mir-modal-icon mir-modal-icon-add">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div>
                                <div class="mir-modal-title-text">Enviar Arquivos</div>
                                <div class="mir-modal-subtitle">Selecione um ou mais arquivos (máx. 200MB cada)</div>
                            </div>
                        </div>
                        <button type="button" class="mir-modal-close"
                                wire:click="$set('showUploadModal', false)">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="mir-modal-body">
                        <label for="media-file-input" class="mda-dropzone">
                            <div class="mda-dropzone-icon">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <p class="mda-dropzone-title">Clique para selecionar ou arraste aqui</p>
                            <p class="mda-dropzone-desc">Imagens, documentos, áudio, vídeo, compactados...</p>
                            <input id="media-file-input" type="file" wire:model="uploadedFiles" multiple style="display:none;">
                        </label>

                        <div wire:loading wire:target="uploadedFiles"
                             style="text-align:center;padding:10px 0;font-size:.82rem;color:#6366f1;">
                            <i class="fa-solid fa-spinner fa-spin"></i> Processando arquivos...
                        </div>

                        @if(count($uploadedFiles) > 0)
                            <div style="margin-top:14px;">
                                <div style="font-size:.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">
                                    {{ count($uploadedFiles) }} arquivo(s) selecionado(s)
                                </div>
                                @foreach($uploadedFiles as $f)
                                    <div class="mda-file-preview">
                                        <i class="fa-solid fa-file" style="color:#9ca3af;font-size:.8rem;flex-shrink:0;"></i>
                                        <span class="mda-file-preview-name">{{ $f->getClientOriginalName() }}</span>
                                        <span class="mda-file-preview-size">{{ number_format($f->getSize() / 1024 / 1024, 2) }} MB</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @error('uploadedFiles.*')
                            <div class="mir-field-error" style="margin-top:8px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mir-modal-footer">
                        <button type="button" class="mir-btn-ghost"
                                wire:click="$set('showUploadModal', false)">Cancelar</button>
                        <button type="button" class="mir-btn-primary-lg"
                                wire:click="saveUpload"
                                wire:loading.attr="disabled"
                                wire:target="saveUpload"
                                {{ count($uploadedFiles) === 0 ? 'disabled' : '' }}>
                            <span wire:loading wire:target="saveUpload">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                            </span>
                            <i class="fa-solid fa-cloud-arrow-up" wire:loading.remove wire:target="saveUpload"></i>
                            Enviar {{ count($uploadedFiles) > 0 ? count($uploadedFiles).' arquivo(s)' : '' }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif


    {{-- ================================================================ --}}
    {{-- MODAL: CONFIRMAR EXCLUSÃO                                         --}}
    {{-- ================================================================ --}}
    @if($confirmDeleteId !== null)
        <div class="mir-modal-overlay" wire:key="modal-delete-media">
            <div class="mir-modal-dialog" style="max-width:440px;">
                <div class="mir-modal-content">

                    <div class="mir-modal-header">
                        <div class="mir-modal-title">
                            <div class="mir-modal-icon mir-modal-icon-delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </div>
                            <div>
                                <div class="mir-modal-title-text">Remover Arquivo</div>
                                <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                            </div>
                        </div>
                        <button type="button" class="mir-modal-close" wire:click="cancelDelete">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="mir-modal-body">
                        @php $toDelete = $files->firstWhere('id', $confirmDeleteId); @endphp
                        <p style="color:#6d7279;font-size:.9rem;line-height:1.6;margin:0;">
                            Tem certeza que deseja remover
                            <strong style="color:#1a1d23;">{{ $toDelete?->original_name ?? 'este arquivo' }}</strong>?
                            <br>
                            <span style="font-size:.8rem;color:#9ca3af;margin-top:4px;display:block;">
                                O arquivo será excluído permanentemente do servidor.
                            </span>
                        </p>
                    </div>

                    <div class="mir-modal-footer">
                        <button class="mir-btn-ghost" wire:click="cancelDelete">Cancelar</button>
                        <button class="mir-btn-danger"
                                wire:click="delete({{ $confirmDeleteId }})"
                                wire:loading.attr="disabled"
                                wire:target="delete({{ $confirmDeleteId }})">
                            <span wire:loading wire:target="delete({{ $confirmDeleteId }})">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                            </span>
                            <i class="fa-solid fa-trash-can" wire:loading.remove wire:target="delete({{ $confirmDeleteId }})"></i>
                            Sim, remover
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif


    {{-- ================================================================ --}}
    {{-- TOAST                                                             --}}
    {{-- ================================================================ --}}
    <div id="media-toast-container" aria-live="polite"></div>


    {{-- ================================================================ --}}
    {{-- SCOPED STYLES (apenas mda- específicos)                          --}}
    {{-- ================================================================ --}}
    <style>
        /* ── Section Card ──────────────────────────────────── */
        .mda-section {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            margin-bottom: 24px;
        }

        /* ── Section header ────────────────────────────────── */
        .mda-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }
        .mda-section-header-left { min-width: 0; }
        .mda-section-title { font-size: .95rem; font-weight: 700; color: #1a1d23; margin: 0; }
        .mda-section-sub { font-size: .78rem; color: #9ca3af; margin-top: 2px; }

        /* ── Filter header ─────────────────────────────────── */
        .mda-filter-header {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            gap: 16px;
        }

        /* ── Table Header: larguras ────────────────────────── */
        .mda-plh-file      { flex: 1 1 0; min-width: 0; }
        .mda-plh-uploader   { width: 140px; flex-shrink: 0; }
        .mda-plh-size       { width: 80px; flex-shrink: 0; }
        .mda-plh-downloads  { width: 90px; flex-shrink: 0; }
        .mda-plh-date       { width: 90px; flex-shrink: 0; }
        .mda-plh-actions    { width: 100px; flex-shrink: 0; }

        /* ── Data row: arquivo ─────────────────────────────── */
        .mda-file {
            flex: 1 1 0; min-width: 0;
            display: flex; align-items: center; gap: 10px;
        }
        .mda-file-icon {
            width: 36px; height: 36px; border-radius: 9px;
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem;
        }
        .mda-file-info { min-width: 0; }
        .mda-file-name {
            font-size: .83rem; font-weight: 600; color: #1a1d23;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 280px;
        }
        .mda-file-ext {
            display: inline-flex; align-items: center;
            padding: 2px 7px; border-radius: 50px;
            background: #f3f4f6; font-size: .68rem; font-weight: 600;
            color: #6d7279; margin-top: 2px;
        }

        /* ── Data row: uploader ────────────────────────────── */
        .mda-uploader {
            width: 140px; flex-shrink: 0;
            display: flex; align-items: center; gap: 7px;
        }
        .mda-uploader-avatar {
            width: 24px; height: 24px; border-radius: 50%;
            object-fit: cover; flex-shrink: 0;
        }
        .mda-uploader-name {
            font-size: .8rem; color: #374151; font-weight: 500;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        /* ── Data row: tamanho ─────────────────────────────── */
        .mda-size {
            width: 80px; flex-shrink: 0; text-align: center;
            font-size: .8rem; color: #6d7279; font-weight: 500;
        }

        /* ── Data row: downloads ───────────────────────────── */
        .mda-downloads {
            width: 90px; flex-shrink: 0; text-align: center;
            display: flex; align-items: center; justify-content: center; gap: 4px;
            font-size: .8rem; font-weight: 600; color: #374151;
        }

        /* ── Data row: data ────────────────────────────────── */
        .mda-date {
            width: 90px; flex-shrink: 0; text-align: center;
            font-size: .78rem; color: #9ca3af;
        }
        .mda-date-time {
            display: block; font-size: .7rem; color: #c4c9d4;
        }

        /* ── Action buttons ────────────────────────────────── */
        .mda-action-copy:hover { background: #ede9fe; border-color: #c4b5fd; color: #5b21b6; }
        .mda-action-download:hover { background: #d1fae5; border-color: #6ee7b7; color: #059669; }

        /* ── Dropzone (modal upload) ───────────────────────── */
        .mda-dropzone {
            display: block; border: 2px dashed #e5e7eb; border-radius: 10px;
            padding: 32px 20px; text-align: center;
            cursor: pointer; background: #fafafa;
            transition: border-color .15s, background .15s;
        }
        .mda-dropzone:hover {
            border-color: #6366f1;
            background: rgba(99,102,241,.03);
        }
        .mda-dropzone-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: rgba(99,102,241,.1); color: #6366f1;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin: 0 auto 12px;
        }
        .mda-dropzone-title { font-size: .85rem; font-weight: 600; color: #374151; margin: 0 0 4px; }
        .mda-dropzone-desc { font-size: .75rem; color: #9ca3af; margin: 0; }

        /* ── File preview (modal upload) ───────────────────── */
        .mda-file-preview {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 10px; border-radius: 7px;
            background: #f9fafb; border: 1px solid #e9ecef;
            margin-bottom: 5px;
        }
        .mda-file-preview-name {
            flex: 1; font-size: .8rem; color: #374151;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .mda-file-preview-size {
            font-size: .72rem; color: #9ca3af; flex-shrink: 0;
        }
    </style>


    {{-- ================================================================ --}}
    {{-- SCRIPTS                                                           --}}
    {{-- ================================================================ --}}
    @push('scripts')
    <script>
    function copyMediaLink(url, btn) {
        navigator.clipboard.writeText(url).then(() => {
            var icon = btn.querySelector('i');
            icon.className = 'fa-solid fa-check';
            btn.style.background = '#d1fae5';
            btn.style.borderColor = '#6ee7b7';
            btn.style.color = '#059669';
            setTimeout(function() {
                icon.className = 'fa-solid fa-copy';
                btn.style.background = '';
                btn.style.borderColor = '';
                btn.style.color = '';
            }, 2000);
        });
    }

    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', ({ type, message }) => {
            var container = document.getElementById('media-toast-container');
            if (!container) return;
            var icons = { success: 'fa-circle-check', error: 'fa-circle-exclamation', info: 'fa-circle-info' };
            var toast = document.createElement('div');
            toast.className = 'mir-toast mir-toast-' + type;
            toast.innerHTML = '<i class="fa-solid ' + (icons[type] || icons.info) + ' mir-toast-icon"></i><span class="mir-toast-msg">' + message + '</span>';
            container.appendChild(toast);
            setTimeout(function() {
                toast.style.animation = 'mir-toast-out 200ms ease forwards';
                setTimeout(function() { toast.remove(); }, 210);
            }, 3500);
        });
    });
    </script>
    @endpush

</div>
