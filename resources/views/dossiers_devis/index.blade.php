<x-app-layout>
    @section('title', 'Dossiers de Devis')
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dossiers de Devis
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('devis_tuyauterie.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Devis
                </a>
                <a href="{{ route('dossiers_devis.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nouveau Dossier
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtres et recherche -->
            <div class="mb-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4">
                <form method="GET" class="flex gap-4 items-end">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recherche</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Code, nom, référence..." class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Statut</label>
                        <select name="statut" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm">
                            <option value="">Tous</option>
                            <option value="quantitatif" {{ request('statut') == 'quantitatif' ? 'selected' : '' }}>Quantitatif</option>
                            <option value="en_devis" {{ request('statut') == 'en_devis' ? 'selected' : '' }}>En devis</option>
                            <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Liste des dossiers -->
            @if($dossiers->isEmpty())
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-8 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Aucun dossier de devis</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Commencez par créer votre premier dossier de devis.</p>
                    <a href="{{ route('dossiers_devis.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        Créer un dossier
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Affaire</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantitatifs</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Devis</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($dossiers as $dossier)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer" onclick="window.location='{{ route('dossiers_devis.show', $dossier) }}'">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $dossier->code }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $dossier->created_at->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $dossier->nom }}</div>
                                        @if($dossier->reference_projet)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $dossier->reference_projet }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($dossier->affaire)
                                            <a href="{{ route('affaires.show', $dossier->affaire) }}" onclick="event.stopPropagation()" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                {{ $dossier->affaire->code }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($dossier->societe)
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $dossier->societe->nom }}</div>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $badgeColors = [
                                                'quantitatif' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                                'en_devis' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
                                                'valide' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                            ];
                                            $badgeColor = $badgeColors[$dossier->statut] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeColor }}">
                                            {{ ucfirst($dossier->statut) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $dossier->quantitatifs->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $dossier->devisTuyauteries->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('dossiers_devis.show', $dossier) }}" onclick="event.stopPropagation()" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 mr-3">
                                            Voir
                                        </a>
                                        <a href="{{ route('dossiers_devis.edit', $dossier) }}" onclick="event.stopPropagation()" class="text-amber-600 hover:text-amber-900 dark:text-amber-400">
                                            Modifier
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $dossiers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
