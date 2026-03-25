<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Préparer le Devis - {{ $dossierDevis->nom }}
            </h2>
            <a href="{{ route('dossiers_devis.show', $dossierDevis) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Assigner les éléments du quantitatif aux lots du devis</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Sélectionnez le lot de destination pour chaque élément du quantitatif.
                        <span class="font-semibold">Note :</span> Vous devez remplir les lots dans l'ordre (le Lot 2 n'est disponible que si le Lot 1 contient au moins un élément, etc.).
                    </p>

                    <form id="prepare-devis-form" method="POST" action="{{ route('dossiers_devis.generer_devis', $dossierDevis) }}">
                        @csrf

                        <div class="overflow-x-auto mb-6">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lot</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Désignation</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description technique</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantité</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prix Unitaire</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($dossierDevis->quantitatifs->sortBy('ordre') as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-3">
                                                <select name="quantitatifs[{{ $item->id }}][lot]"
                                                        class="lot-select block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm"
                                                        data-quantitatif-id="{{ $item->id }}"
                                                        required>
                                                    <option value="">-- Choisir --</option>
                                                    <option value="1" selected>Lot 1</option>
                                                    <option value="2" disabled>Lot 2</option>
                                                    <option value="3" disabled>Lot 3</option>
                                                    <option value="4" disabled>Lot 4</option>
                                                    <option value="5" disabled>Lot 5</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $typeColors = [
                                                        'fourniture' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                        'main_d_oeuvre' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                        'sous_traitance' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                                        'consommable' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                                    ];
                                                    $typeLabels = [
                                                        'fourniture' => 'Fourniture',
                                                        'main_d_oeuvre' => 'Main d\'œuvre',
                                                        'sous_traitance' => 'Sous-traitance',
                                                        'consommable' => 'Consommable',
                                                    ];
                                                @endphp
                                                <span class="px-2 py-1 text-xs rounded-full {{ $typeColors[$item->type] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $typeLabels[$item->type] ?? $item->type }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ $item->designation }}
                                                @if($item->matiere)
                                                    <br><span class="text-xs text-gray-500">Matière : {{ $item->matiere->designation }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $item->description_technique ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ number_format($item->quantite, 2) }} {{ $item->unite }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ number_format($item->prix_unitaire ?? 0, 2) }} €
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-md font-medium mb-4">Titres des lots</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Personnalisez le titre de chaque lot utilisé :
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div id="lot-1-title" class="lot-title-group">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Titre du Lot 1 *</label>
                                    <input type="text" name="lot_titles[1]" value="Lot 1 : Préfabrication Atelier"
                                           class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                </div>
                                <div id="lot-2-title" class="lot-title-group hidden">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Titre du Lot 2</label>
                                    <input type="text" name="lot_titles[2]" value="Lot 2 : Montage sur Site"
                                           class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                </div>
                                <div id="lot-3-title" class="lot-title-group hidden">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Titre du Lot 3</label>
                                    <input type="text" name="lot_titles[3]" value="Lot 3 : Essais et Contrôles"
                                           class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                </div>
                                <div id="lot-4-title" class="lot-title-group hidden">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Titre du Lot 4</label>
                                    <input type="text" name="lot_titles[4]" value="Lot 4 : Finitions"
                                           class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                </div>
                                <div id="lot-5-title" class="lot-title-group hidden">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Titre du Lot 5</label>
                                    <input type="text" name="lot_titles[5]" value="Lot 5 : Divers"
                                           class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('dossiers_devis.show', $dossierDevis) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Générer le Devis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lotSelects = document.querySelectorAll('.lot-select');

            function updateLotAvailability() {
                // Compter combien d'éléments sont assignés à chaque lot
                const lotCounts = {1: 0, 2: 0, 3: 0, 4: 0, 5: 0};

                lotSelects.forEach(select => {
                    const value = parseInt(select.value);
                    if (value && lotCounts.hasOwnProperty(value)) {
                        lotCounts[value]++;
                    }
                });

                // Mettre à jour la disponibilité des options pour chaque select
                lotSelects.forEach(select => {
                    const options = select.querySelectorAll('option');

                    options.forEach(option => {
                        const lotNum = parseInt(option.value);
                        if (!lotNum) return; // Skip empty option

                        // Un lot est disponible si tous les lots précédents ont au moins 1 élément
                        let isAvailable = true;
                        for (let i = 1; i < lotNum; i++) {
                            if (lotCounts[i] === 0) {
                                isAvailable = false;
                                break;
                            }
                        }

                        // Toujours garder disponible le lot actuellement sélectionné
                        if (select.value === option.value) {
                            isAvailable = true;
                        }

                        option.disabled = !isAvailable;
                    });
                });

                // Afficher/masquer les champs de titre de lot
                for (let i = 1; i <= 5; i++) {
                    const titleGroup = document.getElementById(`lot-${i}-title`);
                    if (titleGroup) {
                        if (lotCounts[i] > 0) {
                            titleGroup.classList.remove('hidden');
                        } else {
                            // Ne masquer que si tous les lots supérieurs sont aussi vides
                            let hasHigherLots = false;
                            for (let j = i + 1; j <= 5; j++) {
                                if (lotCounts[j] > 0) {
                                    hasHigherLots = true;
                                    break;
                                }
                            }
                            if (!hasHigherLots && i > 1) {
                                titleGroup.classList.add('hidden');
                            }
                        }
                    }
                }
            }

            // Écouter les changements sur tous les selects
            lotSelects.forEach(select => {
                select.addEventListener('change', updateLotAvailability);
            });

            // Initialiser l'état au chargement
            updateLotAvailability();
        });
    </script>
</x-app-layout>
