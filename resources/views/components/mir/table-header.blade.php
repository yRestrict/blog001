{{-- Table Header — colunas uppercase acima das data rows --}}
<div {{ $attributes->merge(['class' => 'mir-table-header']) }}>
    {{ $slot }}
</div>
