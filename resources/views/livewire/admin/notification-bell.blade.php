<div>
    <div class="user-notification" wire:poll.30s="loadNotifications">

        <div class="dropdown">

            {{-- Sino --}}
            <a class="dropdown-toggle no-arrow"
            href="#"
            role="button"
            wire:click.prevent="toggle"
            aria-label="Notifications"
            title="Notifications">
                <i class="icon-copy dw dw-notification"></i>
                @if($unreadCount > 0)
                    <span class="badge notification-active">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                @endif
            </a>

            {{-- Dropdown --}}
            @if($open)
            <div class="dropdown-menu dropdown-menu-right show" style="min-width: 320px;">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <strong>Notificações</strong>
                    @if($unreadCount > 0)
                        <a href="#" wire:click.prevent="markAllRead" class="small text-primary">
                            Marcar todas como lidas
                        </a>
                    @endif
                </div>

                {{-- Lista --}}
                <div class="notification-list mx-h-350 customscroll">
                    @forelse($notifications as $notification)
                        <a href="{{ $notification->data['url'] ?? '#' }}"
                        wire:click="markRead('{{ $notification->id }}')"
                        class="d-flex align-items-start px-3 py-2 border-bottom text-decoration-none
                                {{ is_null($notification->read_at) ? 'bg-light' : '' }}"
                        style="{{ is_null($notification->read_at) ? 'background: rgba(0,123,255,0.05) !important;' : '' }}">

                            {{-- Ícone por tipo --}}
                            <div class="mr-3 mt-1" style="font-size: 1.4rem; min-width: 30px; text-align:center;">
                                @switch($notification->data['type'])
                                    @case('comment')  💬 @break
                                    @case('reply')    💬 @break
                                    @case('reaction')
                                        {{ $notification->data['emoji'] ?? '👍' }}
                                    @break
                                    @case('comment_approved') ✅ @break
                                    @default 🔔
                                @endswitch
                            </div>

                            {{-- Conteúdo --}}
                            <div class="flex-1">
                                <p class="mb-0 small font-weight-{{ is_null($notification->read_at) ? 'bold' : 'normal' }}"
                                style="color: #333; line-height: 1.3;">
                                    {{ $notification->data['message'] }}
                                </p>
                                @if(isset($notification->data['excerpt']))
                                    <p class="mb-0 small text-muted mt-1" style="font-size: 0.75rem;">
                                        "{{ $notification->data['excerpt'] }}"
                                    </p>
                                @endif
                                <p class="mb-0 text-muted mt-1" style="font-size: 0.7rem;">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>

                            {{-- Bolinha de não lida --}}
                            @if(is_null($notification->read_at))
                                <div class="ml-2 mt-1" style="width:8px;height:8px;background:#007bff;border-radius:50%;flex-shrink:0;"></div>
                            @endif

                        </a>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="dw dw-notification" style="font-size: 2rem; opacity: .3;"></i>
                            <p class="mt-2 small">Nenhuma notificação</p>
                        </div>
                    @endforelse
                </div>

            </div>
            @endif

        </div>
    </div>
</div>