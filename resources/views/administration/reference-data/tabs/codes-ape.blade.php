<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Codes APE</h3>
        <button x-data x-on:click="$dispatch('open-modal', 'create-code-ape')"
                class="btn bg-blue-600 hover:bg-blue-700 text-white">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Ajouter un code APE
        </button>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Libellé</th>
                        <th class="text-center">Sociétés</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($codesApe as $code)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $code->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $code->nom }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $code->societes_count > 0 ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $code->societes_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button x-data x-on:click="$dispatch('open-modal', 'edit-code-ape-{{ $code->id }}')"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Modifier">
                                        <x-icons.edit size="1.2" />
                                    </button>
                                    <button x-data x-on:click="$dispatch('open-modal', 'delete-code-ape-{{ $code->id }}')"
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
            {{ $codesApe->appends(['tab' => 'codes-ape'])->links() }}
        </div>
    </div>
</div>
