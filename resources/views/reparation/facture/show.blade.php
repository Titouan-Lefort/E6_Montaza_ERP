<x-app-layout>
    @section('title', 'Détails de la facture')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Facture #{{ $facture->numero_facture }}
            </h2>
            <div class="mt-4 sm:mt-0 flex space-x-2">
                <a href="{{ route('reparation.facture.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </a>
                @if (Auth::user()->hasPermission('gerer_les_factures_reparations'))
                    <a href="{{ route('reparation.facture.edit', $facture->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Informations principales -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Informations de la facture</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Numéro de facture -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Numéro de facture</label>
                            <p class="text-base text-gray-900 dark:text-gray-100 font-mono">{{ $facture->numero_facture }}</p>
                        </div>

                        <!-- Date d'émission -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date d'émission</label>
                            <p class="text-base text-gray-900 dark:text-gray-100">{{ $facture->date_emission ? $facture->date_emission->format('d/m/Y') : '-' }}</p>
                        </div>

                        <!-- Montant total -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Montant total</label>
                            <p class="text-base text-gray-900 dark:text-gray-100 font-mono">{{ number_format($facture->montant_total, 2, ',', ' ') }} €</p>
                        </div>

                        <!-- Réparation associée -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Réparation associée</label>
                            @if ($facture->reparation)
                                <a href="{{ route('reparation.show', $facture->reparation->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    Réparation #{{ $facture->reparation->id }}
                                </a>
                            @else
                                <p class="text-gray-500 dark:text-gray-400">-</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails du matériel -->
            @if ($facture->reparation && $facture->reparation->materiel)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Matériel associé</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Référence -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Référence</label>
                                <p class="text-base text-gray-900 dark:text-gray-100">{{ $facture->reparation->materiel->reference }}</p>
                            </div>

                            <!-- Désignation -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Désignation</label>
                                <p class="text-base text-gray-900 dark:text-gray-100">{{ $facture->reparation->materiel->designation }}</p>
                            </div>

                            <!-- Numéro de série -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Numéro de série</label>
                                <p class="text-base text-gray-900 dark:text-gray-100 font-mono">{{ $facture->reparation->materiel->numero_serie ?? '-' }}</p>
                            </div>

                            <!-- Statut -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Statut du matériel</label>
                                <span class="inline-block px-3 py-1 text-sm font-medium rounded-full
                                    @if ($facture->reparation->materiel->status === 'actif') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                    @elseif ($facture->reparation->materiel->status === 'inactif') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                    @elseif ($facture->reparation->materiel->status === 'maintenance') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                    @else bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200
                                    @endif
                                ">
                                    {{ ucfirst($facture->reparation->materiel->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        @if ($facture->reparation->materiel->description)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                <p class="text-base text-gray-900 dark:text-gray-100">{{ $facture->reparation->materiel->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Détails de la réparation -->
            @if ($facture->reparation)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Détails de la réparation</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Statut -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Statut</label>
                                <span class="inline-block px-3 py-1 text-sm font-medium rounded-full
                                    @if ($facture->reparation->status === 'pending') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                    @elseif ($facture->reparation->status === 'in_progress') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                    @elseif ($facture->reparation->status === 'completed' || $facture->reparation->status === 'archived') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                    @else bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200
                                    @endif
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $facture->reparation->status)) }}
                                </span>
                            </div>

                            <!-- Demandeur -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Demandeur</label>
                                <p class="text-base text-gray-900 dark:text-gray-100">{{ $facture->reparation->user->name ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Description -->
                        @if ($facture->reparation->description)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description du problème</label>
                                <p class="text-base text-gray-900 dark:text-gray-100">{{ $facture->reparation->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Boutons d'action -->
            <div class="mt-6 flex justify-between">
                <a href="{{ route('reparation.facture.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
