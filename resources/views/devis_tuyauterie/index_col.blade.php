<div class="bg-white dark:bg-gray-800 flex flex-col h-full rounded-xl overflow-hidden shadow-lg border border-amber-100 dark:border-amber-900/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
    <!-- Header -->
    <div class="px-3 py-2 border-b border-amber-100 dark:border-amber-900/50 flex justify-between items-center bg-amber-600 dark:bg-amber-900/20">
        <div class="flex items-center gap-2">
            <span class="flex items-center justify-center w-6 h-6 bg-white/20 text-white rounded-lg dark:bg-amber-800 dark:text-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </span>
            <h2 class="font-bold text-sm text-white dark:text-amber-100">
                <a href="{{ route('devis_tuyauterie.index') }}" class="hover:underline transition">{{ __('Devis') }}</a>
            </h2>
        </div>
        <a href="{{ route('devis_tuyauterie.index') }}" class="text-[10px] font-bold text-amber-100 hover:text-white dark:text-amber-400 dark:hover:text-amber-300 transition-colors uppercase tracking-wider">
            Voir tout
        </a>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto p-2 scrollbar-thin scrollbar-thumb-amber-200 dark:scrollbar-thumb-amber-800">
         @if (!$isSmall)
            <div class="mb-4 px-2 pt-2">
                @can('gerer_les_devis')
                    <a href="{{ route('devis_tuyauterie.create') }}" class="w-full flex justify-center items-center gap-2 px-4 py-2 bg-amber-600 text-white text-xs font-semibold rounded hover:bg-amber-700 transition shadow-sm group">
                        <svg class="w-3 h-3 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Créer un devis
                    </a>
                @endcan
            </div>
        @endif

        @if($devis->count() > 0)
            <div class="space-y-1">
                @foreach($devis->take(5) as $item)
                    <div class="p-2 hover:bg-amber-100 dark:hover:bg-amber-900/20 rounded-md transition-all duration-300 hover:scale-[1.03] hover:shadow-lg group cursor-pointer border border-transparent hover:border-amber-200 dark:hover:border-amber-800/30" onclick="window.location='{{ route('devis_tuyauterie.show', $item->id) }}'">
                        <div class="flex justify-between items-start mb-0.5">
                            <span class="font-bold text-xs text-gray-800 dark:text-gray-200 group-hover:text-amber-700 dark:group-hover:text-amber-300 transition-colors">
                                {{ $item->reference_projet ?? "Devis #".$item->id }}
                            </span>
                             <span class="text-[9px] text-gray-400 font-mono">
                                {{ $item->date_emission ? \Carbon\Carbon::parse($item->date_emission)->format('d/m') : '-' }}
                            </span>
                        </div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 line-clamp-1 mb-1.5 font-medium leading-tight">
                            {{ $item->client_nom }}
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                {{ number_format($item->total_ht, 2, ',', ' ') }} €
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="h-32 flex flex-col items-center justify-center text-gray-400">
                 <div class="w-10 h-10 bg-amber-50 dark:bg-amber-900/20 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 opacity-40 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="text-[10px] font-medium text-amber-400/70">Aucun devis</span>
            </div>
        @endif
    </div>
</div>
