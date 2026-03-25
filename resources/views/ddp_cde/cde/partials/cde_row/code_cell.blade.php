@php
    $total = $cde->cdeLignes->where('conditionnement', '!=', 0)->count();
@endphp

@if ($total > 0)
    <x-tooltip position="top">
        <x-slot name="slot_item">
            <x-icon :size="1" type="error_icon" class="fill-red-500 dark:fill-red-400" />
        </x-slot>
        <x-slot name="slot_tooltip">
            <span class="text-red-600 dark:text-red-400 font-semibold">
                Les stocks à prendre en compte pour cette commande n'ont pas encore été précisés.
            </span>
        </x-slot>
    </x-tooltip>
@endif

<x-tooltip position="right">
    <x-slot name="slot_item">
        <span class="{{ $isSmall ? 'whitespace-nowrap' : '' }} font-bold">{{ $cde->code }}</span>
    </x-slot>
    <x-slot name="slot_tooltip">
        @include('ddp_cde.cde.partials.cde_row.tooltip_content', compact('cde'))
    </x-slot>
</x-tooltip>
