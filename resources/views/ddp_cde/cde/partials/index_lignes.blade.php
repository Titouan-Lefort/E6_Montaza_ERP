@php
    $isMid = $isMid ?? false;
    $isSmall = $isSmall ?? false;
    $showCreateButton = $showCreateButton ?? false;
    $cdesGrouped = $cdesGrouped ?? null;
@endphp

<tbody>
    @if (!$isSmall && !$isMid && isset($cdesGrouped) && $cdesGrouped->count() > 0)
        @foreach ($cdesGrouped as $entiteNom => $cdesParEntite)
            <!-- Ligne de séparation avec le nom de l'entité -->
            <tr class="bg-gray-50 dark:bg-gray-800/50">
                <td colspan="6" class="px-6 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                                {{ $entiteNom }}
                            </span>
                        </div>
                        <span class="text-xs font-semibold text-green-700 bg-green-100 dark:bg-green-900 dark:text-green-300 px-3 py-1 rounded-full border border-green-200 dark:border-green-800">
                            {{ $cdesParEntite->count() }}
                        </span>
                    </div>
                </td>
            </tr>

            @foreach ($cdesParEntite as $cde)
                @include('ddp_cde.cde.partials.cde_row.row', compact('cde', 'isSmall', 'isMid'))
            @endforeach
        @endforeach
    @else
        @foreach ($cdes as $cde)
            @include('ddp_cde.cde.partials.cde_row.row', compact('cde', 'isSmall', 'isMid'))
        @endforeach
    @endif

    @if ($cdes->count() == 0)
        <tr>
            <td colspan="{{ $isSmall ? '4' : '6' }}" class="text-center py-8">
                {{ $isSmall ? 'Aucune commande en cours' : 'Aucune commande trouvée' }}
            </td>
        </tr>
    @endif

    @if ($showCreateButton)
        <tr>
            <td colspan="{{ $isSmall ? '4' : '6' }}" class="">
                <a href="{{ route('cde.create') }}" class="btn-select-square rounded-b-md text-center">
                    Créer une commande
                </a>
            </td>
        </tr>
    @endif
</tbody>
