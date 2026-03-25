<x-app-layout>
    @section('title', 'Vérification Matières pour Devis')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Vérification des Matières pour les Devis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900 dark:border-green-700 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($devis->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucun devis actif</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Il n'y a aucun devis actif à vérifier pour le moment.</p>
                        </div>
                    @else
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-2">{{ $devis->count() }} devis actifs</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Vérification de la disponibilité des matières en stock</p>
                        </div>

                        <div class="space-y-6">
                            @foreach($devis as $devisItem)
                                @php
                                    $materiaux = [];
                                    $stockManquant = false;

                                    // Regrouper les matériaux par désignation
                                    foreach($devisItem->sections as $section) {
                                        foreach($section->lignes as $ligne) {
                                            if($ligne->type === 'fourniture' && !empty($ligne->matiere)) {
                                                $key = $ligne->matiere_id ?: $ligne->matiere; // Grouper par ID si disponible, sinon par nom
                                                if(!isset($materiaux[$key])) {
                                                    $materiaux[$key] = [
                                                        'designation' => $ligne->designation,
                                                        'matiere' => $ligne->matiere,
                                                        'quantite_totale' => 0,
                                                        'quantite_matiere_totale' => 0, // Quantité de matière nécessaire
                                                        'unite' => $ligne->unite ?? 'u',
                                                        'unite_matiere' => $ligne->unite_matiere ?? 'ml',
                                                        'matiere_id' => $ligne->matiere_id, // Utiliser l'ID direct
                                                    ];
                                                }
                                                $materiaux[$key]['quantite_totale'] += $ligne->quantite;

                                                // Calculer la quantité de matière nécessaire
                                                if($ligne->quantite_matiere_unitaire > 0) {
                                                    $materiaux[$key]['quantite_matiere_totale'] += ($ligne->quantite * $ligne->quantite_matiere_unitaire);
                                                }
                                            }
                                        }
                                    }

                                    // Vérifier le stock pour chaque matériau
                                    foreach($materiaux as &$materiau) {
                                        // Utiliser la quantité de matière si définie, sinon la quantité d'éléments
                                        $quantiteAVerifier = $materiau['quantite_matiere_totale'] > 0
                                            ? $materiau['quantite_matiere_totale']
                                            : $materiau['quantite_totale'];

                                        $materiau['quantite_a_verifier'] = $quantiteAVerifier;

                                        // Si matiere_id existe, récupérer directement la matière
                                        if($materiau['matiere_id']) {
                                            $matiereStock = \App\Models\Matiere::find($materiau['matiere_id']);
                                        } else {
                                            // Sinon, essayer de trouver par recherche (pour anciens devis)
                                            $matiereStock = \App\Models\Matiere::where('designation', 'like', '%' . $materiau['matiere'] . '%')
                                                ->orWhere('ref_interne', 'like', '%' . $materiau['matiere'] . '%')
                                                ->first();

                                            // Si trouvé, mettre à jour l'ID pour référence
                                            if($matiereStock) {
                                                $materiau['matiere_id'] = $matiereStock->id;
                                            }
                                        }

                                        if($matiereStock) {
                                            // Stock total en base
                                            $quantiteStockTotal = $matiereStock->quantite();

                                            // Vérifier si déjà réservé pour ce devis
                                            $reservation = $devisItem->stockReservations()
                                                ->where('matiere_id', $matiereStock->id)
                                                ->where('statut', 'reserve')
                                                ->first();

                                            // Calculer le stock réservé par LES AUTRES devis (exclure le devis actuel)
                                            $stockReserveAutresDevis = \App\Models\DevisStockReservation::where('matiere_id', $matiereStock->id)
                                                ->where('statut', 'reserve')
                                                ->where('devis_tuyauterie_id', '!=', $devisItem->id)
                                                ->sum('quantite_reservee');

                                            // Stock réellement disponible = Stock total - Réservations des autres devis
                                            $quantiteDisponible = $quantiteStockTotal - $stockReserveAutresDevis;
                                            $quantiteDisponible = max(0, $quantiteDisponible); // Ne jamais être négatif

                                            $materiau['stock_disponible'] = $quantiteDisponible;
                                            $materiau['stock_total'] = $quantiteStockTotal;
                                            $materiau['stock_reserve_autres'] = $stockReserveAutresDevis;
                                            $materiau['stock_suffisant'] = $quantiteDisponible >= $quantiteAVerifier;
                                            $materiau['matiere_id'] = $matiereStock->id;
                                            $materiau['reservation'] = $reservation;

                                            if(!$materiau['stock_suffisant']) {
                                                $stockManquant = true;
                                            }
                                        } else {
                                            $materiau['stock_disponible'] = null;
                                            $materiau['stock_suffisant'] = false;
                                            $materiau['matiere_id'] = null;
                                            $materiau['reservation'] = null;
                                            $stockManquant = true;
                                        }
                                    }
                                @endphp

                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                    <!-- En-tête du devis -->
                                    <div class="bg-gray-50 dark:bg-gray-750 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3">
                                                    <a href="{{ route('devis_tuyauterie.show', $devisItem) }}" class="text-lg font-semibold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                        {{ $devisItem->reference_projet }}
                                                    </a>
                                                    @if($stockManquant)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            Stock insuffisant
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            Stock OK
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">Client:</span>
                                                        <span class="ml-2 font-medium">{{ $devisItem->client_nom ?? 'N/A' }}</span>
                                                    </div>
                                                    @if($devisItem->affaire)
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Affaire:</span>
                                                            <a href="{{ route('affaires.show', $devisItem->affaire) }}" class="ml-2 font-medium text-blue-600 hover:underline">
                                                                {{ $devisItem->affaire->code }} - {{ $devisItem->affaire->nom }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <span class="text-gray-500 dark:text-gray-400">Date émission:</span>
                                                        <span class="ml-2 font-medium">{{ $devisItem->date_emission?->format('d/m/Y') ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                                    {{ number_format($devisItem->total_ttc, 2, ',', ' ') }} €
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">TTC</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tableau des matières -->
                                    @if(!empty($materiaux))
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-750">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Matière
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Quantité nécessaire
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Stock disponible
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Manque
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Statut
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    @foreach($materiaux as $materiau)
                                                        <tr class="{{ $materiau['stock_suffisant'] ? 'bg-white dark:bg-gray-800' : 'bg-red-50 dark:bg-red-900/20' }}">
                                                            <td class="px-6 py-4">
                                                                <div class="flex flex-col">
                                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                        {{ $materiau['designation'] }}
                                                                    </div>
                                                                    @if($materiau['matiere_id'])
                                                                        <a href="{{ route('matieres.show', $materiau['matiere_id']) }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                                            Voir la fiche matière
                                                                        </a>
                                                                    @else
                                                                        <span class="text-xs text-red-600 dark:text-red-400">Non trouvé en stock</span>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                                <div class="flex flex-col">
                                                                    <div>{{ number_format($materiau['quantite_totale'], 2, ',', ' ') }} {{ $materiau['unite'] }}</div>
                                                                    @if($materiau['quantite_matiere_totale'] > 0)
                                                                        <div class="text-xs text-green-600 dark:text-green-400 font-semibold mt-1">
                                                                            → {{ number_format($materiau['quantite_matiere_totale'], 2, ',', ' ') }} {{ $materiau['unite_matiere'] }} de matière
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 text-sm">
                                                                @if($materiau['stock_disponible'] !== null)
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="{{ $materiau['stock_suffisant'] ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} font-medium">
                                                                            {{ number_format($materiau['stock_disponible'], 2, ',', ' ') }} {{ $materiau['unite_matiere'] ?? $materiau['unite'] }}
                                                                        </span>
                                                                        @if($materiau['stock_reserve_autres'] > 0)
                                                                            <x-tooltip position="top">
                                                                                <x-slot name="slot_item">
                                                                                    <svg class="w-4 h-4 text-orange-500 dark:text-orange-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                                    </svg>
                                                                                </x-slot>
                                                                                <x-slot name="slot_tooltip">
                                                                                    <div class="text-left text-xs space-y-1">
                                                                                        <div class="font-semibold border-b border-gray-300 dark:border-gray-600 pb-1">Détail du stock :</div>
                                                                                        <div class="flex justify-between gap-3">
                                                                                            <span>Stock total :</span>
                                                                                            <span class="font-medium">{{ number_format($materiau['stock_total'], 2, ',', ' ') }}</span>
                                                                                        </div>
                                                                                        <div class="flex justify-between gap-3 text-orange-600 dark:text-orange-400">
                                                                                            <span>Réservé (autres devis) :</span>
                                                                                            <span class="font-medium">-{{ number_format($materiau['stock_reserve_autres'], 2, ',', ' ') }}</span>
                                                                                        </div>
                                                                                        <div class="flex justify-between gap-3 border-t border-gray-300 dark:border-gray-600 pt-1 font-semibold">
                                                                                            <span>Disponible :</span>
                                                                                            <span>{{ number_format($materiau['stock_disponible'], 2, ',', ' ') }}</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </x-slot>
                                                                            </x-tooltip>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <span class="text-gray-500 dark:text-gray-400">-</span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 text-sm">
                                                                @if($materiau['stock_disponible'] !== null && !$materiau['stock_suffisant'])
                                                                    <span class="text-red-600 dark:text-red-400 font-medium">
                                                                        {{ number_format($materiau['quantite_a_verifier'] - $materiau['stock_disponible'], 2, ',', ' ') }} {{ $materiau['unite_matiere'] ?? $materiau['unite'] }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-gray-500 dark:text-gray-400">-</span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                @if($materiau['stock_disponible'] === null)
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                                        Non référencé
                                                                    </span>
                                                                @elseif($materiau['reservation'])
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                        Réservé ({{ number_format($materiau['reservation']->quantite_reservee, 2, ',', ' ') }} {{ $materiau['unite'] }})
                                                                    </span>
                                                                @elseif($materiau['stock_suffisant'])
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                                        ✓ Disponible
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                                        ✗ Insuffisant
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                                @if($materiau['reservation'])
                                                                    <span class="text-xs text-purple-600 dark:text-purple-400 font-medium">Stock réservé</span>
                                                                @elseif($materiau['stock_suffisant'] && $materiau['matiere_id'])
                                                                    <form method="POST" action="{{ route('matieres.assigner_stock_devis') }}" class="inline">
                                                                        @csrf
                                                                        <input type="hidden" name="devis_id" value="{{ $devisItem->id }}">
                                                                        <input type="hidden" name="matiere_id" value="{{ $materiau['matiere_id'] }}">
                                                                        <input type="hidden" name="quantite" value="{{ $materiau['quantite_a_verifier'] }}">
                                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors">
                                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                            </svg>
                                                                            Assigner
                                                                        </button>
                                                                    </form>
                                                                @elseif(!$materiau['stock_suffisant'] && $materiau['matiere_id'])
                                                                    <div class="flex gap-2">
                                                                        <a href="{{ route('ddp.create', ['matiere_id' => $materiau['matiere_id'], 'quantite' => $materiau['quantite_a_verifier'] - ($materiau['stock_disponible'] ?? 0)]) }}"
                                                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors">
                                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                            </svg>
                                                                            DDP
                                                                        </a>
                                                                        <a href="{{ route('cde.create', ['matiere_id' => $materiau['matiere_id'], 'quantite' => $materiau['quantite_a_verifier'] - ($materiau['stock_disponible'] ?? 0)]) }}"
                                                                           class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors">
                                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                                            </svg>
                                                                            Commander
                                                                        </a>
                                                                    </div>
                                                                @elseif(!$materiau['stock_suffisant'] && !$materiau['matiere_id'])
                                                                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">Non disponible</span>
                                                                @else
                                                                    <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            Aucune fourniture trouvée dans ce devis
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
