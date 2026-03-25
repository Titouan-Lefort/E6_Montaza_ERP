 <x-app-layout>
    @section('title', 'Factures')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Factures
            </h2>
            <div class="flex flex-wrap gap-2 items-center">
                <a href="{{ route('reparation.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour aux réparations
                </a>
                @if (Auth::user()->hasPermission('gerer_les_factures_reparations'))
                    <a href="{{ route('reparation.facture.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter une factures
                    </a>
                @endif
            </div>
        </div>
    </x-slot>
    <div class="py-8 ">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 ">
            <div class="bg-white dark:bg-gray-800 sm:rounded-lg shadow-md">
                <!-- Filtres et recherche -->
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" action="{{ route('reparation.facture.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Recherche -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recherche</label>
                                <input type="text" name="search" placeholder="Numéro facture, réparation, matériel..."
                                    value="{{ request('search') }}"
                                    oninput="debounceSubmit(this.form)"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500">
                            </div>

                            <!-- Tri par -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trier par</label>
                                <select name="sort_by" onchange="this.form.submit()"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500">
                                    <option value="date" {{ request('sort_by') === 'date' ? 'selected' : '' }}>Date d'émission</option>
                                    <option value="numero" {{ request('sort_by') === 'numero' ? 'selected' : '' }}>Numéro de facture</option>
                                    <option value="montant" {{ request('sort_by') === 'montant' ? 'selected' : '' }}>Montant</option>
                                </select>
                            </div>

                            <!-- Ordre de tri -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ordre</label>
                                <select name="sort_order" onchange="this.form.submit()"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500">
                                    <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Décroissant</option>
                                    <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Croissant</option>
                                </select>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="flex gap-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Rechercher
                            </button>
                            <a href="{{ route('reparation.facture.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>

                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        @if($factures->isEmpty())
                            <div class="text-center py-12 px-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-4 text-gray-500 dark:text-gray-400 text-lg">Aucune facture</p>
                            </div>
                        @else
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Numéro de facture</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date d'émission</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Montant total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Numéro de la réparation associée</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($factures as $facture)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <a href="{{ route('reparation.facture.show', $facture->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                    {{ $facture->numero_facture ?? '-' }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $facture->date_emission ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400 font-mono">
                                                {{ $facture->montant_total ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400 font-mono">
                                                {{ $facture->reparation_id ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <a href="{{ route('reparation.facture.show', $facture->id) }}"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Consulter
                                                </a>
                                                @if (Auth::user()->hasPermission('gerer_les_factures_reparations'))
                                                    <a href="{{ route('reparation.facture.edit', $facture->id) }}"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Modifier
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <div class="mt-4 flex justify-center items-center pb-3 pagination">
                    <div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        //       document.addEventListener('alpine:init', () => {
        //     window.addEventListener('open-modal', function(e) {
        //         if (e.detail === 'create-facture-modal') {
        //             const modalBody = document.getElementById('create-facture-modal-body');
        //             fetch("{{ route('reparation.facture.create') }}")
        //                 .then(response => response.text())
        //                 .then(html => {
        //                     modalBody.innerHTML = html;
        //                     attachCreateFormListener();
        //                 });
        //         }
        //     });
        // });

        // // Fonction JS pour envoyer une requête DELETE via fetch (utilise le token CSRF)
        // function confirmAndDelete(id) {
        //     if (!confirm('Voulez-vous vraiment désactiver cette facture ?')) {
        //         return;
        //     }

        //     const url = `{{ url('/reparation/facture/destroy') }}/${id}`;
        //     fetch(url, {
        //         method: 'DELETE',
        //         headers: {
        //             'X-CSRF-TOKEN': '{{ csrf_token() }}',
        //             'Accept': 'application/json',
        //             'Content-Type': 'application/json'
        //         },
        //     }).then(response => {
        //         if (response.ok) {
        //             // reload la page pour voir la mise à jour
        //             window.location.reload();
        //         } else {
        //             return response.text().then(text => { throw new Error(text || 'Erreur'); });
        //         }
        //     }).catch(err => {
        //         console.error(err);
        //         alert('Une erreur est survenue lors de la suppression. Vérifie la console.');
        //     });
        // }

        let timeout = null;
        function debounceSubmit(form) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                form.submit();
            }, 500);
        }
    </script>
</x-app-layout>

