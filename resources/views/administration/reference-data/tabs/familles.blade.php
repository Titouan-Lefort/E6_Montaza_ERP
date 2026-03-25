<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Familles ({{ $familles->count() }})</h3>
            <button x-data x-on:click="$dispatch('open-modal', 'create-famille')"
                    class="btn bg-blue-600 hover:bg-blue-700 text-white">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Ajouter une famille
            </button>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Sous-familles</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($familles as $famille)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $famille->nom }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        @if($famille->sousFamilles->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($famille->sousFamilles->take(2) as $sousFamille)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        {{ $sousFamille->nom }}
                                                    </span>
                                                @endforeach
                                                @if($famille->sousFamilles->count() > 2)
                                                    <span class="text-gray-500 dark:text-gray-400 text-xs">
                                                        +{{ $famille->sousFamilles->count() - 2 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-xs italic">Aucune</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button x-data x-on:click="$dispatch('open-modal', 'create-sous-famille-{{ $famille->id }}')"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                title="Ajouter une sous-famille">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                        <button x-data x-on:click="$dispatch('open-modal', 'edit-famille-{{ $famille->id }}')"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                title="Modifier">
                                            <x-icons.edit size="1.2" />
                                        </button>
                                        <button x-data x-on:click="$dispatch('open-modal', 'delete-famille-{{ $famille->id }}')"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                title="Supprimer">
                                            <x-icons.delete size="1.2" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tableau des sous-familles -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Sous-familles ({{ $familles->sum(fn($f) => $f->sousFamilles->count()) }})
            </h3>
            <button x-data x-on:click="$dispatch('open-modal', 'create-sous-famille')"
                    class="btn bg-green-600 hover:bg-green-700 text-white">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Ajouter une sous-famille
            </button>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Famille</th>
                            <th>Type affichage</th>
                            <th>Nb matières</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($familles as $famille)
                            @foreach($famille->sousFamilles as $sousFamille)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $sousFamille->nom }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $famille->nom }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($sousFamille->type_affichage_stock)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $sousFamille->type_affichage_stock == 1 ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' }}">
                                                {{ $sousFamille->type_affichage_stock == 1 ? 'Quantité + Valeur' : 'Valeur uniquement' }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-xs">Par défaut</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $sousFamille->matieres_count ?? 0 }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button x-data x-on:click="$dispatch('open-modal', 'edit-sous-famille-{{ $sousFamille->id }}')"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                    title="Modifier">
                                                <x-icons.edit size="1.2" />
                                            </button>
                                            <button x-data x-on:click="$dispatch('open-modal', 'delete-sous-famille-{{ $sousFamille->id }}')"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                    title="Supprimer">
                                                <x-icons.delete size="1.2" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function loadModal(modalName) {
    fetch(`{{ route('reference-data.modal') }}?modal=${modalName}&tab=familles`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modals-container').innerHTML = html;
            // Déclencher l'ouverture de la modale
            window.dispatchEvent(new CustomEvent('open-modal', { detail: modalName }));
        });
}
</script>
