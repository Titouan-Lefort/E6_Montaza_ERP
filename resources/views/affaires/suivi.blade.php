<x-app-layout>
    @section('title', 'Suivi d\'affaires')
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <span class="p-2 bg-purple-100 dark:bg-purple-900/30 text-purple-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </span>
                {{ __('Tableau de Suivi d\'Affaires') }}
            </h2>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <form method="GET" action="{{ route('affaires.suivi') }}" class="flex flex-col sm:flex-row gap-2 w-full md:w-auto items-center">
                    <!-- Search -->
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}"
                            oninput="debounceSubmit(this.form)"
                            class="pl-10 w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-100 text-sm">
                    </div>

                    <!-- Filtre statut -->
                    <select name="statut" onchange="this.form.submit()" class="w-full sm:w-40 rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-100 text-sm">
                        <option value="">{{ __('Tous statuts') }}</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>{{ __('En attente') }}</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>{{ __('En cours') }}</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>{{ __('Terminé') }}</option>
                        <option value="archive" {{ request('statut') == 'archive' ? 'selected' : '' }}>{{ __('Archivé') }}</option>
                    </select>

                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    @if(request('direction'))
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                    @endif
                </form>

                <div class="flex items-center gap-2">
                    <a href="{{ route('affaires.index') }}" class="btn-secondary whitespace-nowrap flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        {{ __('Cartes') }}
                    </a>
                    <a href="{{ route('affaires.planning') }}" class="btn-secondary whitespace-nowrap flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ __('Planning') }}
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- KPI Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $kpis['total'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
                    <div class="text-xs font-medium text-blue-500 uppercase tracking-wider">En cours</div>
                    <div class="mt-1 text-2xl font-bold text-blue-600">{{ $kpis['en_cours'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">En attente</div>
                    <div class="mt-1 text-2xl font-bold text-gray-600 dark:text-gray-300">{{ $kpis['en_attente'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
                    <div class="text-xs font-medium text-green-500 uppercase tracking-wider">Terminées</div>
                    <div class="mt-1 text-2xl font-bold text-green-600">{{ $kpis['terminees'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
                    <div class="text-xs font-medium text-red-500 uppercase tracking-wider">En retard</div>
                    <div class="mt-1 text-2xl font-bold text-red-600">{{ $kpis['en_retard'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
                    <div class="text-xs font-medium text-orange-500 uppercase tracking-wider">Budget dépassé</div>
                    <div class="mt-1 text-2xl font-bold text-orange-600">{{ $kpis['budget_depasse'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Budget total</div>
                    <div class="mt-1 text-lg font-bold text-gray-900 dark:text-white">{{ number_format($kpis['budget_total'], 0, ',', ' ') }} €</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Engagé total</div>
                    <div class="mt-1 text-lg font-bold {{ $kpis['engage_total'] > $kpis['budget_total'] ? 'text-red-600' : 'text-green-600' }}">{{ number_format($kpis['engage_total'], 0, ',', ' ') }} €</div>
                </div>
            </div>

            <!-- Tableau principal de suivi -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                @php
                                    $columns = [
                                        'code' => 'Code',
                                        'nom' => 'Affaire',
                                        'statut' => 'Statut',
                                        'date_debut' => 'Début',
                                        'date_fin_prevue' => 'Échéance',
                                        'budget' => 'Budget',
                                        'total_ht' => 'Engagé',
                                        'ecart' => 'Écart',
                                        'progression' => '% Budget',
                                    ];
                                @endphp
                                @foreach($columns as $colKey => $colLabel)
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors select-none"
                                        onclick="sortTable('{{ $colKey }}')">
                                        <div class="flex items-center gap-1">
                                            {{ $colLabel }}
                                            @if($sort === $colKey)
                                                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($direction === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    @endif
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                @endforeach
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Délai</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">CDE</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">DDP</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mat.</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pers.</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($affaires as $affaire)
                                @php
                                    $budget = $affaire->budget ?? 0;
                                    $totalHt = $affaire->total_ht ?? 0;
                                    $ecart = $budget - $totalHt;
                                    $progression = $budget > 0 ? min(100, round(($totalHt / $budget) * 100, 1)) : 0;
                                    $isOverBudget = $budget > 0 && $totalHt > $budget;

                                    $isDelayed = $affaire->statut === 'en_cours'
                                        && $affaire->date_fin_prevue
                                        && \Carbon\Carbon::parse($affaire->date_fin_prevue)->lt(now());

                                    $joursRestants = null;
                                    $delaiLabel = '-';
                                    $delaiColor = 'text-gray-400';
                                    if ($affaire->date_fin_prevue) {
                                        $fin = \Carbon\Carbon::parse($affaire->date_fin_prevue);
                                        if ($affaire->statut === 'termine' || $affaire->statut === 'archive') {
                                            $delaiLabel = 'Clos';
                                            $delaiColor = 'text-gray-400';
                                        } elseif ($fin->lt(now())) {
                                            $joursRetard = (int) $fin->diffInDays(now());
                                            $delaiLabel = '-' . $joursRetard . 'j';
                                            $delaiColor = 'text-red-600 font-bold';
                                        } else {
                                            $joursRestants = (int) now()->diffInDays($fin);
                                            $delaiLabel = '+' . $joursRestants . 'j';
                                            $delaiColor = $joursRestants <= 7 ? 'text-orange-500 font-semibold' : 'text-green-600';
                                        }
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer {{ $isDelayed ? 'bg-red-50/50 dark:bg-red-900/10' : '' }} {{ $isOverBudget ? 'bg-orange-50/50 dark:bg-orange-900/10' : '' }}"
                                    onclick="window.location='{{ route('affaires.suivi_detail', $affaire) }}'">
                                    <!-- Code -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-block px-2 py-1 rounded text-xs font-mono font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            {{ $affaire->code }}
                                        </span>
                                    </td>

                                    <!-- Nom -->
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white max-w-xs truncate" title="{{ $affaire->nom }}">
                                            {{ $affaire->nom }}
                                        </div>
                                        @if($affaire->description)
                                            <div class="text-xs text-gray-400 dark:text-gray-500 truncate max-w-xs" title="{{ $affaire->description }}">
                                                {{ Str::limit($affaire->description, 50) }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Statut -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide bg-{{ $affaire->statut_color }}-100 text-{{ $affaire->statut_color }}-700 dark:bg-{{ $affaire->statut_color }}-900/50 dark:text-{{ $affaire->statut_color }}-300">
                                            {{ $affaire->statut_label }}
                                        </span>
                                        @if($isDelayed)
                                            <span class="inline-flex items-center ml-1 px-1.5 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300" title="En retard">
                                                ⚠
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Date début -->
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ $affaire->date_debut ? \Carbon\Carbon::parse($affaire->date_debut)->format('d/m/y') : '-' }}
                                    </td>

                                    <!-- Date fin prévue -->
                                    <td class="px-4 py-3 whitespace-nowrap text-sm {{ $isDelayed ? 'text-red-600 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                                        {{ $affaire->date_fin_prevue ? \Carbon\Carbon::parse($affaire->date_fin_prevue)->format('d/m/y') : '-' }}
                                    </td>

                                    <!-- Budget -->
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-white">
                                        {{ $budget > 0 ? number_format($budget, 0, ',', ' ') . ' €' : '-' }}
                                    </td>

                                    <!-- Engagé (Total HT) -->
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium {{ $isOverBudget ? 'text-red-600 font-bold' : 'text-gray-900 dark:text-white' }}">
                                        {{ number_format($totalHt, 0, ',', ' ') }} €
                                    </td>

                                    <!-- Écart -->
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium {{ $ecart < 0 ? 'text-red-600' : ($ecart > 0 ? 'text-green-600' : 'text-gray-400') }}">
                                        @if($budget > 0)
                                            {{ $ecart >= 0 ? '+' : '' }}{{ number_format($ecart, 0, ',', ' ') }} €
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <!-- % Budget -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($budget > 0)
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 w-16 bg-gray-100 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                                    @php
                                                        $barColor = $isOverBudget ? 'bg-red-500' : ($progression > 80 ? 'bg-orange-400' : 'bg-green-500');
                                                    @endphp
                                                    <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $progression) }}%"></div>
                                                </div>
                                                <span class="text-xs font-semibold {{ $isOverBudget ? 'text-red-600' : 'text-gray-600 dark:text-gray-400' }} w-10 text-right">
                                                    {{ $progression }}%
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <!-- Délai -->
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <span class="text-sm {{ $delaiColor }}">{{ $delaiLabel }}</span>
                                    </td>

                                    <!-- CDE -->
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        @if($affaire->cdes->count() > 0)
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300">
                                                {{ $affaire->cdes->count() }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">0</span>
                                        @endif
                                    </td>

                                    <!-- DDP -->
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        @if($affaire->ddps->count() > 0)
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300">
                                                {{ $affaire->ddps->count() }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">0</span>
                                        @endif
                                    </td>

                                    <!-- Matériels -->
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        @php
                                            $matActifs = $affaire->materiels->where('pivot.statut', '!=', 'termine')->count();
                                        @endphp
                                        @if($matActifs > 0)
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300">
                                                {{ $matActifs }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">0</span>
                                        @endif
                                    </td>

                                    <!-- Personnel -->
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        @if($affaire->personnels->count() > 0)
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300">
                                                {{ $affaire->personnels->count() }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">0</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-full p-6 mb-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Aucune affaire trouvée</h3>
                                            <p class="text-gray-500 dark:text-gray-400">Modifiez vos filtres ou créez une nouvelle affaire.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($affaires->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $affaires->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

            <!-- Légende -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
                <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Légende</h4>
                <div class="flex flex-wrap gap-4 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-gray-200"></span>
                        <span class="text-gray-600 dark:text-gray-400">En attente</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-blue-400"></span>
                        <span class="text-gray-600 dark:text-gray-400">En cours</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-green-400"></span>
                        <span class="text-gray-600 dark:text-gray-400">Terminé</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-red-400"></span>
                        <span class="text-gray-600 dark:text-gray-400">Archivé</span>
                    </div>
                    <div class="border-l border-gray-200 dark:border-gray-700 pl-4 flex items-center gap-2">
                        <span class="text-red-600 font-bold">-Xj</span>
                        <span class="text-gray-600 dark:text-gray-400">En retard de X jours</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-green-600">+Xj</span>
                        <span class="text-gray-600 dark:text-gray-400">X jours restants</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-orange-500 font-semibold">+Xj</span>
                        <span class="text-gray-600 dark:text-gray-400">≤ 7 jours restants</span>
                    </div>
                    <div class="border-l border-gray-200 dark:border-gray-700 pl-4 flex items-center gap-2">
                        <div class="w-8 bg-gray-200 dark:bg-gray-700 rounded-full h-2"><div class="bg-green-500 h-2 rounded-full" style="width:50%"></div></div>
                        <span class="text-gray-600 dark:text-gray-400">Budget normal</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 bg-gray-200 dark:bg-gray-700 rounded-full h-2"><div class="bg-orange-400 h-2 rounded-full" style="width:85%"></div></div>
                        <span class="text-gray-600 dark:text-gray-400">&gt; 80%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 bg-gray-200 dark:bg-gray-700 rounded-full h-2"><div class="bg-red-500 h-2 rounded-full" style="width:100%"></div></div>
                        <span class="text-gray-600 dark:text-gray-400">Dépassé</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sortTable(column) {
            const url = new URL(window.location.href);
            const currentSort = url.searchParams.get('sort');
            const currentDirection = url.searchParams.get('direction') || 'desc';

            url.searchParams.set('sort', column);
            if (currentSort === column) {
                url.searchParams.set('direction', currentDirection === 'asc' ? 'desc' : 'asc');
            } else {
                url.searchParams.set('direction', 'asc');
            }
            // Reset page when sorting
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        let debounceTimer;
        function debounceSubmit(form) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => form.submit(), 500);
        }
    </script>
</x-app-layout>
