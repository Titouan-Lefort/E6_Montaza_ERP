<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div class="flex items-center space-x-2">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('administration.index') }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Administration') !!}</a>
                    >>
                </h2>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Anciens Employés') }}
                    </h2>
                </div>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center">
                <a href="{{ route('personnel.index') }}" class="btn sm:ml-4 mb-2 sm:mb-0">
                    {{ __('Employés actifs') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" id="container">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-800 dark:border-green-600 dark:text-green-200" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Message informatif -->
            <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        Cette liste contient automatiquement tous les employés ayant le statut "Parti".
                        Lorsqu'un employé est marqué comme parti, il disparaît de la
                        <a href="{{ route('personnel.index') }}" class="font-semibold underline hover:text-blue-900 dark:hover:text-blue-100">
                            liste principale
                        </a>
                        et apparaît ici.
                    </p>
                </div>
            </div>

            <!-- Filtres -->
            <div class="mb-6 bg-white dark:bg-gray-800 shadow-xs sm:rounded-lg p-6">
                <form method="GET" action="{{ route('personnel.anciens-employes') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rechercher</label>
                        <input type="text" name="search" placeholder="Nom, prénom, matricule..." value="{{ request('search') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Raison du départ</label>
                        <select name="raison" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900">
                            <option value="">Toutes les raisons</option>
                            <option value="demission" {{ request('raison') == 'demission' ? 'selected' : '' }}>Démission</option>
                            <option value="licenciement" {{ request('raison') == 'licenciement' ? 'selected' : '' }}>Licenciement</option>
                            <option value="retraite" {{ request('raison') == 'retraite' ? 'selected' : '' }}>Retraite</option>
                            <option value="fin_contrat" {{ request('raison') == 'fin_contrat' ? 'selected' : '' }}>Fin de contrat</option>
                            <option value="mutation" {{ request('raison') == 'mutation' ? 'selected' : '' }}>Mutation</option>
                            <option value="autre" {{ request('raison') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn w-full">
                            {{ __('Filtrer') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-linear-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Matricule
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nom
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Poste
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Département
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date de départ
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Raison
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Motif
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($personnels as $personnel)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $personnel->matricule }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $personnel->prenom }} {{ $personnel->nom }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $personnel->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $personnel->poste ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $personnel->departement ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $personnel->date_depart ? $personnel->date_depart->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($personnel->raison_depart)
                                                @switch($personnel->raison_depart)
                                                    @case('demission')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                            Démission
                                                        </span>
                                                        @break
                                                    @case('licenciement')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                            Licenciement
                                                        </span>
                                                        @break
                                                    @case('retraite')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                                                            Retraite
                                                        </span>
                                                        @break
                                                    @case('fin_contrat')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                            Fin de contrat
                                                        </span>
                                                        @break
                                                    @case('mutation')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                            Mutation
                                                        </span>
                                                        @break
                                                    @case('autre')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                            Autre
                                                        </span>
                                                        @break
                                                @endswitch
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 text-xs">Non spécifié</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            @if($personnel->motif_depart)
                                                <div class="max-w-xs truncate" title="{{ $personnel->motif_depart }}">
                                                    {{ Str::limit($personnel->motif_depart, 50) }}
                                                </div>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('personnel.show', $personnel) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                Voir le profil
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                <p class="text-lg font-medium">Aucun ancien employé trouvé</p>
                                                <p class="text-sm mt-1">Il n'y a pas d'employé correspondant à vos critères de recherche</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($personnels->hasPages())
                        <div class="mt-6">
                            {{ $personnels->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="mt-6 bg-white dark:bg-gray-800 shadow-xs sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Statistiques</h3>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                    @php
                        $allAnciens = \App\Models\Personnel::where('statut', 'parti')->get();
                        $stats = [
                            'total' => $allAnciens->count(),
                            'demission' => $allAnciens->where('raison_depart', 'demission')->count(),
                            'licenciement' => $allAnciens->where('raison_depart', 'licenciement')->count(),
                            'retraite' => $allAnciens->where('raison_depart', 'retraite')->count(),
                            'fin_contrat' => $allAnciens->where('raison_depart', 'fin_contrat')->count(),
                            'mutation' => $allAnciens->where('raison_depart', 'mutation')->count(),
                        ];
                    @endphp
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Total</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['demission'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Démissions</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['licenciement'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Licenciements</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['retraite'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Retraites</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['fin_contrat'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Fins de contrat</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['mutation'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Mutations</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
