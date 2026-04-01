{{--
    Section Header com ícone + título + subtítulo + slot direito
    @props: icon, iconColor (indigo|green|amber|cyan|red), title, subtitle
    @slot: right (conteúdo à direita)
--}}
@props(['icon', 'iconColor' => 'indigo', 'title', 'subtitle' => null])

<div class="mir-section-header">
    <div class="section-icon-header">
        <div class="section-icon section-icon-{{ $iconColor }}">
            <i class="fa-solid fa-{{ $icon }}"></i>
        </div>
        <div>
            <h2 class="mir-section-title">{{ $title }}</h2>
            @if($subtitle)
                <p class="mir-section-sub">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @if(isset($right))
        <div class="mir-section-header-right">
            {{ $right }}
        </div>
    @endif
</div>
