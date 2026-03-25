<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Formes juridiques</h3>
        <button x-data x-on:click="$dispatch('open-modal', 'create-forme-juridique')"
                class="btn bg-blue-600 hover:bg-blue-700 text-white">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Ajouter une forme juridique
        </button>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Nombre de sociétés</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($formesJuridiques as $forme)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-gray-900 dark:text-gray-100">
                                    {{ $forme->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $forme->nom }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    {{ $forme->societes()->count() ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button x-data x-on:click="$dispatch('open-modal', 'edit-forme-juridique-{{ $forme->id }}')"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Modifier">
                                        <x-icons.edit size="1.2" />
                                    </button>
                                    <button x-data x-on:click="$dispatch('open-modal', 'delete-forme-juridique-{{ $forme->id }}')"
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
            {{ $formesJuridiques->appends(['tab' => 'formes'])->links() }}
        </div>
    </div>
</div>
