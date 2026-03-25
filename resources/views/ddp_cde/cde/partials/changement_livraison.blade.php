<div class="w-full">
    <x-tooltip position="auto" class="">
        <x-slot name="slot_tooltip">
            <span>
                Liste de tout les changements fait depuis la création de la commande
            </span>
        </x-slot>
        <x-slot name="slot_item">
            <div class="flex items-center justify-between cursor-pointer border-b border-gray-500 pb-2 mb-4 w-full"
                onclick="toggleChangementsSection()">
                <h1 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mr-2">
                    Changements de livraison ({{ count(json_decode($cde->changement_livraison, true) ?? []) }})
                </h1>
                <svg id="changements-chevron" xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 transition-transform duration-300" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </x-slot>
    </x-tooltip>
    <div id="changements-content" class="hidden">
        @if ($cde->changement_livraison)
            @php
                $changements = json_decode($cde->changement_livraison, true);
            @endphp
            @if (count($changements) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-fit bg-white dark:bg-gray-800 rounded-md overflow-hidden">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Produit</th>
                                <th
                                    class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Description</th>
                                <th
                                    class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($changements as $changement)
                                @php
                                    $ligne = $cde->cdeLignes->firstWhere('id', $changement['ligne_id']);
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $ligne ? $ligne->ref_interne . ' - ' . $ligne->designation : 'Ligne #' . $changement['ligne_id'] }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $changement['description'] }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                        @if (isset($changement['date']) && $changement['date'])
                                            {{ \Carbon\Carbon::parse($changement['date'])->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">Aucun changement de livraison enregistré.
                </p>
            @endif
        @else
            <p class="text-gray-500 dark:text-gray-400">Aucun changement de livraison enregistré.</p>
        @endif
    </div>
</div>

<script>
    function toggleChangementsSection() {
        const content = document.getElementById('changements-content');
        const chevron = document.getElementById('changements-chevron');

        content.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }
</script>
