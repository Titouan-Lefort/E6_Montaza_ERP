<div class="bg-white dark:bg-gray-800 flex flex-col h-full rounded-xl overflow-hidden shadow-lg border border-green-100 dark:border-green-900/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
    <!-- Header -->
    <div class="px-3 py-2 border-b border-green-100 dark:border-green-900/50 flex justify-between items-center bg-green-600 dark:bg-green-900/20">
        <div class="flex items-center gap-2">
            <span class="flex items-center justify-center w-6 h-6 bg-white/20 text-white rounded-lg dark:bg-green-800 dark:text-green-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </span>
            <h2 class="font-bold text-sm text-white dark:text-green-100">
                <a href="{{ route('cde.index') }}" class="hover:underline transition">{{ __('Commandes') }}</a>
            </h2>
        </div>
        <a href="{{ route('cde.index') }}" class="text-[10px] font-bold text-green-100 hover:text-white dark:text-green-400 dark:hover:text-green-300 transition-colors uppercase tracking-wider">
            Voir tout
        </a>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto p-2 scrollbar-thin scrollbar-thumb-green-200 dark:scrollbar-thumb-green-800">
        @if (!$isSmall)
            <div class="mb-4 px-2 pt-2">
                <a href="{{ route('cde.create') }}" class="w-full flex justify-center items-center gap-2 px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700 transition shadow-sm group">
                    <svg class="w-3 h-3 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Créer une commande
                </a>
            </div>
        @endif

        @if($cdes->count() > 0)
            <div class="space-y-1">
                @foreach($cdes->take(5) as $cde)
                    <div class="p-2 hover:bg-green-100 dark:hover:bg-green-900/20 rounded-md transition-all duration-300 hover:scale-[1.03] hover:shadow-lg group cursor-pointer border border-transparent hover:border-green-200 dark:hover:border-green-800/30" onclick="window.location='{{ route('cde.show', $cde) }}'">
                        <div class="flex justify-between items-start mb-0.5">
                            <span class="font-bold text-xs text-gray-800 dark:text-gray-200 group-hover:text-green-700 dark:group-hover:text-green-300 transition-colors">
                                {{ $cde->code }}
                            </span>
                            <span class="text-[9px] text-gray-400 font-mono">
                                {{ $cde->created_at->format('d/m') }}
                            </span>
                        </div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 line-clamp-1 mb-1.5 font-medium leading-tight">
                            {{ $cde->nom }}
                        </div>
                        <div class="flex items-center justify-between">
                             <div class="flex items-center gap-1.5">
                                <span class="h-1.5 w-1.5 rounded-full ring-1 ring-white dark:ring-gray-800"
                                      style="background-color: {{ $cde->statut->couleur ?? '#9ca3af' }}"></span>
                                <span class="text-[9px] uppercase font-bold tracking-wider text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300">
                                    {{ $cde->statut->nom ?? 'N/A' }}
                                </span>
                            </div>
                            <!-- Avatar or initials -->
                            <div class="h-5 w-5 rounded-full bg-green-100 dark:bg-green-800/50 text-green-700 dark:text-green-300 flex items-center justify-center text-[9px] font-bold shadow-sm" title="{{ $cde->user->first_name ?? '' }}">
                                {{ substr($cde->user->first_name ?? '?', 0, 1) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="h-32 flex flex-col items-center justify-center text-gray-400">
                <div class="w-10 h-10 bg-green-50 dark:bg-green-900/20 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 opacity-40 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <span class="text-[10px] font-medium text-green-400/70">Aucune commande</span>
            </div>
        @endif
    </div>
</div>
