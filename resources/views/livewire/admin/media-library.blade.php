<div>

    {{-- ================================================================ --}}
    {{-- CABEÇALHO                                                         --}}
    {{-- ================================================================ --}}
    <div class="sdb-section" style="margin-bottom:20px;">
        <div class="sdb-header">
            <div>
                <div class="sdb-title">
                    <i class="fa fa-photo" style="color:#6366f1;margin-right:6px;"></i>
                    Biblioteca de Mídia
                </div>
                <div class="sdb-sub">Gerencie arquivos e copie links para usar nos posts</div>
            </div>
            <button class="mir-btn-primary-lg" wire:click="openUpload">
                <i class="fa fa-upload"></i> Enviar Arquivo
            </button>
        </div>
        <div wire:loading wire:target="saveUpload,delete" class="sdb-loading-bar"></div>
    </div>

    {{-- ================================================================ --}}
    {{-- FILTROS                                                           --}}
    {{-- ================================================================ --}}
    <div class="sdb-section" style="margin-bottom:20px;padding:14px 18px;">
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">

            {{-- Busca --}}
            <div style="flex:1;min-width:200px;position:relative;">
                <i class="fa fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);
                                               color:#9ca3af;font-size:.8rem;pointer-events:none;"></i>
                <input type="text"
                       wire:model.live.debounce.400ms="search"
                       class="mir-input"
                       style="padding-left:30px;"
                       placeholder="Buscar por nome...">
            </div>

            {{-- Filtro por extensão --}}
            <select wire:model.live="filterExt" class="mir-input" style="max-width:160px;">
                <option value="">Todos os tipos</option>
                @foreach($extensions as $ext)
                    <option value="{{ $ext }}">{{ strtoupper($ext) }}</option>
                @endforeach
            </select>

            @if($search || $filterExt)
                <button wire:click="$set('search','');$set('filterExt','')"
                        class="mir-btn-ghost" style="padding:8px 12px;font-size:.78rem;">
                    <i class="fa fa-times"></i> Limpar
                </button>
            @endif

        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- TABELA DE ARQUIVOS                                                --}}
    {{-- ================================================================ --}}
    <div class="sdb-section">

        @if($files->isEmpty())
            <div class="mir-empty-state">
                <div class="mir-empty-icon"><i class="fa fa-folder-open-o"></i></div>
                <h5 class="mir-empty-title">Nenhum arquivo encontrado</h5>
                <p class="mir-empty-desc">
                    {{ $search || $filterExt ? 'Tente outros filtros.' : 'Envie o primeiro arquivo para começar.' }}
                </p>
                @if(!$search && !$filterExt)
                    <button class="mir-btn-primary-lg" wire:click="openUpload">
                        <i class="fa fa-upload"></i> Enviar agora
                    </button>
                @endif
            </div>
        @else
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="border-bottom:2px solid #f0f0f0;">
                            <th style="padding:10px 16px;text-align:left;font-size:.72rem;
                                       font-weight:700;color:#9ca3af;text-transform:uppercase;
                                       letter-spacing:.05em;">Arquivo</th>
                            <th style="padding:10px 16px;text-align:left;font-size:.72rem;
                                       font-weight:700;color:#9ca3af;text-transform:uppercase;
                                       letter-spacing:.05em;">Enviado por</th>
                            <th style="padding:10px 16px;text-align:center;font-size:.72rem;
                                       font-weight:700;color:#9ca3af;text-transform:uppercase;
                                       letter-spacing:.05em;">Tamanho</th>
                            <th style="padding:10px 16px;text-align:center;font-size:.72rem;
                                       font-weight:700;color:#9ca3af;text-transform:uppercase;
                                       letter-spacing:.05em;">Downloads</th>
                            <th style="padding:10px 16px;text-align:center;font-size:.72rem;
                                       font-weight:700;color:#9ca3af;text-transform:uppercase;
                                       letter-spacing:.05em;">Data</th>
                            <th style="padding:10px 16px;text-align:right;font-size:.72rem;
                                       font-weight:700;color:#9ca3af;text-transform:uppercase;
                                       letter-spacing:.05em;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                            <tr style="border-bottom:1px solid #f5f5f5;transition:background .12s;"
                                onmouseover="this.style.background='#f9fafb'"
                                onmouseout="this.style.background=''">

                                {{-- Arquivo --}}
                                <td style="padding:12px 16px;">
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <span style="width:36px;height:36px;border-radius:9px;flex-shrink:0;
                                                     display:flex;align-items:center;justify-content:center;
                                                     font-size:.9rem;
                                                     background:{{ $file->icon_color }}1a;
                                                     color:{{ $file->icon_color }};">
                                            <i class="fa {{ $file->icon }}"></i>
                                        </span>
                                        <div style="min-width:0;">
                                            <div style="font-size:.83rem;font-weight:600;color:#1a1d23;
                                                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
                                                        max-width:280px;"
                                                 title="{{ $file->original_name }}">
                                                {{ $file->original_name }}
                                            </div>
                                            <div style="font-size:.7rem;color:#9ca3af;margin-top:2px;">
                                                <span style="display:inline-flex;align-items:center;
                                                             padding:2px 7px;border-radius:50px;
                                                             background:#f3f4f6;font-weight:600;">
                                                    {{ strtoupper($file->extension) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Enviado por --}}
                                <td style="padding:12px 16px;">
                                    <div style="display:flex;align-items:center;gap:7px;">
                                        <img src="{{ $file->uploader->picture }}"
                                             style="width:24px;height:24px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                        <span style="font-size:.8rem;color:#374151;font-weight:500;">
                                            {{ $file->uploader->name }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Tamanho --}}
                                <td style="padding:12px 16px;text-align:center;">
                                    <span style="font-size:.8rem;color:#6d7279;font-weight:500;">
                                        {{ $file->formatted_size }}
                                    </span>
                                </td>

                                {{-- Downloads --}}
                                <td style="padding:12px 16px;text-align:center;">
                                    <span style="display:inline-flex;align-items:center;gap:4px;
                                                 font-size:.8rem;font-weight:600;color:#374151;">
                                        <i class="fa fa-download" style="color:#6366f1;font-size:.7rem;"></i>
                                        {{ number_format($file->downloads) }}
                                    </span>
                                </td>

                                {{-- Data --}}
                                <td style="padding:12px 16px;text-align:center;">
                                    <span style="font-size:.78rem;color:#9ca3af;">
                                        {{ $file->created_at->format('d/m/Y') }}
                                    </span>
                                    <span style="display:block;font-size:.7rem;color:#c4c9d4;">
                                        {{ $file->created_at->format('H:i') }}
                                    </span>
                                </td>

                                {{-- Ações --}}
                                <td style="padding:12px 16px;">
                                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;">

                                        {{-- Copiar link --}}
                                        <button type="button"
                                                onclick="copyMediaLink('{{ $file->download_url }}', this)"
                                                style="display:inline-flex;align-items:center;gap:5px;
                                                       padding:5px 10px;border-radius:6px;
                                                       border:1px solid #e5e7eb;background:#fff;
                                                       color:#374151;font-size:.72rem;font-weight:600;
                                                       cursor:pointer;transition:.15s;white-space:nowrap;"
                                                onmouseover="this.style.borderColor='#6366f1';this.style.color='#6366f1';this.style.background='rgba(99,102,241,.05)'"
                                                onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#374151';this.style.background='#fff'">
                                            <i class="fa fa-copy"></i> Copiar link
                                        </button>

                                        {{-- Download --}}
                                        <a href="{{ $file->download_url }}"
                                           target="_blank"
                                           style="display:inline-flex;align-items:center;gap:5px;
                                                  padding:5px 10px;border-radius:6px;
                                                  border:1px solid #e5e7eb;background:#fff;
                                                  color:#374151;font-size:.72rem;font-weight:600;
                                                  text-decoration:none;transition:.15s;white-space:nowrap;"
                                           onmouseover="this.style.borderColor='#10b981';this.style.color='#059669';this.style.background='rgba(16,185,129,.05)'"
                                           onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#374151';this.style.background='#fff'">
                                            <i class="fa fa-download"></i>
                                        </a>

                                        {{-- Deletar --}}
                                        <button type="button"
                                                wire:click="confirmDelete({{ $file->id }})"
                                                class="mir-action-btn mir-action-delete"
                                                title="Remover arquivo">
                                            <i class="fa fa-trash" style="font-size:.75rem;"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            <div style="padding:14px 18px;border-top:1px solid #f0f0f0;">
                {{ $files->links() }}
            </div>
        @endif

    </div>


    {{-- ================================================================ --}}
    {{-- MODAL: UPLOAD                                                     --}}
    {{-- ================================================================ --}}
    @if($showUploadModal)
        <div class="mir-modal-overlay" wire:key="modal-upload">
            <div class="mir-modal-dialog">
                <div class="mir-modal-content">

                    <div class="mir-modal-header">
                        <div class="mir-modal-title-wrap">
                            <span class="mir-modal-icon mir-modal-icon-add">
                                <i class="fa fa-upload"></i>
                            </span>
                            <div>
                                <div class="mir-modal-title-text">Enviar Arquivos</div>
                                <div class="mir-modal-subtitle">Selecione um ou mais arquivos (máx. 200MB cada)</div>
                            </div>
                        </div>
                        <button type="button" class="mir-modal-close"
                                wire:click="$set('showUploadModal', false)">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>

                    <div class="mir-modal-body">

                        {{-- Drop zone --}}
                        <label for="media-file-input"
                               style="display:block;border:2px dashed #e5e7eb;border-radius:10px;
                                      padding:32px 20px;text-align:center;transition:.2s;
                                      cursor:pointer;background:#fafafa;position:relative;"
                               onmouseover="this.style.borderColor='#6366f1';this.style.background='rgba(99,102,241,.03)'"
                               onmouseout="this.style.borderColor='#e5e7eb';this.style.background='#fafafa'">
                            <div style="width:48px;height:48px;border-radius:12px;
                                        background:rgba(99,102,241,.1);color:#6366f1;
                                        display:flex;align-items:center;justify-content:center;
                                        font-size:1.3rem;margin:0 auto 12px;">
                                <i class="fa fa-cloud-upload"></i>
                            </div>
                            <p style="font-size:.85rem;font-weight:600;color:#374151;margin:0 0 4px;">
                                Clique para selecionar ou arraste aqui
                            </p>
                            <p style="font-size:.75rem;color:#9ca3af;margin:0;">
                                Imagens, documentos, áudio, vídeo, compactados, .amxx, .sma, .bsp...
                            </p>
                            <input id="media-file-input"
                                   type="file"
                                   wire:model="uploadedFiles"
                                   multiple
                                   style="display:none;">
                        </label>

                        {{-- Loading --}}
                        <div wire:loading wire:target="uploadedFiles"
                             style="text-align:center;padding:10px 0;font-size:.82rem;color:#6366f1;">
                            <i class="fa fa-spinner fa-spin"></i> Processando arquivos...
                        </div>

                        {{-- Preview dos arquivos selecionados --}}
                        @if(count($uploadedFiles) > 0)
                            <div style="margin-top:14px;">
                                <div style="font-size:.75rem;font-weight:700;color:#9ca3af;
                                            text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">
                                    {{ count($uploadedFiles) }} arquivo(s) selecionado(s)
                                </div>
                                @foreach($uploadedFiles as $f)
                                    <div style="display:flex;align-items:center;gap:8px;
                                                padding:8px 10px;border-radius:7px;
                                                background:#f9fafb;border:1px solid #e9ecef;
                                                margin-bottom:5px;">
                                        <i class="fa fa-file-o" style="color:#9ca3af;font-size:.85rem;flex-shrink:0;"></i>
                                        <span style="flex:1;font-size:.8rem;color:#374151;
                                                     overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $f->getClientOriginalName() }}
                                        </span>
                                        <span style="font-size:.72rem;color:#9ca3af;flex-shrink:0;">
                                            {{ number_format($f->getSize() / 1024 / 1024, 2) }} MB
                                        </span>
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
                                wire:click="$set('showUploadModal', false)">
                            Cancelar
                        </button>
                        <button type="button"
                                class="mir-btn-primary-lg"
                                wire:click="saveUpload"
                                wire:loading.attr="disabled"
                                wire:target="saveUpload"
                                {{ count($uploadedFiles) === 0 ? 'disabled' : '' }}
                                style="{{ count($uploadedFiles) === 0 ? 'opacity:.5;cursor:not-allowed;' : '' }}">
                            <span wire:loading wire:target="saveUpload">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                            <i class="fa fa-upload" wire:loading.remove wire:target="saveUpload"></i>
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
            <div class="mir-modal-dialog-sm">
                <div class="mir-modal-content">

                    <div class="mir-modal-header">
                        <div class="mir-modal-title-wrap">
                            <span class="mir-modal-icon mir-modal-icon-delete">
                                <i class="fa fa-trash"></i>
                            </span>
                            <div>
                                <div class="mir-modal-title-text">Remover Arquivo</div>
                                <div class="mir-modal-subtitle">Esta ação não pode ser desfeita</div>
                            </div>
                        </div>
                        <button type="button" class="mir-modal-close" wire:click="cancelDelete">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>

                    <div class="mir-modal-body">
                        @php $toDelete = $files->firstWhere('id', $confirmDeleteId); @endphp
                        <p style="color:#6d7279;font-size:.88rem;line-height:1.6;margin:0;">
                            Tem certeza que deseja remover
                            <strong style="color:#1a1d23;">
                                {{ $toDelete?->original_name ?? 'este arquivo' }}
                            </strong>?
                            <br>
                            <span style="font-size:.78rem;color:#9ca3af;margin-top:4px;display:block;">
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
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                            <i class="fa fa-trash" wire:loading.remove wire:target="delete({{ $confirmDeleteId }})"></i>
                            Sim, remover
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif


    {{-- ================================================================ --}}
    {{-- TOAST CONTAINER (reutiliza do sidebar)                            --}}
    {{-- ================================================================ --}}
    <div id="media-toast-container"
         style="position:fixed;bottom:24px;right:24px;z-index:9999;
                display:flex;flex-direction:column;gap:10px;"
         aria-live="polite"></div>


    @push('scripts')
    <script>
    // ── Copiar link ───────────────────────────────────────────────────────────
    function copyMediaLink(url, btn) {
        navigator.clipboard.writeText(url).then(() => {
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fa fa-check"></i> Copiado!';
            btn.style.borderColor = '#10b981';
            btn.style.color = '#059669';
            btn.style.background = 'rgba(16,185,129,.06)';
            setTimeout(() => {
                btn.innerHTML = original;
                btn.style.borderColor = '#e5e7eb';
                btn.style.color = '#374151';
                btn.style.background = '#fff';
            }, 2000);
        });
    }

    // ── Toast via evento Livewire ─────────────────────────────────────────────
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', ({ type, message }) => {
            const container = document.getElementById('media-toast-container')
                           || document.getElementById('sdb-toast-container');
            if (!container) return;

            const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle' };
            const toast = document.createElement('div');
            toast.className = `mir-toast mir-toast-${type}`;
            toast.innerHTML = `<i class="fa ${icons[type]||icons.info} mir-toast-icon"></i><span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'mir-toast-out 200ms ease forwards';
                setTimeout(() => toast.remove(), 210);
            }, 3500);
        });
    });
    </script>
    @endpush

</div>