<x-app-layout>
    @section('title', 'Anciens Devis')
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <span class="p-2 bg-gray-100 dark:bg-gray-700 text-gray-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </span>
                {{ __('Anciens Devis (Archivés)') }}
            </h2>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                 <a href="{{ route('devis_tuyauterie.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm whitespace-nowrap">
                    {{ __('Retour aux devis actifs') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-0 text-gray-900 dark:text-gray-100">
                    @if($devis->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Référence</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date Emission</th>
                                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total HT</th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($devis as $item)
                                        <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-700/50 transition duration-150 ease-in-out group cursor-pointer" onclick="window.location='{{ route('devis_tuyauterie.show', $item->id) }}'">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-9 w-9 bg-gray-100 text-gray-600 rounded-lg flex items-center justify-center font-bold text-xs shadow-sm">
                                                        #
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-amber-600 transition duration-150">
                                                            {{ $item->reference_projet ?? "Devis #".$item->id }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            Créé le {{ $item->created_at->format('d/m/Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ $item->client_nom }}</div>
                                                @if($item->lieu_intervention)
                                                    <div class="text-xs text-gray-500 flex items-center mt-1">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                        {{ Str::limit($item->lieu_intervention, 20) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $item->date_emission ? \Carbon\Carbon::parse($item->date_emission)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900 dark:text-white">
                                                {{ number_format($item->total_ht, 2, ',', ' ') }} €
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium relative" onclick="event.stopPropagation()">
                                                <div class="flex justify-center space-x-3 items-center">
                                                    <a href="{{ route('devis_tuyauterie.show', $item->id) }}" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-full transition" title="Voir les détails">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>

                                                    @can('gerer_les_devis')
                                                    <form method="POST" action="{{ route('devis_tuyauterie.unarchive', $item->id) }}">
                                                        @csrf
                                                        <button type="submit" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-full transition" title="Restaurer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-16" style="padding-top: 15rem; padding-bottom: 15rem;">
                            <div class="bg-gray-100 rounded-full p-6 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Aucun devis archivé</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
