<x-app-layout>
    @section('title', 'Mouvements de stock - ' . $matiere->designation)

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('matieres.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Matières</a>
                >>
                <a href="{{ route('matieres.show', $matiere->id) }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{{ $matiere->designation }}</a>
                >> Mouvements de stock
            </h2>

        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6 space-y-6">
        <!-- Carte d'information principale de la matière -->
        <div
            class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="bg-emerald-100 dark:bg-emerald-900 rounded-full p-3 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600 dark:text-emerald-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </div>
                    <div>
                        <h1
                            class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-gray-100 dark:to-gray-300 bg-clip-text text-transparent">
                            Mouvements de stock
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-400 mt-1">{{ $matiere->designation }}</p>
                    </div>
                </div>
                <div
                    class="bg-gray-100 dark:bg-gray-700 rounded-full px-5 py-2 flex items-center gap-2 shadow-inner text-sm font-medium">
                    <span class="text-gray-500 dark:text-gray-400">Référence:</span>
                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ $matiere->ref_interne }}</span>
                </div>
            </div>

            <!-- Infos résumées de la matière -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Sous Famille</p>
                    <p class="font-semibold text-lg">{{ $matiere->sousFamille->nom }}</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Stock actuel</p>
                    <p class="font-semibold text-lg"><x-stock-tooltip matiereId="{{ $matiere->id }}" /></p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total mouvements</p>
                    <p class="font-semibold text-lg text-emerald-600 dark:text-emerald-400">{{ $mouvements->total() }}
                    </p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Unité</p>
                    <p class="font-semibold text-lg">{{ $matiere->unite->nom ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Section des mouvements de stock -->
        <div
            class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
            <div class="flex items-center justify-between gap-3 mb-6">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-100 dark:bg-emerald-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 dark:text-emerald-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Historique détaillé des mouvements</h2>
                </div>

                @if ($mouvements->hasPages())
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Page {{ $mouvements->currentPage() }} sur {{ $mouvements->lastPage() }}
                    </div>
                @endif
            </div>

            <!-- Filtres -->
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-750 rounded-lg border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Filtres</h3>
                <form id="filters-form" method="GET" action="{{ route('matieres.mouvements', $matiere->id) }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Filtre par période -->
                    <div>
                        <label for="periode"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Période</label>
                        <select id="periode" name="periode" class="select">
                            <option value="">Toutes les périodes</option>
                            <option value="today" {{ request('periode') == 'today' ? 'selected' : '' }}>Aujourd'hui
                            </option>
                            <option value="week" {{ request('periode') == 'week' ? 'selected' : '' }}>Cette semaine
                            </option>
                            <option value="month" {{ request('periode') == 'month' ? 'selected' : '' }}>Ce mois
                            </option>
                            <option value="3months" {{ request('periode') == '3months' ? 'selected' : '' }}>3 derniers
                                mois</option>
                            <option value="6months" {{ request('periode') == '6months' ? 'selected' : '' }}>6 derniers
                                mois</option>
                            <option value="year" {{ request('periode') == 'year' ? 'selected' : '' }}>Cette année
                            </option>
                            <option value="custom" {{ request('periode') == 'custom' ? 'selected' : '' }}>Période
                                personnalisée</option>
                        </select>
                    </div>

                    <!-- Filtre par utilisateur -->
                    <div>
                        <label for="user_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Utilisateur</label>
                        <select id="user_id" name="user_id" class="select">
                            <option value="">Tous les utilisateurs</option>
                            @foreach ($utilisateurs as $utilisateur)
                                <option value="{{ $utilisateur->id }}"
                                    {{ request('user_id') == $utilisateur->id ? 'selected' : '' }}>
                                    {{ trim($utilisateur->first_name . ' ' . $utilisateur->last_name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre par type -->
                    <div>
                        <label for="type"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type de
                            mouvement</label>
                        <select id="type" name="type" class="select">
                            <option value="">Tous les types</option>
                            <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>Entrées
                                uniquement</option>
                            <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>Sorties
                                uniquement</option>
                        </select>
                    </div>

                    <!-- Bouton reset -->
                    <div class="flex items-end">
                        <a href="{{ route('matieres.mouvements', $matiere->id) }}" class="btn">
                            Réinitialiser
                        </a>
                    </div>


                <!-- Filtres de période personnalisée (cachés par défaut) -->
                <div id="custom-period" class="mt-4 flex gap-4"
                    style="display: {{ request('periode') == 'custom' ? 'flex' : 'none' }}">
                    <div>
                        <label for="date_debut"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date de
                            début</label>
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

            @if ($mouvements->count() > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-750">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Quantité
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Motif / Commande
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Utilisateur
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date & Heure
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($mouvements as $mouvement)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">


                                    <!-- Quantité -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($mouvement->type == 'sortie')
                                            <div class="flex items-center">
                                                <span
                                                    class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-red-100 text-red-500 dark:bg-red-900 dark:text-red-300 mr-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </span>
                                                <span class="text-red-500 dark:text-red-400 font-medium">-
                                                    {{ $mouvement->valeur_unitaire ? formatNumber($mouvement->quantite * $mouvement->valeur_unitaire) : formatNumber($mouvement->quantite) }}
                                                    {{ $matiere->unite->short }}</span>
                                                @if ($mouvement->valeur_unitaire != null)
                                                    <span class="text-gray-500 dark:text-gray-400 ml-1 text-xs">
                                                        ({{ formatNumber($mouvement->quantite) }} ×
                                                        {{ formatNumber($mouvement->valeur_unitaire) }}
                                                        {{ $matiere->unite->short }})
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="flex items-center">
                                                <span
                                                    class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-100 text-green-500 dark:bg-green-900 dark:text-green-300 mr-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7" />
                                                    </svg>
                                                </span>
                                                <span class="text-green-500 dark:text-green-400 font-medium">+
                                                    {{ $mouvement->valeur_unitaire ? formatNumber($mouvement->quantite * $mouvement->valeur_unitaire) : formatNumber($mouvement->quantite) }}
                                                    {{ $matiere->unite->short }}</span>
                                                @if ($mouvement->valeur_unitaire != null)
                                                    <span class="text-gray-500 dark:text-gray-400 ml-1 text-xs">
                                                        ({{ formatNumber($mouvement->quantite) }} ×
                                                        {{ formatNumber($mouvement->valeur_unitaire) }}
                                                        {{ $matiere->unite->short }})
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Motif / Commande fusionnés -->
                                    <td class="px-6 py-4">
                                        @if ($mouvement->cde_ligne_id && $mouvement->cdeLigne && $mouvement->cdeLigne->cde)
                                            <!-- Bouton commande -->
                                            <button
                                                onclick="window.open('{{ route('cde.show', $mouvement->cdeLigne->cde->id) }}', '_blank');"
                                                class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors duration-200 cursor-pointer border border-blue-200 dark:border-blue-700"
                                                title="Voir la commande {{ $mouvement->cdeLigne->cde->code }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                @if ($mouvement->raison && $mouvement->raison !== 'Livraison')
                                                    <!-- Motif supplémentaire sous la commande -->
                                                    <div class="max-w-xs">
                                                        @if (strlen($mouvement->raison) > 70)
                                                            <x-tooltip>
                                                                <x-slot name="slot_item">
                                                                    {{ Str::limit($mouvement->raison, 70) }}
                                                                </x-slot>
                                                                <x-slot name="slot_tooltip">
                                                                    {{ $mouvement->raison }}
                                                                </x-slot>
                                                            </x-tooltip>
                                                        @else
                                                            {{ $mouvement->raison }}
                                                        @endif
                                                    </div>
                                                @endif
                                            </button>
                                        @else
                                            <!-- Motif simple -->
                                            <div class="max-w-xs">
                                                @if (strlen($mouvement->raison) > 70)
                                                    <x-tooltip>
                                                        <x-slot name="slot_item">
                                                            <span
                                                                class="text-sm text-gray-900 dark:text-gray-100 truncate block">
                                                                {{ Str::limit($mouvement->raison, 70) }}
                                                            </span>
                                                        </x-slot>
                                                        <x-slot name="slot_tooltip">
                                                            {{ $mouvement->raison }}
                                                        </x-slot>
                                                    </x-tooltip>
                                                @else
                                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $mouvement->raison ?: 'Aucun motif' }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Utilisateur -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ trim(($mouvement->user->first_name ?? '') . ' ' . ($mouvement->user->last_name ?? 'Système')) }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Date & Heure -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $mouvement->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $mouvement->created_at->format('H:i:s') }}
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if (!$mouvement->cde_ligne_id)
                                            <div class="flex items-center space-x-2">
                                                <!-- Bouton modifier -->
                                                <button x-data x-on:click.prevent="$dispatch('open-modal','edit-mouvement-{{ $mouvement->id }}')"
                                                    class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors duration-200"
                                                    title="Modifier le mouvement">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Modifier
                                                </button>

                                                <!-- Bouton supprimer -->
                                                <button x-data x-on:click.prevent="$dispatch('open-modal','delete-mouvement-{{ $mouvement->id }}')"
                                                    class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors duration-200"
                                                    title="Supprimer le mouvement">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Lié à commande</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($mouvements->hasPages())
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Affichage de {{ $mouvements->firstItem() }} à {{ $mouvements->lastItem() }} sur
                            {{ $mouvements->total() }} résultats
                        </div>
                        <div>
                            {{ $mouvements->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Aucun mouvement de stock</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Cette matière n'a encore aucun mouvement de stock
                        enregistré.</p>
                    <a href="{{ route('matieres.show', $matiere->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-700 dark:hover:bg-emerald-600 text-white rounded-md transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Ajouter du stock
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Modales pour modifier chaque mouvement -->
    @foreach ($mouvements as $mouvement)
        @if (!$mouvement->cde_ligne_id)
            <!-- Modal de modification -->
            <x-modal name="edit-mouvement-{{ $mouvement->id }}">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Modifier le mouvement</h2>
                        <button x-on:click="$dispatch('close')"
                            class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('matieres.mouvement.modifier', [$matiere->id, $mouvement->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="edit_quantite_{{ $mouvement->id }}" :value="__('Quantité')" />
                            <x-text-input type="number" step="0.01" name="quantite" id="edit_quantite_{{ $mouvement->id }}"
                                class="mt-1 block w-full"
                                value="{{ $mouvement->quantite }}" required />
                            <x-input-error :messages="$errors->get('quantite')" class="mt-2" />
                        </div>

                        @if($matiere->typeAffichageStock() == 2)
                        <div class="mb-4">
                            <x-input-label for="edit_valeur_unitaire_{{ $mouvement->id }}" :value="__('Valeur unitaire')" />
                            <x-text-input type="number" step="0.01" name="valeur_unitaire" id="edit_valeur_unitaire_{{ $mouvement->id }}"
                                class="mt-1 block w-full"
                                value="{{ $mouvement->valeur_unitaire }}" />
                            <x-input-error :messages="$errors->get('valeur_unitaire')" class="mt-2" />
                        </div>
                        @endif

                        <div class="mb-4">
                            <x-input-label for="edit_raison_{{ $mouvement->id }}" :value="__('Motif')" />
                            <x-text-input type="text" name="raison" id="edit_raison_{{ $mouvement->id }}"
                                class="mt-1 block w-full"
                                value="{{ $mouvement->raison }}" required />
                            <x-input-error :messages="$errors->get('raison')" class="mt-2" />
                        </div>

                        <div class="flex justify-between gap-4">
                            <button type="button" x-on:click="$dispatch('close')"
                                class="btn-secondary">Annuler</button>
                            <button type="submit" class="btn">Modifier le mouvement</button>
                        </div>
                    </form>
                </div>
            </x-modal>

            <!-- Modal de confirmation de suppression -->
            <x-modal name="delete-mouvement-{{ $mouvement->id }}">
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
                                <p class="text-red-700 dark:text-red-300">Cette action est irréversible et ajustera le stock en conséquence.</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-gray-600 dark:text-gray-400">
                                <strong>Type:</strong>
                                @if($mouvement->type == 'entree')
                                    <span class="text-green-600 dark:text-green-400">Entrée</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400">Sortie</span>
                                @endif
                            </p>
                            <p class="text-gray-600 dark:text-gray-400">
                                <strong>Quantité:</strong> {{ formatNumber($mouvement->quantite) }} {{ $matiere->unite->short }}
                            </p>
                            @if($mouvement->valeur_unitaire)
                            <p class="text-gray-600 dark:text-gray-400">
                                <strong>Valeur unitaire:</strong> {{ formatNumber($mouvement->valeur_unitaire) }}
                            </p>
                            @endif
                            <p class="text-gray-600 dark:text-gray-400">
                                <strong>Motif:</strong> {{ $mouvement->raison ?: 'Aucun motif' }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-400">
                                <strong>Date:</strong> {{ $mouvement->created_at->format('d/m/Y H:i:s') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-between gap-4">
                        <button type="button" x-on:click="$dispatch('close')"
                            class="btn-secondary">Annuler</button>
                        <form action="{{ route('matieres.mouvement.supprimer', [$matiere->id, $mouvement->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white">
                                Supprimer définitivement
                            </button>
                        </form>
                    </div>
                </div>
            </x-modal>
        @endif
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filters-form');
            const periodeSelect = document.getElementById('periode');
            const userSelect = document.getElementById('user_id');
            const typeSelect = document.getElementById('type');
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

            // Soumettre lors du changement d'utilisateur ou de type
            userSelect.addEventListener('change', submitForm);
            typeSelect.addEventListener('change', submitForm);

            // Soumettre lors du changement des dates personnalisées
            dateDebut.addEventListener('change', function() {
                if (periodeSelect.value === 'custom') {
                    submitForm();
                }
            });

            dateFin.addEventListener('change', function() {
                if (periodeSelect.value === 'custom') {
                    submitForm();
                }
            });
        });

        function openEditModal(mouvementId) {
            document.getElementById('editModal' + mouvementId).classList.remove('hidden');
        }

        function closeEditModal(mouvementId) {
            document.getElementById('editModal' + mouvementId).classList.add('hidden');
        }
    </script>
</x-app-layout>
