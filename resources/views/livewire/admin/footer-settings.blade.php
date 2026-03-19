<div>
    <style>
        /* Container Principal com o visual do novo painel */
        .pop-cat-section {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #eef0f2;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .03);
            margin-bottom: 24px;
            overflow: hidden;
        }

        /* Cabeçalho Refinado */
        .pop-cat-header {
            padding: 20px 24px;
            background: #fcfcfd;
            border-bottom: 1px solid #f0f2f5;
        }

        .pop-cat-header h5 {
            font-size: 1rem;
            font-weight: 700;
            color: #1a1d23;
            margin: 0;
            letter-spacing: -0.3px;
        }

        .pop-cat-header p {
            font-size: .82rem;
            color: #9ca3af;
            margin: 2px 0 0 0;
        }

        /* Corpo do Formulário */
        .pop-cat-body {
            padding: 24px;
        }

        /* Label e Descrição do Campo */
        .pop-cat-label {
            font-size: .88rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
            display: block;
        }

        .pop-cat-desc {
            font-size: .82rem;
            color: #6b7280;
            margin-bottom: 16px;
            display: block;
        }

        /* Container dos Cards de Seleção */
        .order-selection-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        /* Card de Opção (Label) */
        .selection-card {
            cursor: pointer;
            position: relative;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            transition: all 0.2s ease;
            margin-bottom: 0;
            background: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .selection-card:hover {
            border-color: #d1d5db;
            background-color: #f9fafb;
        }

        /* Estado Ativo (Selecionado) */
        .selection-card.active {
            border-color: #6366f1; /* Cor indico do gradiente */
            background-color: rgba(99, 102, 241, 0.03);
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.08);
        }

        /* Indicador lateral colorido (Tendência UI) */
        .selection-card.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 3px;
            background: #6366f1;
            border-radius: 0 4px 4px 0;
        }

        /* Ícone */
        .selection-icon {
            width: 40px;
            height: 40px;
            background: #f3f4f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 1.1rem;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .selection-card.active .selection-icon {
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
        }

        /* Texto do Card */
        .selection-text {
            flex-grow: 1;
        }

        .selection-title {
            display: block;
            font-size: .88rem;
            font-weight: 600;
            color: #1a1d23;
        }

        .selection-desc {
            font-size: .78rem;
            color: #9ca3af;
            display: block;
            margin-top: 2px;
        }

        /* Checkmark Lateral */
        .selection-check {
            font-size: 1.2rem;
            color: #e5e7eb;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .selection-card.active .selection-check {
            color: #6366f1;
            transform: scale(1.05);
        }

        /* Botão com Gradiente Indico (igual ao seu menu) */
        .mir-btn-save {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 9px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
            font-size: .85rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(99, 102, 241, .25);
        }

        .mir-btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(99, 102, 241, .35);
            color: #fff;
        }
        
        .mir-btn-save:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
    </style>

    <div class="pop-cat-section">
        {{-- HEADER --}}
        <div class="pop-cat-header">
            <h5>Categorias Populares</h5>
            <p>Defina o critério de exibição das categorias no rodapé do site.</p>
        </div>

        {{-- BODY --}}
        <div class="pop-cat-body">
            <div class="form-group mb-0">
                <label class="pop-cat-label">Ordenar categorias por</label>
                <span class="pop-cat-desc">
                    Escolha como o sistema deve selecionar as categorias para exibir.
                </span>

                <div class="order-selection-container">
                    
                    <label class="selection-card {{ $footer_category_order === 'posts' ? 'active' : '' }}">
                        <input type="radio" wire:model.live="footer_category_order" value="posts" class="d-none">
                        <div class="selection-icon">
                            <i class="fa fa-pencil-square-o"></i>
                        </div>
                        <div class="selection-text">
                            <span class="selection-title">Volume de Conteúdo</span>
                            <span class="selection-desc">Prioriza categorias com maior número de posts publicados.</span>
                        </div>
                        <div class="selection-check">
                            <i class="fa fa-check-circle"></i>
                        </div>
                    </label>

                    <label class="selection-card {{ $footer_category_order === 'views' ? 'active' : '' }}">
                        <input type="radio" wire:model.live="footer_category_order" value="views" class="d-none">
                        <div class="selection-icon">
                            <i class="fa fa-bar-chart"></i>
                        </div>
                        <div class="selection-text">
                            <span class="selection-title">Popularidade Real</span>
                            <span class="selection-desc">Mostra as categorias que recebem mais visitas dos usuários.</span>
                        </div>
                        <div class="selection-check">
                            <i class="fa fa-check-circle"></i>
                        </div>
                    </label>

                </div>

                @error('footer_category_order')
                    <div class="alert alert-danger py-2 mt-3 font-12">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- FOOTER COM BOTÃO --}}
        <div class="px-4 py-3 border-top text-right">
            <button wire:click="save" wire:loading.attr="disabled" class="mir-btn-save">
                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm mr-1"></span>
                <i class="fa fa-save" wire:loading.remove wire:target="save"></i>
                Salvar Configurações
            </button>
        </div>
    </div>
</div>