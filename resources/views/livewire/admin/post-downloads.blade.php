<div>

    {{-- ── Botão trigger ──────────────────────────────────────────────────── --}}
    <button type="button"
            wire:click="openModal"
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
            @if(count($buttons) > 0)
                <span style="display:inline-flex;align-items:center;justify-content:center;
                             min-width:18px;height:18px;padding:0 5px;border-radius:50px;
                             background:#6366f1;color:#fff;font-size:.62rem;font-weight:700;">
                    {{ count($buttons) }}
                </span>
            @endif
            <i class="fa fa-chevron-right" style="font-size:.62rem;color:#9ca3af;"></i>
        </span>
    </button>

    {{-- ── Modal ──────────────────────────────────────────────────────────── --}}
    @if($showModal)
        <div class="mir-modal-overlay" style="z-index:1070;padding:12px;">
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
                        <button type="button" class="mir-modal-close" wire:click="closeModal">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="mir-modal-body" style="padding:14px 18px;">

                        @forelse($buttons as $i => $btn)
                            @php $isOpen = ($openIndex === $i); @endphp
                            <div wire:key="dlbtn-{{ $i }}"
                                 style="border:1.5px solid {{ $isOpen ? '#6366f1' : '#e9ecef' }};
                                        border-radius:10px;overflow:hidden;
                                        margin-bottom:8px;transition:border-color .2s;
                                        background:{{ $isOpen ? '#fff' : '#fafafa' }};">

                                {{-- ── Cabeçalho do accordion ─────────────────── --}}
                                <div wire:click="toggleOpen({{ $i }})"
                                     style="display:flex;align-items:center;gap:10px;
                                            padding:10px 12px;cursor:pointer;
                                            user-select:none;">

                                    {{-- Número --}}
                                    <span style="width:22px;height:22px;border-radius:50%;flex-shrink:0;
                                                 display:flex;align-items:center;justify-content:center;
                                                 font-size:.65rem;font-weight:800;
                                                 background:{{ $isOpen ? '#6366f1' : '#e5e7eb' }};
                                                 color:{{ $isOpen ? '#fff' : '#6d7279' }};
                                                 transition:.2s;">
                                        {{ $i + 1 }}
                                    </span>

                                    {{-- Label ou placeholder --}}
                                    <span style="flex:1;min-width:0;font-size:.82rem;font-weight:600;
                                                 color:{{ $isOpen ? '#1a1d23' : '#6d7279' }};
                                                 white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        @if(!empty($btn['label']))
                                            {{ $btn['label'] }}
                                            @if(!empty($btn['url']) || !empty($btn['existingFile']))
                                                <span style="font-size:.7rem;font-weight:400;color:#9ca3af;margin-left:4px;">
                                                    · {{ !empty($btn['url']) ? 'URL' : 'Arquivo' }}
                                                </span>
                                            @endif
                                        @else
                                            <span style="color:#9ca3af;">Botão {{ $i + 1 }} — preencha o nome</span>
                                        @endif
                                    </span>

                                    {{-- Ações: remover + chevron --}}
                                    <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                                        <button type="button"
                                                wire:click.stop="removeButton({{ $i }})"
                                                wire:confirm="Remover '{{ $btn['label'] ?: 'este botão' }}'?"
                                                style="width:24px;height:24px;border-radius:5px;
                                                       border:1px solid #fca5a5;background:#fee2e2;
                                                       color:#b91c1c;cursor:pointer;font-size:.65rem;
                                                       display:flex;align-items:center;justify-content:center;
                                                       transition:.15s;"
                                                onmouseover="this.style.background='#fecaca'"
                                                onmouseout="this.style.background='#fee2e2'">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <i class="fa fa-chevron-{{ $isOpen ? 'up' : 'down' }}"
                                           style="font-size:.62rem;color:{{ $isOpen ? '#6366f1' : '#9ca3af' }};
                                                  transition:.2s;"></i>
                                    </div>

                                </div>

                                {{-- ── Corpo do accordion (só quando aberto) ───── --}}
                                @if($isOpen)
                                    <div style="padding:0 12px 14px;border-top:1px solid #e9ecef;">

                                        {{-- Row 1: Nome + URL --}}
                                        <div style="display:grid;grid-template-columns:1fr 1fr;
                                                    gap:10px;margin-top:12px;margin-bottom:10px;">
                                            <div>
                                                <label class="mir-label">
                                                    Nome <span class="mir-required">*</span>
                                                </label>
                                                <input type="text"
                                                       wire:model.live="buttons.{{ $i }}.label"
                                                       class="mir-input @error('buttons.'.$i.'.label') is-invalid @enderror"
                                                       placeholder="Ex: Google Drive, Mega...">
                                                @error('buttons.'.$i.'.label')
                                                    <div class="mir-field-error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="mir-label">URL</label>
                                                <input type="text"
                                                       wire:model.live="buttons.{{ $i }}.url"
                                                       class="mir-input @error('buttons.'.$i.'.url') is-invalid @enderror"
                                                       placeholder="https://...">
                                                @error('buttons.'.$i.'.url')
                                                    <div class="mir-field-error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Row 2: Arquivo + Posição --}}
                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">

                                            <div>
                                                <label class="mir-label">Arquivo</label>
                                                @if(!empty($btn['existingFile']))
                                                    <div style="display:flex;align-items:center;gap:6px;
                                                                padding:5px 9px;border-radius:7px;
                                                                background:#f0fdf4;border:1px solid #bbf7d0;
                                                                font-size:.72rem;color:#065f46;margin-bottom:6px;">
                                                        <i class="fa fa-file-archive-o" style="flex-shrink:0;"></i>
                                                        <span style="flex:1;overflow:hidden;text-overflow:ellipsis;
                                                                     white-space:nowrap;min-width:0;">
                                                            {{ basename($btn['existingFile']) }}
                                                        </span>
                                                        <button type="button"
                                                                wire:click="removeFile({{ $i }})"
                                                                style="background:transparent;border:none;
                                                                       color:#9ca3af;cursor:pointer;padding:0 2px;"
                                                                onmouseover="this.style.color='#ef4444'"
                                                                onmouseout="this.style.color='#9ca3af'">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                                <input type="file"
                                                       wire:model="buttons.{{ $i }}.uploadedFile"
                                                       class="mir-input"
                                                       style="padding:5px 8px;cursor:pointer;font-size:.72rem;">
                                                <div wire:loading wire:target="buttons.{{ $i }}.uploadedFile"
                                                     class="mir-hint" style="color:#6366f1;">
                                                    <i class="fa fa-spinner fa-spin"></i> Enviando...
                                                </div>
                                                @error('buttons.'.$i.'.uploadedFile')
                                                    <div class="mir-field-error">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div>
                                                <label class="mir-label">Posição</label>
                                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:5px;">
                                                    @foreach(['left'=>'← Esq','center'=>'↔ Centro','right'=>'→ Dir','block'=>'↕ Bloco'] as $val => $lbl)
                                                        <label style="display:flex;align-items:center;gap:5px;
                                                                      padding:6px 8px;border-radius:7px;cursor:pointer;
                                                                      border:1.5px solid {{ ($btn['position'] ?? 'block') === $val ? '#6366f1' : '#e5e7eb' }};
                                                                      background:{{ ($btn['position'] ?? 'block') === $val ? 'rgba(99,102,241,.07)' : '#fff' }};
                                                                      font-size:.72rem;font-weight:600;
                                                                      color:{{ ($btn['position'] ?? 'block') === $val ? '#4338ca' : '#6d7279' }};
                                                                      transition:.15s;">
                                                            <input type="radio"
                                                                   wire:model.live="buttons.{{ $i }}.position"
                                                                   value="{{ $val }}"
                                                                   style="display:none;">
                                                            {{ $lbl }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                                <div class="mir-hint">URL tem prioridade sobre arquivo.</div>
                                            </div>

                                        </div>
                                    </div>
                                @endif

                            </div>
                        @empty
                            <div style="display:flex;flex-direction:column;align-items:center;
                                        padding:28px 20px;text-align:center;">
                                <div style="width:44px;height:44px;border-radius:12px;
                                            background:#f3f4f6;color:#9ca3af;
                                            display:flex;align-items:center;justify-content:center;
                                            font-size:1.1rem;margin-bottom:10px;">
                                    <i class="fa fa-download"></i>
                                </div>
                                <p style="font-size:.82rem;color:#9ca3af;margin:0 0 4px;">
                                    Nenhum botão configurado ainda.
                                </p>
                                <p style="font-size:.75rem;color:#c4c9d4;margin:0;">
                                    Adicione links para Google Drive, Mega, etc.
                                </p>
                            </div>
                        @endforelse

                        {{-- Adicionar --}}
                        @if(count($buttons) < 10)
                            <button type="button"
                                    wire:click="addButton"
                                    class="mir-btn-ghost"
                                    style="width:100%;justify-content:center;
                                           border-style:dashed;margin-top:4px;">
                                <i class="fa fa-plus" style="font-size:.72rem;"></i>
                                Adicionar botão de download
                                @if(count($buttons) > 0)
                                    <span style="color:#9ca3af;font-size:.7rem;margin-left:2px;">
                                        ({{ count($buttons) }}/10)
                                    </span>
                                @endif
                            </button>
                        @endif

                        {{-- Preview --}}
                        @php $labeled = collect($buttons)->filter(fn($b) => !empty($b['label'])); @endphp
                        @if($labeled->count() > 0)
                            <div style="margin-top:12px;padding:12px 14px;border-radius:8px;
                                        background:#fafafa;border:1px dashed #e9ecef;">
                                <div style="font-size:.67rem;font-weight:700;color:#9ca3af;
                                            text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">
                                    Preview
                                </div>
                                <div style="display:flex;flex-wrap:wrap;gap:7px;">
                                    @foreach($buttons as $btn)
                                        @if(!empty($btn['label']))
                                            @php
                                                $pos = $btn['position'] ?? 'block';
                                                $s = "display:inline-flex;align-items:center;gap:5px;
                                                      padding:6px 13px;border-radius:7px;
                                                      background:linear-gradient(135deg,#6366f1,#4f46e5);
                                                      color:#fff;font-size:.74rem;font-weight:600;
                                                      box-shadow:0 2px 6px rgba(99,102,241,.25);";
                                            @endphp
                                            @if($pos === 'block')
                                                <div style="width:100%;">
                                                    <span style="{{ $s }}">
                                                        <i class="fa fa-download"></i> {{ $btn['label'] }}
                                                    </span>
                                                </div>
                                            @elseif($pos === 'center')
                                                <div style="width:100%;display:flex;justify-content:center;">
                                                    <span style="{{ $s }}"><i class="fa fa-download"></i> {{ $btn['label'] }}</span>
                                                </div>
                                            @elseif($pos === 'right')
                                                <div style="width:100%;display:flex;justify-content:flex-end;">
                                                    <span style="{{ $s }}"><i class="fa fa-download"></i> {{ $btn['label'] }}</span>
                                                </div>
                                            @else
                                                <span style="{{ $s }}"><i class="fa fa-download"></i> {{ $btn['label'] }}</span>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>

                    {{-- Footer --}}
                    <div class="mir-modal-footer">
                        <button type="button" class="mir-btn-ghost" wire:click="closeModal">
                            Cancelar
                        </button>
                        <button type="button"
                                class="mir-btn-primary-lg"
                                wire:click="save"
                                wire:loading.attr="disabled"
                                wire:target="save">
                            <span wire:loading wire:target="save">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                            <i class="fa fa-save" wire:loading.remove wire:target="save"></i>
                            {{ count($buttons) > 0 ? 'Salvar Downloads' : 'Fechar' }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

</div>