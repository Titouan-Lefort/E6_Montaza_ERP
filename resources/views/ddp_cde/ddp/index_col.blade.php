<div class="bg-white dark:bg-gray-800 flex flex-col h-full rounded-xl overflow-hidden shadow-lg border border-blue-100 dark:border-blue-900/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
    <!-- Header -->
    <div class="px-3 py-2 border-b border-blue-100 dark:border-blue-900/50 flex justify-between items-center bg-blue-600 dark:bg-blue-900/20">
        <div class="flex items-center gap-2">
            <span class="flex items-center justify-center w-6 h-6 bg-white/20 text-white rounded-lg dark:bg-blue-800 dark:text-blue-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </span>
            <h2 class="font-bold text-sm text-white dark:text-blue-100">
                <a href="{{ route('ddp.index') }}" class="hover:underline transition">{{ __('Demandes') }}</a>
            </h2>
        </div>
        <a href="{{ route('ddp.index') }}" class="text-[10px] font-bold text-blue-100 hover:text-white dark:text-blue-400 dark:hover:text-blue-300 transition-colors uppercase tracking-wider">
            Voir tout
        </a>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto p-2 scrollbar-thin scrollbar-thumb-blue-200 dark:scrollbar-thumb-blue-800">
        @if (!$isSmall)
            <div class="mb-4 px-2 pt-2">
                <a href="{{ route('ddp.create') }}" class="w-full flex justify-center items-center gap-2 px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700 transition shadow-sm group">
                    <svg class="w-3 h-3 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Créer une demande
                </a>
            </div>
        @endif

        @if($ddps->count() > 0)
            <div class="space-y-1">
                @foreach($ddps->take(5) as $ddp)
                    <div class="p-2 hover:bg-blue-100 dark:hover:bg-blue-900/20 rounded-md transition-all duration-300 hover:scale-[1.03] hover:shadow-lg group cursor-pointer border border-transparent hover:border-blue-200 dark:hover:border-blue-800/30" onclick="window.location='{{ route('ddp.show', $ddp) }}'">
                        <div class="flex justify-between items-start mb-0.5">
                            <span class="font-bold text-xs text-gray-800 dark:text-gray-200 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors">
                                {{ $ddp->code }}
                            </span>
                            <span class="text-[9px] text-gray-400 font-mono">
                                {{ $ddp->created_at->format('d/m') }}
                            </span>
                        </div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 line-clamp-1 mb-1.5 font-medium leading-tight">
                            {{ $ddp->nom }}
                        </div>
                        <div class="flex items-center justify-between">
                             <div class="flex items-center gap-1.5">
                                <span class="h-1.5 w-1.5 rounded-full ring-1 ring-white dark:ring-gray-800"
                                      style="background-color: {{ $ddp->statut->couleur ?? '#9ca3af' }}"></span>
                                <span class="text-[9px] uppercase font-bold tracking-wider text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300">
                                    {{ $ddp->statut->nom ?? 'N/A' }}
                                </span>
                            </div>
                            <!-- Avatar or initials -->
                            <div class="h-5 w-5 rounded-full bg-blue-100 dark:bg-blue-800/50 text-blue-700 dark:text-blue-300 flex items-center justify-center text-[9px] font-bold shadow-sm" title="{{ $ddp->user->first_name ?? '' }}">
                                {{ substr($ddp->user->first_name ?? '?', 0, 1) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="h-32 flex flex-col items-center justify-center text-gray-400">
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 opacity-40 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="text-[10px] font-medium text-blue-400/70">Aucune demande</span>
            </div>
        @endif
    </div>
</div>
