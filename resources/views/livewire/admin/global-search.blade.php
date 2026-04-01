<div class="gs-wrap" x-data="{ open: @entangle('showResults') }" @click.away="$wire.close()" @keydown.escape.window="$wire.close()">

    {{-- Input --}}
    <div class="gs-input-wrap">
        <i class="fa-solid fa-magnifying-glass gs-input-icon"></i>
        <input type="text"
               wire:model.live.debounce.300ms="query"
               class="gs-input"
               placeholder="Buscar posts, categorias, tags, usuários..."
               autocomplete="off"
               @focus="if($wire.query.length >= 2) open = true">
        @if($query)
            <button type="button" class="gs-input-clear" wire:click="close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        @endif
    </div>

    {{-- Dropdown de resultados --}}
    @if($showResults)
        <div class="gs-dropdown">

            {{-- Loading --}}
            <div wire:loading wire:target="query" class="gs-loading">
                <div class="gs-loading-bar"></div>
                <div class="gs-loading-text">
                    <i class="fa-solid fa-spinner fa-spin"></i> Buscando...
                </div>
            </div>

            {{-- Resultados --}}
            <div wire:loading.remove wire:target="query">
                @if(empty($results))
                    <div class="gs-empty">
                        <i class="fa-solid fa-magnifying-glass" style="color:#d1d5db;font-size:1.2rem;"></i>
                        <span>Nenhum resultado para "<strong>{{ $query }}</strong>"</span>
                    </div>
                @else
                    @foreach($results as $group => $items)
                        <div class="gs-group">
                            <div class="gs-group-label">{{ $group }}</div>
                            @foreach($items as $item)
                                <a href="{{ $item['url'] }}" class="gs-item" wire:navigate>
                                    <div class="gs-item-icon">
                                        <i class="fa-solid {{ $item['icon'] }}"></i>
                                    </div>
                                    <div class="gs-item-body">
                                        <div class="gs-item-label">{{ $item['label'] }}</div>
                                        <div class="gs-item-meta">{{ $item['meta'] }}</div>
                                    </div>
                                    <i class="fa-solid fa-arrow-right gs-item-arrow"></i>
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endif

    <style>
        .gs-wrap {
            position: relative;
            flex: 1;
            max-width: 480px;
        }

        /* Input */
        .gs-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .gs-input-icon {
            position: absolute;
            left: 12px;
            color: #9ca3af;
            font-size: .78rem;
            pointer-events: none;
        }
        .gs-input {
            width: 100%;
            padding: 8px 36px 8px 34px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: .84rem;
            color: #1a1d23;
            background: #f9fafb;
            outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        .gs-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.1);
            background: #fff;
        }
        .gs-input::placeholder { color: #9ca3af; }
        .gs-input-clear {
            position: absolute;
            right: 8px;
            width: 24px; height: 24px;
            border-radius: 6px;
            border: none;
            background: transparent;
            color: #9ca3af;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: .7rem;
            transition: background .15s, color .15s;
        }
        .gs-input-clear:hover { background: #f3f4f6; color: #374151; }

        /* Loading */
        .gs-loading-bar {
            height: 3px;
            background: #f0f0f0;
            position: relative;
            overflow: hidden;
            border-radius: 10px 10px 0 0;
        }
        .gs-loading-bar::after {
            content: '';
            position: absolute;
            top: 0; left: -40%;
            width: 40%; height: 100%;
            background: linear-gradient(90deg, transparent, #6366f1, transparent);
            animation: gs-slide 1.2s ease-in-out infinite;
        }
        @keyframes gs-slide {
            0%   { left: -40%; }
            100% { left: 100%; }
        }
        .gs-loading-text {
            padding: 16px;
            text-align: center;
            font-size: .82rem;
            color: #6366f1;
        }

        /* Dropdown */
        .gs-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            box-shadow: 0 12px 40px rgba(0,0,0,.12);
            z-index: 1050;
            max-height: 420px;
            overflow-y: auto;
            animation: gs-fade-in .12s ease;
        }
        @keyframes gs-fade-in {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Empty */
        .gs-empty {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 24px 16px;
            font-size: .84rem;
            color: #9ca3af;
            justify-content: center;
        }
        .gs-empty strong { color: #374151; }

        /* Group */
        .gs-group {
            border-bottom: 1px solid #f0f0f0;
        }
        .gs-group:last-child { border-bottom: none; }
        .gs-group-label {
            padding: 10px 16px 4px;
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #9ca3af;
        }

        /* Item */
        .gs-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            text-decoration: none;
            transition: background .12s;
            color: inherit;
        }
        .gs-item:hover {
            background: #f9fafb;
            color: inherit;
            text-decoration: none;
        }
        .gs-item-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: #f3f4f6;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: .75rem;
            color: #6366f1;
        }
        .gs-item-body { flex: 1; min-width: 0; }
        .gs-item-label {
            font-size: .82rem;
            font-weight: 600;
            color: #1a1d23;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .gs-item-meta {
            font-size: .7rem;
            color: #9ca3af;
            margin-top: 1px;
        }
        .gs-item-arrow {
            font-size: .6rem;
            color: #d1d5db;
            flex-shrink: 0;
            transition: color .12s;
        }
        .gs-item:hover .gs-item-arrow { color: #6366f1; }
    </style>
</div>
