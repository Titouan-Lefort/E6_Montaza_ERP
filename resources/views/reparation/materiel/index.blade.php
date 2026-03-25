 <x-app-layout>
    @section('title', 'Matériels')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Matériels
            </h2>
            <div class="flex flex-wrap gap-2 items-center">
                <a href="{{ route('reparation.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour aux réparations
                </a>
                <a href="{{ route('reparation.materiel.historique') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Accéder à l'ancien matériel
                </a>
                @if (Auth::user()->hasPermission('gerer_le_materiel'))
                    <a href="{{ route('reparation.materiel.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter du matériel
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
                    <form method="GET" action="{{ route('reparation.materiel.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Recherche -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recherche</label>
                                <input type="text" name="search" placeholder="Référence, désignation, série..."
                                    value="{{ request('search') }}"
                                    oninput="debounceSubmit(this.form)"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500">
                            </div>

                            <!-- Tri par -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trier par</label>
                                <select name="sort_by" onchange="this.form.submit()"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500">
                                    <option value="date" {{ request('sort_by') === 'date' ? 'selected' : '' }}>Date d'acquisition</option>
                                    <option value="reference" {{ request('sort_by') === 'reference' ? 'selected' : '' }}>Référence</option>
                                    <option value="designation" {{ request('sort_by') === 'designation' ? 'selected' : '' }}>Désignation</option>
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

                            <!-- Quantité par page -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quantité par pages</label>
                                <input type="number" name="nombre" value="{{ request('nombre', 50) }}" min="1" max="1000"
                                    onchange="this.form.submit()"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500">
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
                            <a href="{{ route('reparation.materiel.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>

                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        @if($materiels->isEmpty())
                            <div class="text-center py-12 px-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-4 text-gray-500 dark:text-gray-400 text-lg">Aucun matériel disponible</p>
                            </div>
                        @else
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Référence</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Désignation</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Numéro de série</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date d'acquisition</th>
                                        @if (Auth::user()->hasPermission('gerer_le_materiel'))
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($materiels as $materiel)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $materiel->reference ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $materiel->designation ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400 font-mono">
                                                {{ $materiel->numero_serie ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @php
                                                    $statusClass = match($materiel->status ?? 'actif') {
                                                        'actif' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                        'inactif' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                        'maintenance' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                        default => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                                                    };
                                                @endphp
                                                <span class="{{ $statusClass }}">
                                                    {{ ucfirst($materiel->status ?? '-') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                @if($materiel->acquisition_date)
                                                    {{ $materiel->acquisition_date->format('d/m/Y') }}
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            @if (Auth::user()->hasPermission('gerer_le_materiel'))
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                    <a href="{{ route('reparation.materiel.edit', $materiel->id) }}"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Modifier
                                                    </a>
                                                    <button onclick="confirmAndDelete({{ $materiel->id }})"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Désactiver
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <div class="mt-4 flex justify-center items-center pb-3 pagination">
                    <div>
                        {{ $materiels->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
              document.addEventListener('alpine:init', () => {
            window.addEventListener('open-modal', function(e) {
                if (e.detail === 'create-materiel-modal') {
                    const modalBody = document.getElementById('create-materiel-modal-body');
                    fetch("{{ route('reparation.materiel.create') }}")
                        .then(response => response.text())
                        .then(html => {
                            modalBody.innerHTML = html;
                            attachCreateFormListener();
                        });
                }
            });
        });

        // Fonction JS pour envoyer une requête DELETE via fetch (utilise le token CSRF)
        function confirmAndDelete(id) {
            if (!confirm('Voulez-vous vraiment désactiver ce matériel ?')) {
                return;
            }

            const url = `{{ url('/reparation/materiel/destroy') }}/${id}`;
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
            }).then(response => {
                if (response.ok) {
                    // reload la page pour voir la mise à jour
                    window.location.reload();
                } else {
                    return response.text().then(text => { throw new Error(text || 'Erreur'); });
                }
            }).catch(err => {
                console.error(err);
                alert('Une erreur est survenue lors de la suppression. Vérifie la console.');
            });
        }

        let timeout = null;
        function debounceSubmit(form) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                form.submit();
            }, 500);
        }
    </script>
</x-app-layout>

