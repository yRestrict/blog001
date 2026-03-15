@push('stylesheets')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <style>
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
window.hljs.configure({
    languages: ['php', 'javascript', 'html', 'css', 'bash', 'xml', 'json'],
    cssSelector: '.desativar-vazamento-global' 
});

// ── Registra o módulo ─────────────────────────────────────────────────────────
Quill.register('modules/imageResize', window.QuillResizeImage);

// ── Instancia o Quill ─────────────────────────────────────────────────────────
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
                ['blockquote','code-block'],
                ['link','image','video'],
                ['clean'],
            ],
            handlers: { image: imageHandler, video: videoHandler },
        },
        syntax: true,
        imageResize: {},
    },
});

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

document.getElementById('yt-apply-btn').addEventListener('click', function () {
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
document.getElementById('post-form').addEventListener('submit', function () {
    document.getElementById('content-input').value = quill.root.innerHTML;
});

// ── Preview imagem destacada ──────────────────────────────────────────────────
document.getElementById('featured-image-input').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        document.getElementById('featured-image-preview').src = ev.target.result;
        document.getElementById('preview-wrapper').style.display = 'block';
    };
    reader.readAsDataURL(file);
});

// ── Autocomplete tags ─────────────────────────────────────────────────────────
(function () {
    const input       = document.getElementById('tag-input');
    const suggestions = document.getElementById('tag-suggestions');
    const searchUrl   = "{{ route('admin.tags.tags.search') }}";
    let timer = null;

    input.addEventListener('input', function () {
        const pos = this.selectionStart;
        this.value = this.value.toUpperCase();
        this.setSelectionRange(pos, pos);
        clearTimeout(timer);
        timer = setTimeout(fetchSuggestions, 250);
    });

    function fetchSuggestions() {
        const parts = input.value.split(',');
        const last  = parts[parts.length - 1].trim();
        if (!last) { hide(); return; }
        fetch(searchUrl + '?q=' + encodeURIComponent(last))
            .then(r => r.json()).then(tags => render(tags, parts)).catch(hide);
    }

    function render(tags, parts) {
        suggestions.innerHTML = '';
        const used     = parts.slice(0, -1).map(t => t.trim().toUpperCase());
        const filtered = tags.filter(t => !used.includes(t.toUpperCase()));
        if (!filtered.length) { hide(); return; }
        filtered.forEach(tag => {
            const li = document.createElement('li');
            li.textContent   = tag;
            li.style.cssText = 'padding:8px 12px;cursor:pointer;font-size:.875rem;';
            li.addEventListener('mouseenter', () => li.style.background = '#f3f4f6');
            li.addEventListener('mouseleave', () => li.style.background = '');
            li.addEventListener('mousedown',  e  => { e.preventDefault(); pick(tag, parts); });
            suggestions.appendChild(li);
        });
        suggestions.style.display = 'block';
    }

    function pick(tag, parts) {
        parts[parts.length - 1] = ' ' + tag;
        input.value = parts.join(',').replace(/^,\s*/, '') + ', ';
        hide(); input.focus();
    }

    document.addEventListener('click', e => {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) hide();
    });

    input.addEventListener('keydown', function (e) {
        const items  = suggestions.querySelectorAll('li');
        const active = suggestions.querySelector('li.active');
        const idx    = Array.from(items).indexOf(active);
        if (e.key === 'ArrowDown') { e.preventDefault(); setActive(items[idx+1] || items[0]); }
        if (e.key === 'ArrowUp')   { e.preventDefault(); setActive(items[idx-1] || items[items.length-1]); }
        if (e.key === 'Enter' && active) { e.preventDefault(); pick(active.textContent, input.value.split(',')); }
        if (e.key === 'Escape') hide();
    });

    function setActive(el) {
        suggestions.querySelectorAll('li').forEach(l => { l.classList.remove('active'); l.style.background=''; });
        if (el) { el.classList.add('active'); el.style.background='#ede9fe'; }
    }

    function hide() { suggestions.style.display='none'; suggestions.innerHTML=''; }
})();
</script>
@endpush