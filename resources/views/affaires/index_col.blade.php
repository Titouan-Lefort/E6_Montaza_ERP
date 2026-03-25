<div class="bg-white dark:bg-gray-800 flex flex-col h-full rounded-xl overflow-hidden shadow-lg border border-purple-100 dark:border-purple-900/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
    <!-- Header -->
    <div class="px-3 py-2 border-b border-purple-100 dark:border-purple-900/50 flex justify-between items-center bg-purple-600 dark:bg-purple-900/20">
        <div class="flex items-center gap-2">
            <span class="flex items-center justify-center w-6 h-6 bg-white/20 text-white rounded-lg dark:bg-purple-800 dark:text-purple-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </span>
            <h2 class="font-bold text-sm text-white dark:text-purple-100">
                <a href="{{ route('affaires.index') }}" class="hover:underline transition">{{ __('Affaires') }}</a>
            </h2>
        </div>
        <a href="{{ route('affaires.index') }}" class="text-[10px] font-bold text-purple-100 hover:text-white dark:text-purple-400 dark:hover:text-purple-300 transition-colors uppercase tracking-wider">
            Voir tout
        </a>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto p-2 scrollbar-thin scrollbar-thumb-purple-200 dark:scrollbar-thumb-purple-800">
        @if($affaires->count() > 0)
            <div class="space-y-1">
                @foreach($affaires->take(5) as $affaire)
                    <div class="p-2 hover:bg-purple-100 dark:hover:bg-purple-900/20 rounded-md transition-all duration-300 hover:scale-[1.03] hover:shadow-lg group cursor-pointer border border-transparent hover:border-purple-200 dark:hover:border-purple-800/30" onclick="window.location='{{ route('affaires.show', $affaire) }}'">
                        <div class="flex justify-between items-start mb-0.5">
                            <span class="font-bold text-xs text-gray-800 dark:text-gray-200 group-hover:text-purple-700 dark:group-hover:text-purple-300 transition-colors">
                                {{ $affaire->code }}
                            </span>
                            @if(isset($affaire->created_at))
                            <span class="text-[9px] text-gray-400 font-mono">
                                {{ $affaire->created_at->format('d/m') }}
                            </span>
                            @endif
                        </div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 line-clamp-1 mb-1.5 font-medium leading-tight">
                            {{ $affaire->nom }}
                        </div>
                        <div class="flex items-center justify-between">
                             <div class="flex items-center gap-1.5">
                                <span class="h-1.5 w-1.5 rounded-full ring-1 ring-white dark:ring-gray-800"
                                      style="background-color: {{ $affaire->statut_color ?? '#9ca3af' }}"></span>
                                <span class="text-[9px] uppercase font-bold tracking-wider text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300">
                                    {{ $affaire->statut_label ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="h-32 flex flex-col items-center justify-center text-gray-400">
                <div class="w-10 h-10 bg-purple-50 dark:bg-purple-900/20 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 opacity-40 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-[10px] font-medium text-purple-400/70">Aucune affaire</span>
            </div>
        @endif
    </div>
</div>
