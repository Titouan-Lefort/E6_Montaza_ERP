@props([
    'matiere' => null,
    'no_underline' => false,
    'no_color' => false,
])
@if ($matiere->typeAffichageStock() == 2)
    <x-tooltip :position="'right'" :class="'group'">
        <x-slot:slot_item>
            <span class="cursor-pointer {{ $no_underline ? '' : 'underline' }} {{ $matiere->quantite() < $matiere->stock_min && !$no_color ? 'text-amber-600' : '' }}">
                {{ $matiere->quantite() }} {{ $matiere->unite->short }}
                @if ($matiere->quantite() < $matiere->stock_min)
                @endif
            </span>
        </x-slot:slot_item>
        <x-slot:slot_tooltip>
            @if ($matiere->quantite() > 1)
                <h3 class="text-sm font-semibold mb-2 whitespace-nowrap border-b {{ $matiere->quantite() < $matiere->stock_min ? 'text-amber-600' : '' }}">{{ $matiere->quantite() }}
                    {{ $matiere->unite->full_plural }}</h3>
            @else
                <h3 class="text-sm font-semibold mb-2 whitespace-nowrap border-b {{ $matiere->quantite() < $matiere->stock_min ? 'text-amber-600' : '' }}">{{ $matiere->quantite() }}
                    {{ $matiere->unite->full }}</h3>
            @endif
            @if ($matiere->quantite() < $matiere->stock_min)
                <p class="text-amber-600 text-xs mb-2 italic">Stock faible (min: {{ $matiere->stock_min }} {{ $matiere->unite->short }})</p>
            @endif
            <ul class="text-sm space-y-1">
                @foreach ($matiere->stock->where("quantite",'!=',0) as $stock)
                    <li>- {{ formatNumber($stock->quantite) }} x {{ formatNumber($stock->valeur_unitaire) }} {{ $matiere->unite->short }}</li>
                @endforeach
            </ul>
        </x-slot:slot_tooltip>
    </x-tooltip>
@else
    @if ($matiere->quantite() > 1)
        <span class="{{ $matiere->quantite() < $matiere->stock_min ? 'text-amber-600' : '' }}" title="{{ $matiere->quantite() }} {{ $matiere->unite->full_plural }}{{ $matiere->quantite() < $matiere->stock_min ? ' - Stock faible (Min: ' . $matiere->stock_min . ' ' . $matiere->unite->short . ')' : '' }}">
        @else
            <span class="{{ $matiere->quantite() < $matiere->stock_min ? 'text-amber-600' : '' }}" title="{{ $matiere->quantite() }} {{ $matiere->unite->full }}{{ $matiere->quantite() < $matiere->stock_min ? ' - Stock faible (Min: ' . $matiere->stock_min . ' ' . $matiere->unite->short . ')' : '' }}">
    @endif
    {{ $matiere->quantite() }} {{ $matiere->unite->short }}
    </span>
@endif
