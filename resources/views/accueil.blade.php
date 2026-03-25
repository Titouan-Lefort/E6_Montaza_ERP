<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Accueil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Bannière de bienvenue --}}
            <div class="mb-10 px-4 sm:px-0">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 dark:from-blue-800 dark:to-indigo-900 rounded-2xl p-8 shadow-xl text-white">
                    <h1 class="text-3xl font-bold">Bienvenue, {{ Auth::user()->first_name }} !</h1>
                    <p class="mt-2 text-blue-100 text-lg">Que souhaitez-vous faire aujourd'hui ?</p>
                </div>
            </div>

            {{-- Cartes d'accès rapide --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-0">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Tableau de bord</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Vue d'ensemble de l'activité</p>
                        </div>
                    </div>
                </a>

                {{-- Sociétés --}}
                @if(Auth::user()->hasPermission('voir_les_societes'))
                <a href="{{ route('societes.index') }}"
                   class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:border-emerald-300 dark:hover:border-emerald-600 transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Sociétés</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Gérer les sociétés et contacts</p>
                        </div>
                    </div>
                </a>
                @endif

                {{-- Matières --}}
                @can('voir_les_matieres')
                <a href="{{ route('matieres.index') }}"
                   class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:border-amber-300 dark:hover:border-amber-600 transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-amber-100 dark:bg-amber-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Matières</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Catalogues et prix des matières</p>
                        </div>
                    </div>
                </a>
                @endcan

                {{-- DDP / CDE --}}
                @can('voir_les_ddp_et_cde')
                <a href="{{ route('ddp_cde.index') }}"
                   class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:border-violet-300 dark:hover:border-violet-600 transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-violet-100 dark:bg-violet-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">DDP / CDE</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Demandes de prix et commandes</p>
                        </div>
                    </div>
                </a>
                @endcan

                {{-- Affaires --}}
                @can('voir_les_affaires')
                <a href="{{ route('affaires.index') }}"
                   class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:border-purple-300 dark:hover:border-purple-600 transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Affaires</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Suivi des affaires en cours</p>
                        </div>
                    </div>
                </a>
                @endcan

                {{-- Réparations --}}
                @can('voir_les_reparations')
                <a href="{{ route('reparation.index') }}"
                   class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:border-red-300 dark:hover:border-red-600 transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">Réparations</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Réparations du matériel</p>
                        </div>
                    </div>
                </a>
                @endcan

                {{-- Devis --}}
                @can('voir_les_devis')
                <a href="{{ route('devis_tuyauterie.index') }}"
                   class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:border-teal-300 dark:hover:border-teal-600 transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-teal-100 dark:bg-teal-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">Devis</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Devis tuyauterie</p>
                        </div>
                    </div>
                </a>
                @endcan

            </div>
        </div>
    </div>
</x-app-layout>
