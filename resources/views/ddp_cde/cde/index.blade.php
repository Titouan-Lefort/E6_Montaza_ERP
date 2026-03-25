<x-app-layout>
    @section('title', 'Commandes')
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
             <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <span class="p-2 bg-green-100 dark:bg-green-900/30 text-green-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </span>
                {{ __('Commandes') }}
            </h2>

             <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <form method="GET" action="{!! route('cde.index') !!}" class="flex flex-col sm:flex-row gap-2 w-full md:w-auto items-center">
                    <!-- Search -->
                     <div class="relative w-full sm:w-48">
                         <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" placeholder="Rechercher..." value="{!! request('search') !!}"
                             oninput="debounceSubmit(this.form)"
                            class="pl-10 w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-100 text-sm">
                    </div>

                    <!-- Filters (Simplified visually) -->
                     <select name="statut" onchange="this.form.submit()" class="w-full sm:w-40 rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-100 text-sm">
                        <option value="">{{ __('Tous types') }}</option>
                        @foreach ($cde_statuts as $cde_statut)
                            <option value="{{ $cde_statut->id }}" {{ request('statut') == $cde_statut->id ? 'selected' : '' }}>
                                {{ $cde_statut->nom }}
                            </option>
                        @endforeach
                    </select>

                    <select name="societe" onchange="this.form.submit()" class="w-full sm:w-48 rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-100 text-sm">
                         <option value="" selected>{!! __('Toutes les sociétés') !!}</option>
                        @foreach ($societes as $societe)
                            <option value="{{ $societe->id }}" {{ request('societe') == $societe->id ? 'selected' : '' }}>
                                {!! $societe->raison_sociale !!}
                            </option>
                        @endforeach
                    </select>
                </form>

                <a href="{!! route('cde.create') !!}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Nouvelle') }}
                </a>
            </div>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-0 text-gray-800 dark:text-gray-200">
                    @if ($cdesGrouped->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            <a href="{{ route('cde.index', array_merge(request()->query(), ['sort' => 'code', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="group inline-flex items-center">
                                                Numéro
                                                @if(request('sort') == 'code')
                                                    <svg class="ml-2 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ request('direction') == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                                    </svg>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nom</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Créé par</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pour</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statut</th>
                                    </tr>
                                </thead>
                                @include('ddp_cde.cde.partials.index_lignes', [
                                    'isSmall' => false,
                                    'showCreateButton' => false,
                                    'cdesGrouped' => $cdesGrouped,
                                ])
                            </table>
                        </div>
                    @else
                        <!-- Message si aucune commande -->
                         <div class="flex flex-col items-center justify-center py-12">
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-full p-6 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Aucune commande trouvée</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6 text-center max-w-sm">Aucune commande ne correspond à vos critères de recherche.</p>
                            <a href="{{ route('cde.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Créer une nouvelle commande
                            </a>
                        </div>
                    @endif

                    @if($cdes->total() > $cdes->perPage())
                    <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        {{ $cdes->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        let timeout = null;
        function debounceSubmit(form) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                form.submit();
            }, 500);
        }
    </script>
</x-app-layout>
