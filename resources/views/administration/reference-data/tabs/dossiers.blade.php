<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Dossiers standards</h3>
        <button x-data x-on:click="$dispatch('open-modal', 'create-dossier-standard')"
                class="btn bg-blue-600 hover:bg-blue-700 text-white">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Ajouter un dossier
        </button>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Nb standards</th>
                        <th>Créé le</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($dossiersStandards as $dossier)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $dossier->nom }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                    {{ $dossier->standards_count }} standard(s)
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $dossier->created_at ? $dossier->created_at->format('d/m/Y') : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button x-data x-on:click="$dispatch('open-modal', 'edit-dossier-standard-{{ $dossier->id }}')"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Modifier">
                                        <x-icons.edit size="1.2" />
                                    </button>
                                    <button x-data x-on:click="$dispatch('open-modal', 'delete-dossier-standard-{{ $dossier->id }}')"
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

        <div class="mt-6">
            {{ $dossiersStandards->appends(['tab' => 'dossiers'])->links() }}
        </div>
    </div>
</div>
