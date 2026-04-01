{{--
    Empty State — exibido quando a lista está vazia
    @props: icon, title, description
    @slot: ação (botão CTA)
--}}
@props(['icon' => 'inbox', 'title', 'description' => null])

<div class="mir-empty-state">
    <div class="mir-empty-icon">
        <i class="fa-solid fa-{{ $icon }}"></i>
    </div>
    <h3 class="mir-empty-title">{{ $title }}</h3>
    @if($description)
        <p class="mir-empty-desc">{{ $description }}</p>
    @endif
    @if($slot->isNotEmpty())
        {{ $slot }}
    @endif
</div>
