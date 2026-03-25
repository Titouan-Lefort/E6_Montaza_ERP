<x-app-layout>
    @section('title', $matiere->designation . ' - Prix ' . $fournisseur->raison_sociale)

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('matieres.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Matières</a>
                >>
                <a href="{{ route('matieres.show', $matiere->id) }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{{ $matiere->designation }}</a>
                >> Prix par fournisseur
            </h2>
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6 space-y-6">
        <!-- Carte d'information principale -->
        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-3 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-gray-100 dark:to-gray-300 bg-clip-text text-transparent">Évolution des prix</h1>
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 rounded-full px-5 py-2 flex items-center gap-2 shadow-inner text-sm font-medium">
                    <span class="text-gray-500 dark:text-gray-400">Fournisseur:</span>
                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ $fournisseur->raison_sociale }}</span>
                </div>
            </div>

            <!-- Grille pour les filtres de date -->
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-750 rounded-lg border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Filtres</h3>
                <form id="filters-form" method="GET" action="{{ route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id]) }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Filtre par période -->
                    <div>
                        <label for="periode"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Période</label>
                        <select id="periode" name="periode" class="select w-full focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les périodes</option>
                            <option value="today" {{ request('periode') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                            <option value="week" {{ request('periode') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                            <option value="month" {{ request('periode') == 'month' ? 'selected' : '' }}>Ce mois</option>
                            <option value="3months" {{ request('periode') == '3months' ? 'selected' : '' }}>3 derniers mois</option>
                            <option value="6months" {{ request('periode') == '6months' ? 'selected' : '' }}>6 derniers mois</option>
                            <option value="year" {{ request('periode') == 'year' ? 'selected' : '' }}>Cette année</option>
                            <option value="custom" {{ request('periode') == 'custom' ? 'selected' : '' }}>Période personnalisée</option>
                        </select>
                    </div>

                    <!-- Bouton reset -->
                    <div class="flex items-end">
                        <a href="{{ route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id]) }}" class="btn">
                            Réinitialiser
                        </a>
                    </div>

                    <!-- Filtres de période personnalisée (cachés par défaut) -->
                    <div id="custom-period" class="col-span-full mt-4 flex gap-4"
                        style="display: {{ request('periode') == 'custom' ? 'flex' : 'none' }}">
                        <div>
                            <label for="date_debut"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date de début</label>
                            <x-date-input type="date" id="date_debut" name="date_debut"
                                value="{{ request('date_debut') }}" />
                        </div>
                        <div>
                            <label for="date_fin"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date de fin</label>
                            <x-date-input type="date" id="date_fin" name="date_fin"
                                value="{{ request('date_fin') }}" />
                        </div>
                    </div>
                </form>
            </div>

            <!-- Contenu principal -->
            <div class="bg-gray-50 dark:bg-gray-750 p-6 rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-emerald-100 dark:bg-emerald-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">{{ $matiere->designation }} - Prix {{ $fournisseur->raison_sociale }}</h2>
                </div>

                @if ($fournisseurs_prix->count() == 0)
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 p-4 rounded-lg mb-6 flex items-center gap-3 border border-yellow-100 dark:border-yellow-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-yellow-600 dark:text-yellow-400 font-medium">Aucun prix n'a été enregistré pour cette matière et ce fournisseur.</p>
                    </div>
                @elseif ($fournisseurs_prix->count() == 1)
                    <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg mb-6 flex items-center gap-3 border border-blue-100 dark:border-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-blue-600 dark:text-blue-400 font-medium">Il faut plus d'un prix pour cette matière et ce fournisseur pour afficher un graphique.</p>
                    </div>
                @else
                    <p class="text-center text-gray-500 dark:text-gray-400 mb-4">Les prix sont affichés par ordre chronologique.</p>
                    <div class="mb-6 chart-container" style="position: relative; height:300px;">
                        <canvas id="myChart"></canvas>
                    </div>
                @endif

                <!-- Tableau des prix -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Historique des prix
                        @if(request('periode'))
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                - {{ ucfirst(str_replace('_', ' ', request('periode'))) }}
                            </span>
                        @endif
                    </h3>
                    <button x-data x-on:click.prevent="$dispatch('open-modal','add-prix')" class="btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Ajouter un prix
                    </button>
                </div>

                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($fournisseurs_prix_filtered as $fournisseur_prix)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-4 py-3 whitespace-nowrap font-semibold text-green-600 dark:text-green-400">
                                        {{ formatNumberArgent($fournisseur_prix->prix_unitaire) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                        {{ formatDate($fournisseur_prix->date) }}
                                        @if ($fournisseur_prix->cde_ligne_id)
                                            <button
                                                onclick="window.open('{{ route('cde.show', $fournisseur_prix->cde->id) }}', '_blank');"
                                                class="inline-flex items-center px-3 py-2 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors duration-200 cursor-pointer border border-blue-200 dark:border-blue-700 ml-2"
                                                title="Voir la commande liée">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                {{ $fournisseur_prix->cde->code }}
                                            </button>
                                        @elseif ($fournisseur_prix->ddp_ligne_fournisseur_id)
                                            <button
                                                onclick="window.open('{{ route('ddp.show', $fournisseur_prix->ddpLigneFournisseur->ddpLigne->ddp->id) }}', '_blank');"
                                                class="inline-flex items-center px-3 py-2 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200 hover:bg-emerald-200 dark:hover:bg-emerald-800 transition-colors duration-200 cursor-pointer border border-emerald-200 dark:border-emerald-700 ml-2"
                                                title="Voir la demande de prix liée">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                {{ $fournisseur_prix->ddp()->code }}
                                            </button>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button x-data x-on:click.prevent="$dispatch('open-modal','edit-prix-{{ $fournisseur_prix->id }}')"
                                                class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Modifier
                                            </button>
                                            <button x-data x-on:click.prevent="$dispatch('open-modal','delete-prix-{{ $fournisseur_prix->id }}')"
                                                class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Supprimer
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400" colspan="3">
                                        @if(request('periode'))
                                            Aucun prix enregistré pour cette période
                                        @else
                                            Aucun prix enregistré
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour ajouter un prix -->
    <x-modal name="add-prix">
        <div class="p-6 bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Ajouter un prix</h2>
                <button x-on:click="$dispatch('close')"
                    class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('matieres.show_prix.store', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id]) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <x-input-label for="prix_unitaire" :value="__('Prix unitaire')" />
                    <x-text-input type="number" name="prix_unitaire" id="prix_unitaire"
                        class="mt-1 block w-full" step="0.01" min="0.01"
                        placeholder="0.00" required />
                    <x-input-error :messages="$errors->get('prix_unitaire')" class="mt-2" />
                </div>
                <div class="mb-4">
                    <x-input-label for="date" :value="__('Date')" />
                    <x-date-input type="date" name="date" id="date"
                        class="mt-1 block w-fit"
                        value="{{ date('Y-m-d') }}" required />
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                </div>
                <div class="flex justify-between gap-4">
                    <button type="button" x-on:click="$dispatch('close')"
                        class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn">Ajouter le prix</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modaux pour modifier chaque prix -->
    @foreach ($fournisseurs_prix as $fournisseur_prix)
        <x-modal name="edit-prix-{{ $fournisseur_prix->id }}">
            <div class="p-6 bg-white dark:bg-gray-800">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Modifier le prix</h2>
                    <button x-on:click="$dispatch('close')"
                        class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('matieres.show_prix.update', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id, 'prix' => $fournisseur_prix->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <x-input-label for="edit_prix_unitaire_{{ $fournisseur_prix->id }}" :value="__('Prix unitaire')" />
                        <x-text-input type="number" name="prix_unitaire" id="edit_prix_unitaire_{{ $fournisseur_prix->id }}"
                            class="mt-1 block w-full" step="0.01" min="0.01"
                            value="{{ $fournisseur_prix->prix_unitaire }}" required />
                        <x-input-error :messages="$errors->get('prix_unitaire')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="edit_date_{{ $fournisseur_prix->id }}" :value="__('Date')" />
                        <x-date-input type="date" name="date" id="edit_date_{{ $fournisseur_prix->id }}"
                            class="mt-1 block w-fit"
                            value="{{ \Carbon\Carbon::parse($fournisseur_prix->date)->format('Y-m-d') }}" required />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>
                    <div class="flex justify-between gap-4">
                        <button type="button" x-on:click="$dispatch('close')"
                            class="btn-secondary">Annuler</button>
                        <button type="submit" class="btn">Modifier le prix</button>
                    </div>
                </form>
            </div>
        </x-modal>

        <!-- Modal de confirmation de suppression -->
        <x-modal name="delete-prix-{{ $fournisseur_prix->id }}">
            <div class="p-6 bg-white dark:bg-gray-800">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Confirmer la suppression</h2>
                    <button x-on:click="$dispatch('close')"
                        class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-6">
                    <div class="bg-red-50 dark:bg-red-900/30 p-4 rounded-lg mb-4 flex items-center gap-3 border border-red-100 dark:border-red-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <div>
                            <p class="text-red-800 dark:text-red-200 font-semibold">Attention</p>
                            <p class="text-red-700 dark:text-red-300">Cette action est irréversible.</p>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">
                        Êtes-vous sûr de vouloir supprimer le prix de <strong>{{ formatNumberArgent($fournisseur_prix->prix_unitaire) }}</strong>
                        du <strong>{{ formatDate($fournisseur_prix->date) }}</strong> ?
                    </p>
                </div>
                <div class="flex justify-between gap-4">
                    <button type="button" x-on:click="$dispatch('close')"
                        class="btn-secondary">Annuler</button>
                    <form action="{{ route('matieres.show_prix.delete', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id, 'prix' => $fournisseur_prix->id]) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white">
                            Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
        </x-modal>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('filters-form');
            const periodeSelect = document.getElementById('periode');
            const customPeriod = document.getElementById('custom-period');
            const dateDebut = document.getElementById('date_debut');
            const dateFin = document.getElementById('date_fin');

            // Fonction pour soumettre le formulaire automatiquement
            function submitForm() {
                form.submit();
            }

            // Gérer le changement de période
            periodeSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customPeriod.style.display = 'flex';
                } else {
                    customPeriod.style.display = 'none';
                    // Si ce n'est pas une période personnalisée, soumettre immédiatement
                    submitForm();
                }
            });

            // Soumettre lors du changement des dates personnalisées
            if (dateDebut) {
                dateDebut.addEventListener('change', function() {
                    if (periodeSelect.value === 'custom') {
                        submitForm();
                    }
                });
            }

            if (dateFin) {
                dateFin.addEventListener('change', function() {
                    if (periodeSelect.value === 'custom') {
                        submitForm();
                    }
                });
            }

            @if($fournisseurs_prix_filtered->count() > 1)
            const ctx = document.getElementById('myChart').getContext('2d');

            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($dates_filtered),
                    datasets: [{
                        label: 'Prix sur le temps',
                        data: @json($prix_filtered),
                        borderColor: '#4F46E5', // Indigo-600
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true,
                        pointBackgroundColor: '#4F46E5',
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'hour',
                                displayFormats: {
                                    hour: 'yyyy-MM-dd HH:mm',
                                },
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            type: 'linear',
                            grid: {
                                borderDash: [2]
                            }
                        }
                    }
                }
            });
            @endif
        });
    </script>
</x-app-layout>
