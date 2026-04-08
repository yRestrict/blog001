@push('stylesheets')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <style>
        /* ── Custom Category Select ─────────────────────────────────────── */
        .cs-wrap { position: relative; user-select: none; }
        .cs-trigger {
            display: flex; align-items: center; justify-content: space-between; gap: 8px;
            padding: 0 12px; height: 42px; background: #fff;
            border: 1px solid #d1d5db; border-radius: 6px;
            cursor: pointer; transition: border-color .15s, box-shadow .15s;
            font-size: 14px; color: #111827;
        }
        .cs-trigger:hover { border-color: #9ca3af; }
        .cs-trigger.open { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
        .cs-trigger-text { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .cs-trigger-text.placeholder { color: #9ca3af; }
        .cs-arrow { width: 16px; height: 16px; flex-shrink: 0; transition: transform .2s; color: #6b7280; }
        .cs-trigger.open .cs-arrow { transform: rotate(180deg); }
        .cs-dropdown {
            position: absolute; top: calc(100% + 4px); left: 0; right: 0;
            background: #fff; border: 1px solid #e5e7eb; border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,.12); z-index: 9999;
            overflow: hidden; display: none; animation: cs-fade .12s ease;
        }
        .cs-dropdown.open { display: block; }
        @keyframes cs-fade { from { opacity:0; transform:translateY(-4px); } to { opacity:1; transform:translateY(0); } }
        .cs-search-wrap { padding: 8px; border-bottom: 1px solid #f3f4f6; position: relative; }
        .cs-search-icon { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: #9ca3af; pointer-events: none; }
        .cs-search {
            width: 100%; height: 34px; padding: 0 10px 0 32px;
            border: 1px solid #e5e7eb; border-radius: 6px;
            font-size: 13px; background: #f9fafb; color: #111827; outline: none;
            transition: border-color .15s;
        }
        .cs-search:focus { border-color: #3b82f6; background: #fff; }
        .cs-list { max-height: 220px; overflow-y: auto; padding: 4px; }
        .cs-list::-webkit-scrollbar { width: 4px; }
        .cs-list::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
        .cs-group-label {
            padding: 8px 10px 4px; font-size: 11px; font-weight: 600;
            letter-spacing: .07em; text-transform: uppercase; color: #9ca3af;
        }
        .cs-option {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 10px; border-radius: 6px; font-size: 14px;
            cursor: pointer; color: #374151; transition: background .1s;
        }
        .cs-option:hover { background: #f3f4f6; }
        .cs-option.selected { background: #eff6ff; color: #1d4ed8; font-weight: 500; }
        .cs-check { width: 14px; height: 14px; opacity: 0; color: #3b82f6; flex-shrink: 0; }
        .cs-option.selected .cs-check { opacity: 1; }
        .cs-empty { padding: 20px; text-align: center; font-size: 13px; color: #9ca3af; }
        .cs-error .cs-trigger { border-color: #ef4444 !important; }
        .invalid-feedback { display: block; font-size: .875em; color: #dc3545; margin-top: 4px; }

        /* ── Tag Input ──────────────────────────────────────────────────── */
        .ti-wrap {
            min-height: 42px; display: flex; flex-wrap: wrap; align-items: center;
            gap: 6px; padding: 6px 10px; background: #fff;
            border: 1px solid #d1d5db; border-radius: 6px;
            cursor: text; transition: border-color .15s, box-shadow .15s;
        }
        .ti-wrap:focus-within { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
        .ti-tag {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 8px 3px 10px; background: #eff6ff; color: #1d4ed8;
            border-radius: 20px; font-size: 12px; font-weight: 500;
            letter-spacing: .02em; animation: tag-pop .15s ease;
        }
        @keyframes tag-pop { from { opacity:0; transform:scale(.85); } to { opacity:1; transform:scale(1); } }
        .ti-tag-remove {
            display: flex; align-items: center; justify-content: center;
            width: 14px; height: 14px; border-radius: 50%;
            background: rgba(29,78,216,.15); cursor: pointer;
            transition: background .1s; color: #1d4ed8; font-size: 10px; line-height: 1;
        }
        .ti-tag-remove:hover { background: rgba(29,78,216,.35); }
        .ti-input {
            flex: 1; min-width: 80px; border: none; outline: none;
            background: transparent; font-size: 14px; color: #111827;
            font-family: inherit; text-transform: uppercase;
        }
        .ti-input::placeholder { color: #9ca3af; text-transform: none; }
        .ti-suggestions {
            position: absolute; top: calc(100% + 4px); left: 0; right: 0;
            background: #fff; border: 1px solid #e5e7eb; border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,.1); z-index: 9999;
            overflow: hidden; display: none;
        }
        .ti-suggestion {
            padding: 9px 12px; font-size: 14px; cursor: pointer;
            color: #374151; transition: background .1s;
        }
        .ti-suggestion:hover, .ti-suggestion.active { background: #f3f4f6; }
        #quill-editor         { font-size: 15px; background: #fff; }
        #quill-editor .ql-editor { min-height: 420px; }
        .ql-toolbar.ql-snow   { border-radius: 4px 4px 0 0; }
        .ql-container.ql-snow { border-radius: 0 0 4px 4px; }
        .ql-editor img        { cursor: pointer; max-width: 100%; }
        .ql-editor iframe     { width: 100%; max-width: 100%; min-height: 300px; }

        /* Select de linguagem: escondido por padrão, aparece ao passar o mouse */
        #quill-editor .ql-code-block-container {
            position: relative;
        }
        #quill-editor .ql-code-block-container .ql-ui {
            position: absolute;
            top: 6px;
            right: 6px;
            opacity: 0;
            transition: opacity .2s;
            z-index: 10;
            font-size: 12px;
            padding: 2px 4px;
            border-radius: 4px;
        }
        #quill-editor .ql-code-block-container:hover .ql-ui {
            opacity: 1;
        }
    </style>
@endpush

@push('scripts')
{{-- highlight.js DEVE vir antes do Quill --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

{{-- Quill 2.0 --}}
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

{{-- quill-resize-image — Quill 2.0 compatível, expõe window.QuillResizeImage --}}
<script src="https://cdn.jsdelivr.net/gh/hunghg255/quill-resize-module/dist/quill-resize-image.min.js"></script>

<script>
// ── hljs: configurar linguagens mas NÃO rodar auto-highlight na página toda ──
window.hljs.configure({
    languages: ['php', 'javascript', 'html', 'css', 'bash', 'xml', 'json', 'python', 'sql', 'typescript'],
});

// ── Registra módulos ─────────────────────────────────────────────────────────
Quill.register('modules/imageResize', window.QuillResizeImage);



// ── Instancia o Quill com syntax highlighting via hljs ──────────────────────
const quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: {
        toolbar: {
            container: [
                [{ header: [1,2,3,4,5,6,false] }],
                [{ font: [] }],
                ['bold','italic','underline','strike'],
                [{ color: [] }, { background: [] }],
                [{ align: [] }],
                [{ list: 'ordered' }, { list: 'bullet' }],
                [{ indent: '-1' }, { indent: '+1' }],
                ['blockquote','code-block', 'code'],
                ['link','image','video'],
                ['clean'],
            ],
            handlers: { image: imageHandler, video: videoHandler, 'code-block': codeBlockHandler,},
        },
        syntax: true,
        imageResize: {},
    },
});

function codeBlockHandler() {
    const wasActive = !!quill.getFormat()['code-block'];
    quill.format('code-block', !wasActive);

    if (!wasActive) {
        setTimeout(() => {
            quill.root.querySelectorAll('.ql-code-block-container').forEach(container => {
                const select = container.querySelector('select.ql-ui');
                if (select && !select.value) {
                    select.value = 'php';
                    select.dispatchEvent(new Event('change'));
                }
            });
        }, 50);
    }
}

// ── Upload de imagem para o servidor ──────────────────────────────────────────
function imageHandler() {
    const input  = document.createElement('input');
    input.type   = 'file';
    input.accept = 'image/*';
    input.click();

    input.onchange = async () => {
        const file = input.files[0];
        if (!file) return;

        const fd = new FormData();
        fd.append('image', file);
        fd.append('_token', '{{ csrf_token() }}');

        try {
            const res  = await fetch('{{ route("admin.posts.upload-image") }}', { method: 'POST', body: fd });
            const data = await res.json();
            if (data.url) {
                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', data.url);
                quill.setSelection(range.index + 1);
            }
        } catch { alert('Erro ao fazer upload da imagem.'); }
    };
}

// ── YouTube embed com modal ───────────────────────────────────────────────────
function videoHandler() {
    document.getElementById('yt-url-input').value    = '';
    document.getElementById('yt-width-input').value  = '100%';
    document.getElementById('yt-height-input').value = '400px';
    $('#quill-video-modal').modal('show');
}

const ytApplyBtn = document.getElementById('yt-apply-btn');
if (ytApplyBtn) ytApplyBtn.addEventListener('click', function () {
    const url    = document.getElementById('yt-url-input').value.trim();
    const width  = document.getElementById('yt-width-input').value.trim()  || '100%';
    const height = document.getElementById('yt-height-input').value.trim() || '400px';

    if (!url) { alert('Informe o link do YouTube.'); return; }

    const embedUrl = toYoutubeEmbed(url);
    if (!embedUrl) { alert('Link do YouTube inválido.'); return; }

    const range = quill.getSelection(true);
    quill.insertEmbed(range.index, 'video', embedUrl);

    setTimeout(() => {
        const iframe = quill.root.querySelector(`iframe[src="${embedUrl}"]`);
        if (iframe) {
            iframe.style.width  = width;
            iframe.style.height = height;
        }
    }, 80);

    $('#quill-video-modal').modal('hide');
});

function toYoutubeEmbed(url) {
    let m = url.match(/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/);
    if (m) return `https://www.youtube.com/embed/${m[1]}`;
    m = url.match(/youtu\.be\/([a-zA-Z0-9_-]+)/);
    if (m) return `https://www.youtube.com/embed/${m[1]}`;
    if (url.includes('youtube.com/embed/')) return url;
    return null;
}

// ── Sincroniza Quill → hidden input antes de submeter ─────────────────────────
const postForm = document.getElementById('post-form');
if (postForm) {
    postForm.addEventListener('submit', function (e) {
        // Conteúdo do Quill — envia vazio se só tiver <p><br></p>
        const html = quill.root.innerHTML;
        const isEmpty = html === '<p><br></p>' || html.trim() === '';
        document.getElementById('content-input').value = isEmpty ? '' : html;
    });
}

// ── Preview imagem destacada ──────────────────────────────────────────────────
const featuredImageInput = document.getElementById('featured-image-input');
if (featuredImageInput) featuredImageInput.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        document.getElementById('featured-image-preview').src = ev.target.result;
        document.getElementById('preview-wrapper').style.display = 'block';
    };
    reader.readAsDataURL(file);
});

// ── Custom Category Select ────────────────────────────────────────────────────
(function () {
    const wrap     = document.getElementById('cs-category-wrap');
    if (!wrap) return;
    const trigger  = document.getElementById('cs-category-trigger');
    const dropdown = document.getElementById('cs-category-dropdown');
    const search   = document.getElementById('cs-category-search');
    const list     = document.getElementById('cs-category-list');
    const txt      = document.getElementById('cs-category-text');
    const hidden   = document.getElementById('cs-category-hidden');

    // Parse options from the original <select> rendered by Blade
    const origSelect = document.getElementById('cs-category-source');
    if (!origSelect) return;

    // Lê o valor do hidden (preenchido pelo Blade com old() ou $post->category_id)
    const initValue = hidden.value || '';
    if (initValue) origSelect.value = initValue;
    const initOption = Array.from(origSelect.options).find(o => o.value == initValue);
    let selected = { value: initValue, label: initOption?.text || '' };

    // Build grouped structure from <optgroup> / <option>
    function getGroups() {
        const groups = [];
        Array.from(origSelect.children).forEach(el => {
            if (el.tagName === 'OPTGROUP') {
                groups.push({ group: el.label, items: Array.from(el.children).map(o => ({ value: o.value, label: o.text })) });
            } else if (el.tagName === 'OPTION' && el.value) {
                groups.push({ group: null, items: [{ value: el.value, label: el.text }] });
            }
        });
        return groups;
    }

    function renderList(filter) {
        list.innerHTML = '';
        filter = (filter || '').toLowerCase();
        let any = false;
        getGroups().forEach(g => {
            const items = g.items.filter(i => i.label.toLowerCase().includes(filter));
            if (!items.length) return;
            any = true;
            if (g.group) {
                const gl = document.createElement('div');
                gl.className = 'cs-group-label';
                gl.textContent = g.group;
                list.appendChild(gl);
            }
            items.forEach(item => {
                const opt = document.createElement('div');
                opt.className = 'cs-option' + (selected.value === item.value ? ' selected' : '');
                opt.innerHTML = `<svg class="cs-check" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 7l3.5 3.5L12 3"/></svg><span>${item.label}</span>`;
                opt.addEventListener('click', () => {
                    selected = item;
                    txt.textContent = item.label;
                    txt.classList.remove('placeholder');
                    hidden.value = item.value;
                    origSelect.value = item.value;
                    trigger.classList.remove('open');
                    dropdown.classList.remove('open');
                    renderList();
                });
                list.appendChild(opt);
            });
        });
        if (!any) list.innerHTML = '<div class="cs-empty">Nenhuma categoria encontrada</div>';
    }

    // Init label
    if (selected.value && selected.label && selected.label.trim() !== '-- Selecione uma Categoria --') {
        txt.textContent = selected.label;
        txt.classList.remove('placeholder');
        hidden.value = selected.value;
    }
    renderList();

    trigger.addEventListener('click', e => {
        e.stopPropagation();
        const open = dropdown.classList.toggle('open');
        trigger.classList.toggle('open', open);
        if (open) { search.value = ''; renderList(); setTimeout(() => search.focus(), 50); }
    });
    search.addEventListener('input', () => renderList(search.value));
    document.addEventListener('click', e => {
        if (!wrap.contains(e.target)) { dropdown.classList.remove('open'); trigger.classList.remove('open'); }
    });
})();

// ── Tag Input com chips ───────────────────────────────────────────────────────
(function () {
    const tiWrap   = document.getElementById('ti-wrap');
    const tiInput  = document.getElementById('ti-real');
    const tiSug    = document.getElementById('ti-suggestions');
    const tiHidden = document.getElementById('tag-hidden');
    const searchUrl = "{{ route('admin.tags.tags.search') }}";
    if (!tiWrap) return;

    // Parse initial tags from hidden input
    let tags = (tiHidden.value || '').split(',').map(t => t.trim().toUpperCase()).filter(Boolean);
    let activeIdx = -1;
    let timer = null;

    function syncHidden() {
        tiHidden.value = tags.join(', ');
    }

    const MAX_TAGS = 5;

    function addTag(val) {
        val = val.trim().toUpperCase();
        if (val && !tags.includes(val)) {
            if (tags.length >= MAX_TAGS) {
                tiInput.value = '';
                hideSug();
                return;
            }
            tags.push(val);
            renderTags();
            syncHidden();
        }
        tiInput.value = '';
        hideSug();
        updateInputState();
    }

    function updateInputState() {
        if (tags.length >= MAX_TAGS) {
            tiInput.disabled = true;
            tiInput.placeholder = 'Limite de ' + MAX_TAGS + ' tags atingido';
        } else {
            tiInput.disabled = false;
            tiInput.placeholder = 'Adicionar tag...';
        }
    }

    function renderTags() {
        tiWrap.querySelectorAll('.ti-tag').forEach(e => e.remove());
        tags.forEach(t => {
            const tag = document.createElement('span');
            tag.className = 'ti-tag';
            tag.innerHTML = `${t}<span class="ti-tag-remove" data-tag="${t}">✕</span>`;
            tiWrap.insertBefore(tag, tiInput);
        });
        tiWrap.querySelectorAll('.ti-tag-remove').forEach(btn => {
            btn.addEventListener('click', e => {
                e.stopPropagation();
                tags = tags.filter(t => t !== btn.dataset.tag);
                renderTags(); syncHidden(); updateInputState();
            });
        });
    }

    function showSug(q) {
        clearTimeout(timer);
        timer = setTimeout(() => {
            if (!q) { hideSug(); return; }
            fetch(searchUrl + '?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(results => {
                    const filtered = results.filter(t => !tags.includes(t.toUpperCase())).slice(0, 6);
                    if (!filtered.length) { hideSug(); return; }
                    tiSug.innerHTML = '';
                    filtered.forEach((t, i) => {
                        const d = document.createElement('div');
                        d.className = 'ti-suggestion';
                        d.textContent = t;
                        d.addEventListener('mousedown', e => { e.preventDefault(); addTag(t); tiInput.focus(); });
                        tiSug.appendChild(d);
                    });
                    tiSug.style.display = 'block';
                    activeIdx = -1;
                })
                .catch(hideSug);
        }, 200);
    }

    function hideSug() { tiSug.style.display = 'none'; tiSug.innerHTML = ''; activeIdx = -1; }

    tiInput.addEventListener('input', function () {
        const v = this.value.toUpperCase();
        this.value = v;
        if (v.endsWith(',')) { addTag(v.slice(0, -1)); return; }
        showSug(v.trim());
    });

    tiInput.addEventListener('keydown', function (e) {
        const items = tiSug.querySelectorAll('.ti-suggestion');
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            if (activeIdx >= 0 && items[activeIdx]) addTag(items[activeIdx].textContent);
            else if (this.value.trim()) addTag(this.value);
        }
        if (e.key === 'ArrowDown') { e.preventDefault(); activeIdx = Math.min(activeIdx + 1, items.length - 1); items.forEach((el, i) => el.classList.toggle('active', i === activeIdx)); }
        if (e.key === 'ArrowUp')   { e.preventDefault(); activeIdx = Math.max(activeIdx - 1, 0); items.forEach((el, i) => el.classList.toggle('active', i === activeIdx)); }
        if (e.key === 'Backspace' && !this.value && tags.length) { tags.pop(); renderTags(); syncHidden(); }
        if (e.key === 'Escape') hideSug();
    });

    tiWrap.addEventListener('click', () => tiInput.focus());
    document.addEventListener('click', e => { if (!tiWrap.contains(e.target) && !tiSug.contains(e.target)) hideSug(); });

    renderTags();
    updateInputState();
})();
</script>
@endpush