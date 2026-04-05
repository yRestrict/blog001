<div>

    <div class="page-header-action">
        <div class="page-header-left">
            <h1 class="page-header-title">Configurações do Footer</h1>
            <span class="page-header-sub">Defina como as categorias são exibidas no rodapé</span>
        </div>
        <div class="page-header-right">
            <a href="{{ route('admin.settings') }}" class="mir-btn-neutral">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="ftr-section-card">
        <div class="ftr-section-header">
            <div class="ftr-section-icon"><i class="fa-solid fa-layer-group"></i></div>
            <div>
                <div class="ftr-section-title">Categorias Populares</div>
                <div class="ftr-section-sub">Escolha como o sistema deve selecionar as categorias para exibir</div>
            </div>
        </div>

        <div class="ftr-section-body">
            <label class="mir-label">Ordenar categorias por</label>

            <div class="ftr-selection-grid">

                <label class="ftr-selection-card {{ $footer_category_order === 'posts' ? 'active' : '' }}">
                    <input type="radio" wire:model.live="footer_category_order" value="posts" class="d-none">
                    <div class="ftr-selection-icon"><i class="fa-solid fa-file-lines"></i></div>
                    <div class="ftr-selection-text">
                        <span class="ftr-selection-title">Volume de Conteúdo</span>
                        <span class="ftr-selection-desc">Prioriza categorias com maior número de posts publicados.</span>
                    </div>
                    <div class="ftr-selection-check"><i class="fa-solid fa-circle-check"></i></div>
                </label>

                <label class="ftr-selection-card {{ $footer_category_order === 'views' ? 'active' : '' }}">
                    <input type="radio" wire:model.live="footer_category_order" value="views" class="d-none">
                    <div class="ftr-selection-icon"><i class="fa-solid fa-chart-simple"></i></div>
                    <div class="ftr-selection-text">
                        <span class="ftr-selection-title">Popularidade Real</span>
                        <span class="ftr-selection-desc">Mostra as categorias que recebem mais visitas dos usuários.</span>
                    </div>
                    <div class="ftr-selection-check"><i class="fa-solid fa-circle-check"></i></div>
                </label>

            </div>

            @error('footer_category_order')
                <div class="mir-field-error" style="margin-top:12px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="ftr-section-footer">
            <button wire:click="save" wire:loading.attr="disabled" class="mir-btn-primary-lg">
                <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm mr-1"></span></span>
                <i class="fa-solid fa-floppy-disk" wire:loading.remove wire:target="save"></i>
                Salvar Configurações
            </button>
        </div>
    </div>

    {{-- Toast container --}}
    <div id="ftr-toast-container" aria-live="polite"></div>

    <style>
        .ftr-section-card { background:#fff;border-radius:10px;border:1px solid #e9ecef;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden; }
        .ftr-section-header { display:flex;align-items:center;gap:12px;padding:16px 20px;border-bottom:1px solid #f0f0f0; }
        .ftr-section-icon { width:34px;height:34px;border-radius:8px;background:#ede9fe;color:#6366f1;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.82rem; }
        .ftr-section-title { font-size:.88rem;font-weight:700;color:#1a1d23; }
        .ftr-section-sub { font-size:.72rem;color:#9ca3af;margin-top:1px; }
        .ftr-section-body { padding:20px; }
        .ftr-section-footer { padding:16px 20px;border-top:1px solid #f0f0f0;background:#fafafa;display:flex;justify-content:flex-end; }
        .ftr-selection-grid { display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-top:12px; }
        .ftr-selection-card { cursor:pointer;position:relative;border:1.5px solid #e5e7eb;border-radius:10px;padding:16px;transition:border-color .15s,background .15s,box-shadow .15s;background:#fff;display:flex;align-items:center;gap:12px;margin-bottom:0; }
        .ftr-selection-card:hover { border-color:#d1d5db;background:#f9fafb; }
        .ftr-selection-card.active { border-color:#6366f1;background:rgba(99,102,241,.03);box-shadow:0 2px 8px rgba(99,102,241,.1); }
        .ftr-selection-card.active::before { content:'';position:absolute;left:0;top:20%;height:60%;width:3px;background:#6366f1;border-radius:0 4px 4px 0; }
        .ftr-selection-icon { width:40px;height:40px;background:#f3f4f6;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#6b7280;font-size:1.1rem;transition:background .15s,color .15s;flex-shrink:0; }
        .ftr-selection-card.active .ftr-selection-icon { background:rgba(99,102,241,.1);color:#6366f1; }
        .ftr-selection-text { flex:1; }
        .ftr-selection-title { display:block;font-size:.88rem;font-weight:600;color:#1a1d23; }
        .ftr-selection-desc { font-size:.78rem;color:#9ca3af;display:block;margin-top:2px; }
        .ftr-selection-check { font-size:1.2rem;color:#e5e7eb;transition:color .15s;flex-shrink:0; }
        .ftr-selection-card.active .ftr-selection-check { color:#6366f1; }
    </style>

    @push('scripts')
    <script>
    (function () {
        function ftrShowToast(type, message) {
            const container = document.getElementById('ftr-toast-container');
            const icons = { success: 'fa-circle-check', error: 'fa-circle-exclamation', warning: 'fa-triangle-exclamation', info: 'fa-circle-info' };
            const toast = document.createElement('div');
            toast.className = `mir-toast mir-toast-${type}`;
            toast.innerHTML = `<i class="fa-solid ${icons[type] || icons.info} mir-toast-icon"></i><span class="mir-toast-msg">${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'mir-toast-out 200ms ease forwards';
                setTimeout(() => toast.remove(), 210);
            }, 3500);
        }

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', ({ type, message }) => ftrShowToast(type, message));
        });
    })();
    </script>
    @endpush

</div>