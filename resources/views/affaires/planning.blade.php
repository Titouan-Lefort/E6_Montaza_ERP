<x-app-layout>
    @section('title', 'Planning Global des Affaires')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Planning Global') }}
            </h2>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ route('affaires.suivi') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Tableau de suivi
                </a>
                <a href="{{ route('affaires.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Retour à la liste
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filtres -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('affaires.planning') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div>
                        <label for="start" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de début</label>
                        <input type="date" name="start" id="start" value="{{ $start->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="end" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de fin</label>
                        <input type="date" name="end" id="end" value="{{ $end->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Filtrer
                    </button>
                </form>
            </div>

            <!-- Timeline / Grid View -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 overflow-x-auto">
                {{-- On génère la période complète --}}
                @php
                    $period = \Carbon\CarbonPeriod::create($start, $end);
                    $months = [];
                    foreach ($period as $date) {
                        $monthKey = $date->translatedFormat('F Y');
                        if (!isset($months[$monthKey])) {
                            $months[$monthKey] = 0;
                        }
                        $months[$monthKey]++;
                    }
                @endphp

                <table class="min-w-full border-collapse">
                    <thead>
                        <!-- Ligne des Mois -->
                        <tr>
                            <th class="sticky left-0 z-20 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-2"></th>
                            @foreach($months as $monthName => $daysCount)
                                <th colspan="{{ $daysCount }}" class="border-b border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 p-1 text-center font-bold text-gray-700 dark:text-gray-300 text-sm border-r border-gray-300 dark:border-gray-600">
                                    {{ ucfirst($monthName) }}
                                </th>
                            @endforeach
                        </tr>
                        <!-- Ligne des Jours -->
                        <tr>
                            <th class="sticky left-0 z-10 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-2 text-left text-sm font-bold text-gray-700 dark:text-gray-300 min-w-[200px]">
                                Affaire
                            </th>
                            @foreach($period as $date)
                                <th class="border-b border-gray-200 dark:border-gray-700 p-1 text-center min-w-[30px] {{ $date->isToday() ? 'bg-indigo-100 dark:bg-indigo-900' : '' }} border-r border-gray-100 dark:border-gray-700">
                                    <div class="text-[10px] font-semibold text-gray-500">{{ $date->translatedFormat('D') }}</div>
                                    <div class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $date->format('d') }}</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($planningData as $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <!-- Info Affaire -->
                                <td class="sticky left-0 z-10 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 p-2 border-r">
                                    <div class="truncate max-w-[200px]">
                                        <a href="{{ route('affaires.show', $data->affaire) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $data->affaire->code }}
                                        </a>
                                        <div class="text-xs text-gray-500 truncate">{{ $data->affaire->nom }}</div>
                                    </div>
                                </td>

                                <!-- Jours -->
                                @foreach($period as $date)
                                    @php
                                        // Vérifie si le jour est dans la plage effective
                                        // On utilise startOfDay pour comparer des dates sans l'heure
                                        $infosDay = $date->copy()->startOfDay();
                                        $startDay = $data->date_debut->copy()->startOfDay();
                                        $endDay = $data->date_fin_effective->copy()->startOfDay();

                                        $isActive = $infosDay->between($startDay, $endDay);

                                        // Gestion du style
                                        $bgClass = '';
                                        $styles = '';

                                        if ($isActive) {
                                            if ($data->is_delayed && $infosDay->gt($data->date_fin_prevue)) {
                                                // C'est du retard
                                                $bgClass = 'bg-red-500';
                                                $styles = 'background-image: repeating-linear-gradient(45deg, transparent, transparent 2px, rgba(255,255,255,0.3) 2px, rgba(255,255,255,0.3) 4px);';
                                            } else {
                                                $bgClass = 'bg-' . $data->affaire->statut_color . '-500';
                                            }
                                        }

                                        // Week-end styling (optionnel, pour repère visuel)
                                        $isWeekend = $date->isWeekend();
                                    @endphp

                                    <td class="border-b border-gray-100 dark:border-gray-700 p-0 h-10 border-r border-dashed border-gray-200 dark:border-gray-700 {{ $isWeekend ? 'bg-gray-50 dark:bg-gray-900' : '' }} relative">
                                        @if($isActive)
                                            <div class="h-6 w-full mx-auto my-auto rounded-sm {{ $bgClass }}"
                                                 style="{{ $styles }}"
                                                 title="{{ $date->format('d/m/Y') }} : {{ $data->affaire->statut_label }}">
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($period) + 1 }}" class="text-center text-gray-500 py-8">
                                    Aucune affaire trouvée sur cette période.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Légende -->
            <div class="mt-6 flex gap-4 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-yellow-500 rounded"></div> En attente
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-blue-500 rounded"></div> En cours
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-green-500 rounded"></div> Terminé
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-gray-500 rounded"></div> Archivé
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-red-500" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 5px, rgba(255,255,255,0.2) 5px, rgba(255,255,255,0.2) 10px);"></div> Retard (Prolongation auto)
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
