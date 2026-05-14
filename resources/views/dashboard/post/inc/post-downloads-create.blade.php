{{--
    Downloads em JS puro — sem Livewire, sem race condition com flash de sessão.
    Envia os botões como campos hidden junto com o form normal do post.
    Usar apenas na página de CRIAR post. Na edição continua o Livewire.
--}}

<div id="pd-wrap">

    {{-- Trigger --}}
    <button type="button" id="pd-trigger"
            style="display:flex;align-items:center;justify-content:space-between;
                   width:100%;padding:9px 12px;border-radius:8px;
                   background:#fafafa;border:1.5px solid #e5e7eb;
                   color:#374151;font-size:.82rem;font-weight:600;
                   cursor:pointer;transition:all .15s;"
            onmouseover="this.style.borderColor='#6366f1';this.style.color='#6366f1';this.style.background='rgba(99,102,241,.04)'"
            onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#374151';this.style.background='#fafafa'">
        <span style="display:flex;align-items:center;gap:7px;">
            <span style="width:24px;height:24px;border-radius:6px;
                         background:rgba(16,185,129,.12);color:#059669;
                         display:flex;align-items:center;justify-content:center;
                         font-size:.75rem;flex-shrink:0;">
                <i class="fa fa-download"></i>
            </span>
            Gerenciar Downloads
        </span>
        <span style="display:flex;align-items:center;gap:6px;">
            <span id="pd-count-badge" style="display:none;
                         min-width:18px;height:18px;padding:0 5px;border-radius:50px;
                         background:#6366f1;color:#fff;font-size:.62rem;font-weight:700;
                         align-items:center;justify-content:center;">0</span>
            <i class="fa fa-chevron-right" style="font-size:.62rem;color:#9ca3af;"></i>
        </span>
    </button>

    {{-- Hidden inputs gerados por JS --}}
    <div id="pd-hidden-inputs"></div>

    {{-- Modal --}}
    <div id="pd-modal-overlay" style="display:none;position:fixed;inset:0;
         background:rgba(0,0,0,.45);z-index:1070;
         display:none;align-items:center;justify-content:center;padding:12px;">
        <div style="width:100%;max-width:560px;animation:mir-modal-in .2s ease;">
            <div class="mir-modal-content">

                {{-- Header --}}
                <div class="mir-modal-header">
                    <div class="mir-modal-title-wrap">
                        <span class="mir-modal-icon" style="background:rgba(16,185,129,.12);color:#059669;">
                            <i class="fa fa-download"></i>
                        </span>
                        <div>
                            <div class="mir-modal-title-text">Botões de Download</div>
                            <div class="mir-modal-subtitle">Configure os links de download deste post</div>
                        </div>
                    </div>
                    <button type="button" class="mir-modal-close" id="pd-close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="mir-modal-body" style="padding:14px 18px;">
                    <div id="pd-list"></div>

                    {{-- Adicionar --}}
                    <button type="button" id="pd-add"
                            class="mir-btn-ghost"
                            style="width:100%;justify-content:center;
                                   border-style:dashed;margin-top:4px;">
                        <i class="fa fa-plus" style="font-size:.72rem;"></i>
                        Adicionar botão de download
                        <span id="pd-add-count" style="color:#9ca3af;font-size:.7rem;margin-left:2px;display:none;">(0/10)</span>
                    </button>

                    {{-- Preview --}}
                    <div id="pd-preview" style="display:none;margin-top:12px;padding:12px 14px;
                         border-radius:8px;background:#fafafa;border:1px dashed #e9ecef;">
                        <div style="font-size:.67rem;font-weight:700;color:#9ca3af;
                                    text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">
                            Preview
                        </div>
                        <div id="pd-preview-inner" style="display:flex;flex-wrap:wrap;gap:7px;"></div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="mir-modal-footer">
                    <button type="button" class="mir-btn-ghost" id="pd-cancel">Cancelar</button>
                    <button type="button" class="mir-btn-primary-lg" id="pd-save">
                        <i class="fa fa-save"></i>
                        <span id="pd-save-label">Fechar</span>
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
(function () {
    // ── Estado ────────────────────────────────────────────────────────────────
    let buttons   = [];
    let openIndex = null;
    const MAX     = 10;

    // ── Elementos ─────────────────────────────────────────────────────────────
    const overlay     = document.getElementById('pd-modal-overlay');
    const list        = document.getElementById('pd-list');
    const addBtn      = document.getElementById('pd-add');
    const addCount    = document.getElementById('pd-add-count');
    const preview     = document.getElementById('pd-preview');
    const previewInner= document.getElementById('pd-preview-inner');
    const hiddenWrap  = document.getElementById('pd-hidden-inputs');
    const badge       = document.getElementById('pd-count-badge');
    const saveLabel   = document.getElementById('pd-save-label');

    // ── Abrir / Fechar modal ──────────────────────────────────────────────────
    document.getElementById('pd-trigger').addEventListener('click', openModal);
    document.getElementById('pd-close').addEventListener('click', closeModal);
    document.getElementById('pd-cancel').addEventListener('click', closeModal);
    document.getElementById('pd-save').addEventListener('click', saveAndClose);

    function openModal() {
        overlay.style.display = 'flex';
        render();
    }

    function closeModal() {
        overlay.style.display = 'none';
    }

    function saveAndClose() {
        syncHiddens();
        closeModal();
    }

    // ── Adicionar botão ───────────────────────────────────────────────────────
    addBtn.addEventListener('click', () => {
        if (buttons.length >= MAX) return;
        buttons.push({ label: '', url: '', position: 'block', file: null, fileName: '' });
        openIndex = buttons.length - 1;
        render();
    });

    // ── Render lista ─────────────────────────────────────────────────────────
    function render() {
        list.innerHTML = '';

        if (buttons.length === 0) {
            list.innerHTML = `
                <div style="display:flex;flex-direction:column;align-items:center;
                            padding:28px 20px;text-align:center;">
                    <div style="width:44px;height:44px;border-radius:12px;
                                background:#f3f4f6;color:#9ca3af;
                                display:flex;align-items:center;justify-content:center;
                                font-size:1.1rem;margin-bottom:10px;">
                        <i class="fa fa-download"></i>
                    </div>
                    <p style="font-size:.82rem;color:#9ca3af;margin:0 0 4px;">Nenhum botão configurado ainda.</p>
                    <p style="font-size:.75rem;color:#c4c9d4;margin:0;">Adicione links para Google Drive, Mega, etc.</p>
                </div>`;
        }

        buttons.forEach((btn, i) => {
            const isOpen = openIndex === i;
            const card = document.createElement('div');
            card.style.cssText = `border:1.5px solid ${isOpen ? '#6366f1' : '#e9ecef'};
                border-radius:10px;overflow:hidden;margin-bottom:8px;transition:border-color .2s;
                background:${isOpen ? '#fff' : '#fafafa'};`;

            // ── Header accordion ──────────────────────────────────────────────
            const header = document.createElement('div');
            header.style.cssText = 'display:flex;align-items:center;gap:10px;padding:10px 12px;cursor:pointer;user-select:none;';
            header.addEventListener('click', () => {
                openIndex = openIndex === i ? null : i;
                render();
            });

            const numBadge = `<span style="width:22px;height:22px;border-radius:50%;flex-shrink:0;
                display:flex;align-items:center;justify-content:center;
                font-size:.65rem;font-weight:800;
                background:${isOpen ? '#6366f1' : '#e5e7eb'};
                color:${isOpen ? '#fff' : '#6d7279'};transition:.2s;">${i + 1}</span>`;

            const labelText = btn.label
                ? `${btn.label}<span style="font-size:.7rem;font-weight:400;color:#9ca3af;margin-left:4px;">
                    · ${btn.url ? 'URL' : (btn.fileName ? 'Arquivo' : '')}</span>`
                : `<span style="color:#9ca3af;">Botão ${i + 1} — preencha o nome</span>`;

            header.innerHTML = `
                ${numBadge}
                <span style="flex:1;min-width:0;font-size:.82rem;font-weight:600;
                             color:${isOpen ? '#1a1d23' : '#6d7279'};
                             white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    ${labelText}
                </span>
                <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                    <button type="button" data-remove="${i}"
                            style="width:24px;height:24px;border-radius:5px;
                                   border:1px solid #fca5a5;background:#fee2e2;
                                   color:#b91c1c;cursor:pointer;font-size:.65rem;
                                   display:flex;align-items:center;justify-content:center;transition:.15s;"
                            onmouseover="this.style.background='#fecaca'"
                            onmouseout="this.style.background='#fee2e2'">
                        <i class="fa fa-trash"></i>
                    </button>
                    <i class="fa fa-chevron-${isOpen ? 'up' : 'down'}"
                       style="font-size:.62rem;color:${isOpen ? '#6366f1' : '#9ca3af'};transition:.2s;"></i>
                </div>`;

            header.querySelector('[data-remove]').addEventListener('click', e => {
                e.stopPropagation();
                if (!confirm(`Remover '${btn.label || 'este botão'}'?`)) return;
                buttons.splice(i, 1);
                if (openIndex === i) openIndex = buttons.length > 0 ? Math.max(0, i - 1) : null;
                else if (openIndex !== null && openIndex > i) openIndex--;
                render();
            });

            card.appendChild(header);

            // ── Body accordion ────────────────────────────────────────────────
            if (isOpen) {
                const body = document.createElement('div');
                body.style.cssText = 'padding:0 12px 14px;border-top:1px solid #e9ecef;';

                body.innerHTML = `
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:12px;margin-bottom:10px;">
                        <div>
                            <label class="mir-label">Nome <span class="mir-required">*</span></label>
                            <input type="text" class="mir-input pd-label" data-idx="${i}"
                                   placeholder="Ex: Google Drive, Mega..."
                                   value="${escHtml(btn.label)}">
                        </div>
                        <div>
                            <label class="mir-label">URL</label>
                            <input type="text" class="mir-input pd-url" data-idx="${i}"
                                   placeholder="https://..."
                                   value="${escHtml(btn.url)}">
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        <div>
                            <label class="mir-label">Arquivo</label>
                            ${btn.fileName ? `
                            <div style="display:flex;align-items:center;gap:6px;padding:5px 9px;
                                        border-radius:7px;background:#f0fdf4;border:1px solid #bbf7d0;
                                        font-size:.72rem;color:#065f46;margin-bottom:6px;min-width:0;">
                                <i class="fa fa-file-archive-o" style="flex-shrink:0;"></i>
                                <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;min-width:0;max-width:120px;">
                                    ${escHtml(btn.fileName)}
                                </span>
                                <button type="button" class="pd-remove-file" data-idx="${i}"
                                        style="background:transparent;border:none;color:#9ca3af;cursor:pointer;padding:0 2px;flex-shrink:0;"
                                        onmouseover="this.style.color='#ef4444'"
                                        onmouseout="this.style.color='#9ca3af'">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>` : ''}
                            <input type="file" class="mir-input pd-file" data-idx="${i}"
                                   style="padding:5px 8px;cursor:pointer;font-size:.72rem;width:100%;">
                            <div class="mir-hint">URL tem prioridade sobre arquivo.</div>
                        </div>
                        <div>
                            <label class="mir-label">Posição</label>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:5px;">
                                ${[['left','← Esq'],['center','↔ Centro'],['right','→ Dir'],['block','↕ Bloco']].map(([val,lbl]) => `
                                <label style="display:flex;align-items:center;gap:5px;padding:6px 8px;border-radius:7px;cursor:pointer;
                                             border:1.5px solid ${btn.position===val ? '#6366f1' : '#e5e7eb'};
                                             background:${btn.position===val ? 'rgba(99,102,241,.07)' : '#fff'};
                                             font-size:.72rem;font-weight:600;
                                             color:${btn.position===val ? '#4338ca' : '#6d7279'};transition:.15s;">
                                    <input type="radio" class="pd-pos" data-idx="${i}" value="${val}"
                                           style="display:none;" ${btn.position===val ? 'checked' : ''}>
                                    ${lbl}
                                </label>`).join('')}
                            </div>
                        </div>
                    </div>`;

                // Eventos
                body.querySelector('.pd-label').addEventListener('input', e => {
                    buttons[i].label = e.target.value;
                    renderBadgeAndPreview();
                });
                body.querySelector('.pd-url').addEventListener('input', e => {
                    buttons[i].url = e.target.value;
                    renderBadgeAndPreview();
                });
                body.querySelector('.pd-file').addEventListener('change', e => {
                    const file = e.target.files[0];
                    if (!file) return;
                    buttons[i].file = file;
                    buttons[i].fileName = file.name;
                    render();
                });
                body.querySelectorAll('.pd-pos').forEach(radio => {
                    radio.addEventListener('change', e => {
                        buttons[i].position = e.target.value;
                        render();
                    });
                    radio.closest('label').addEventListener('click', () => {
                        buttons[i].position = radio.value;
                        render();
                    });
                });
                const removeFile = body.querySelector('.pd-remove-file');
                if (removeFile) {
                    removeFile.addEventListener('click', () => {
                        buttons[i].file = null;
                        buttons[i].fileName = '';
                        render();
                    });
                }

                card.appendChild(body);
            }

            list.appendChild(card);
        });

        // Atualiza add button
        if (buttons.length >= MAX) {
            addBtn.style.display = 'none';
        } else {
            addBtn.style.display = '';
            addCount.style.display = buttons.length > 0 ? 'inline' : 'none';
            addCount.textContent = `(${buttons.length}/${MAX})`;
        }

        renderBadgeAndPreview();
        saveLabel.textContent = buttons.length > 0 ? 'Salvar Downloads' : 'Fechar';
    }

    // ── Badge no trigger e preview ────────────────────────────────────────────
    function renderBadgeAndPreview() {
        const count = buttons.filter(b => b.label).length;
        if (count > 0) {
            badge.style.display = 'inline-flex';
            badge.textContent = count;
        } else {
            badge.style.display = 'none';
        }

        const labeled = buttons.filter(b => b.label);
        if (labeled.length > 0) {
            preview.style.display = 'block';
            previewInner.innerHTML = labeled.map(btn => {
                const s = `display:inline-flex;align-items:center;gap:5px;
                           padding:6px 13px;border-radius:7px;
                           background:linear-gradient(135deg,#6366f1,#4f46e5);
                           color:#fff;font-size:.74rem;font-weight:600;
                           box-shadow:0 2px 6px rgba(99,102,241,.25);`;
                const inner = `<i class="fa fa-download"></i> ${escHtml(btn.label)}`;
                if (btn.position === 'block')  return `<div style="width:100%;"><span style="${s}">${inner}</span></div>`;
                if (btn.position === 'center') return `<div style="width:100%;display:flex;justify-content:center;"><span style="${s}">${inner}</span></div>`;
                if (btn.position === 'right')  return `<div style="width:100%;display:flex;justify-content:flex-end;"><span style="${s}">${inner}</span></div>`;
                return `<span style="${s}">${inner}</span>`;
            }).join('');
        } else {
            preview.style.display = 'none';
        }
    }

    // ── Sync hidden inputs (enviados junto com o form) ────────────────────────
    function syncHiddens() {
        hiddenWrap.innerHTML = '';
        buttons.forEach((btn, i) => {
            if (!btn.label) return;
            addHidden(`downloads[${i}][label]`, btn.label);
            addHidden(`downloads[${i}][url]`, btn.url || '');
            addHidden(`downloads[${i}][position]`, btn.position || 'block');
            // arquivo é enviado via input file real — clonamos para dentro do form
            if (btn.file) {
                const dt = new DataTransfer();
                dt.items.add(btn.file);
                const fi = document.createElement('input');
                fi.type = 'file';
                fi.name = `downloads[${i}][file]`;
                fi.style.display = 'none';
                fi.files = dt.files;
                hiddenWrap.appendChild(fi);
            }
        });
    }

    function addHidden(name, value) {
        const el = document.createElement('input');
        el.type  = 'hidden';
        el.name  = name;
        el.value = value;
        hiddenWrap.appendChild(el);
    }

    function escHtml(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Sincroniza antes do submit do form
    const postForm = document.getElementById('post-form');
    if (postForm) {
        postForm.addEventListener('submit', syncHiddens, true);
    }

    // Init
    render();
    overlay.style.display = 'none';
})();
</script>
@endpush