<x-app-layout>
    @section('title', 'Suivi Tuyauterie - ' . $affaire->code)
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                    <span class="p-1.5 bg-purple-100 dark:bg-purple-900/30 text-purple-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </span>
                    Suivi Tuyauterie : {{ $affaire->nom }}
                    <span class="text-gray-500 text-sm font-normal">({{ $affaire->code }})</span>
                </h2>
                <div class="mt-1 flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $affaire->statut_color }}-100 text-{{ $affaire->statut_color }}-800 dark:bg-{{ $affaire->statut_color }}-900 dark:text-{{ $affaire->statut_color }}-200">
                        {{ $affaire->statut_label }}
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $stats['total_lignes'] }} ligne(s)
                    </span>
                </div>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ route('affaires.show', $affaire) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    Détails
                </a>
                <a href="{{ route('affaires.suivi') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                    Suivi Global
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .suivi-table tbody tr:hover > td { background-color: rgb(229 231 235 / 0.7) !important; }
        .suivi-table tbody tr:hover > td:hover { background-color: rgb(209 213 219) !important; }
        @media (prefers-color-scheme: dark) {
            .suivi-table tbody tr:hover > td { background-color: rgb(55 65 81 / 0.5) !important; }
            .suivi-table tbody tr:hover > td:hover { background-color: rgb(75 85 99) !important; }
        }
        .dark .suivi-table tbody tr:hover > td { background-color: rgb(55 65 81 / 0.5) !important; }
        .dark .suivi-table tbody tr:hover > td:hover { background-color: rgb(75 85 99) !important; }
    </style>
    <div class="py-4" x-data="suiviTuyauterie()">
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-6">

            {{-- Messages flash --}}
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/30 p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <p class="ml-3 text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900/30 p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <p class="ml-3 text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- KPI Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
                {{-- Total lignes --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-blue-500">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Lignes</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['total_lignes'] }}</p>
                </div>
                {{-- Fabrication --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-orange-500">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Fabrication</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['fabrication_terminees'] }}<span class="text-sm font-normal text-gray-500">/{{ $stats['total_lignes'] }}</span></p>
                    <div class="mt-1 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-orange-500 h-1.5 rounded-full" style="width: {{ $stats['progression_fab'] }}%"></div>
                    </div>
                </div>
                {{-- Montage --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-green-500">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Montage</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['montage_termines'] }}<span class="text-sm font-normal text-gray-500">/{{ $stats['total_lignes'] }}</span></p>
                    <div class="mt-1 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $stats['progression_montage'] }}%"></div>
                    </div>
                </div>
                {{-- Soudure --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-indigo-500">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Soudure</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['soudure_terminees'] }}<span class="text-sm font-normal text-gray-500">/{{ $stats['total_lignes'] }}</span></p>
                </div>
                {{-- Pouces Total --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-purple-500">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Pouces total</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($stats['pouces_total'], 1) }}</p>
                </div>
                {{-- Non-conformités --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 {{ $stats['non_conformites'] > 0 ? 'border-red-500' : 'border-gray-300' }}">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Non-Conformités</p>
                    <p class="text-2xl font-bold {{ $stats['non_conformites'] > 0 ? 'text-red-600' : 'text-gray-800 dark:text-gray-100' }}">{{ $stats['non_conformites'] }}</p>
                </div>
            </div>

            {{-- Action bar --}}
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <form method="POST" action="{{ route('affaires.suivi_lignes.store', $affaire) }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-md uppercase tracking-widest shadow transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Ajouter une ligne
                    </button>
                </form>

                @php
                    function fmtH($v) { if (!$v) return '0h'; $h = intval($v); $m = round(abs($v - $h) * 60); return $h . 'h' . ($m > 0 ? str_pad($m, 2, '0', STR_PAD_LEFT) : ''); }
                @endphp
                <div class="ml-auto text-xs text-gray-500 dark:text-gray-400">
                    <span>Temps fab. total: <strong>{{ fmtH($stats['temps_fab_total']) }}</strong></span>
                    <span class="mx-2">|</span>
                    <span>Montage estimé: <strong>{{ fmtH($stats['temps_montage_estime_total']) }}</strong></span>
                    <span class="mx-2">|</span>
                    <span>Montage réel: <strong>{{ fmtH($stats['temps_montage_reel_total']) }}</strong></span>
                </div>
            </div>

            {{-- Tableau principal --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto" style="max-height: 75vh;">
                    <table class="suivi-table min-w-max w-full text-xs border-collapse">
                        {{-- En-têtes groupés --}}
                        <thead class="sticky top-0 z-20">
                            {{-- Ligne de groupes --}}
                            <tr class="bg-gray-100 dark:bg-gray-900">
                                <th class="px-1 py-1 text-center border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-900 sticky left-0 z-30" rowspan="2" style="min-width:40px">#</th>
                                <th colspan="9" class="px-2 py-1 text-center border border-gray-300 dark:border-gray-600 font-bold text-white bg-blue-600">Identification</th>
                                <th colspan="7" class="px-2 py-1 text-center border border-gray-300 dark:border-gray-600 font-bold text-white bg-teal-600">Technique</th>
                                <th colspan="8" class="px-2 py-1 text-center border border-gray-300 dark:border-gray-600 font-bold text-white bg-cyan-600">Dimensions & Matière</th>
                                <th colspan="4" class="px-2 py-1 text-center border border-gray-300 dark:border-gray-600 font-bold text-white bg-amber-600">Temps Estimés</th>
                                <th colspan="2" class="px-2 py-1 text-center border border-gray-300 dark:border-gray-600 font-bold text-white bg-lime-700">Appro.</th>
                                <th colspan="9" class="px-2 py-1 text-center border border-gray-300 dark:border-gray-600 font-bold text-white bg-orange-600">Fabrication</th>
                                <th colspan="2" class="px-2 py-1 text-center border border-gray-300 dark:border-gray-600 font-bold text-white bg-pink-600">Traitement</th>
                                <th colspan="1" class="px-2 py-1 text-center border border-gray-300 dark:border-gray-600 font-bold text-white bg-rose-700">Livr.</th>
                                <th colspan="8" class="px-2 py-1 text-center border border-gray-300 dark:border-gray-600 font-bold text-white bg-green-600">Montage</th>
                                <th colspan="3" class="px-2 py-1 text-center border border-gray-300 dark:border-gray-600 font-bold text-white bg-indigo-600">Réels & Écarts</th>
                                <th colspan="2" class="px-2 py-1 text-center border border-gray-300 dark:border-gray-600 font-bold text-white bg-red-600">Qualité</th>
                                <th class="px-1 py-1 text-center border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-900" rowspan="2" style="min-width:60px">Actions</th>
                            </tr>
                            {{-- Ligne de colonnes --}}
                            <tr class="bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                {{-- Identification (9) --}}
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 !bg-blue-100 dark:!bg-blue-900/30 whitespace-nowrap" style="min-width:80px">Projet</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 !bg-blue-100 dark:!bg-blue-900/30 whitespace-nowrap" style="min-width:70px">TM</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 !bg-blue-100 dark:!bg-blue-900/30 whitespace-nowrap" style="min-width:50px">Indice</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 !bg-blue-100 dark:!bg-blue-900/30 whitespace-nowrap" style="min-width:80px">Activité</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 !bg-blue-100 dark:!bg-blue-900/30 whitespace-nowrap" style="min-width:100px">Stade Mont.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 !bg-blue-100 dark:!bg-blue-900/30 whitespace-nowrap" style="min-width:60px">Lot</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 !bg-blue-100 dark:!bg-blue-900/30 whitespace-nowrap" style="min-width:60px">Bloc</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 !bg-blue-100 dark:!bg-blue-900/30 whitespace-nowrap" style="min-width:70px">Panneau</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 !bg-blue-100 dark:!bg-blue-900/30 whitespace-nowrap" style="min-width:70px">Repère</th>
                                {{-- Technique (7) --}}
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-teal-50 dark:bg-teal-900/20 whitespace-nowrap" style="min-width:90px">Récept. Iso</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-teal-50 dark:bg-teal-900/20 whitespace-nowrap" style="min-width:100px">Fourn. Préfa</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-teal-50 dark:bg-teal-900/20 whitespace-nowrap" style="min-width:60px">Classe</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-teal-50 dark:bg-teal-900/20 whitespace-nowrap" style="min-width:65px">Code TS</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-teal-50 dark:bg-teal-900/20 whitespace-nowrap" style="min-width:100px">Trait. Surf.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-teal-50 dark:bg-teal-900/20 whitespace-nowrap" style="min-width:110px">Schéma-Ligne</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-teal-50 dark:bg-teal-900/20 whitespace-nowrap" style="min-width:80px">Trigramme</th>
                                {{-- Dimensions & Matière (8) --}}
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-cyan-50 dark:bg-cyan-900/20 whitespace-nowrap" style="min-width:80px">Lg/Poids</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-cyan-50 dark:bg-cyan-900/20 whitespace-nowrap" style="min-width:70px">Pouces</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-cyan-50 dark:bg-cyan-900/20 whitespace-nowrap" style="min-width:70px">Cintrages</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-cyan-50 dark:bg-cyan-900/20 whitespace-nowrap" style="min-width:50px">DN</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-cyan-50 dark:bg-cyan-900/20 whitespace-nowrap" style="min-width:50px">Ep</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-cyan-50 dark:bg-cyan-900/20 whitespace-nowrap" style="min-width:80px">Matière</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-cyan-50 dark:bg-cyan-900/20 whitespace-nowrap" style="min-width:80px">Catégorie</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-cyan-50 dark:bg-cyan-900/20 whitespace-nowrap" style="min-width:90px">DP Armement</th>
                                {{-- Temps Estimés (4) --}}
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-amber-50 dark:bg-amber-900/20 whitespace-nowrap" style="min-width:75px">Tps Fab.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-amber-50 dark:bg-amber-900/20 whitespace-nowrap" style="min-width:90px">Tps Mont. Est.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-amber-50 dark:bg-amber-900/20 whitespace-nowrap" style="min-width:90px">Tps Soud. Est.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-amber-50 dark:bg-amber-900/20 whitespace-nowrap" style="min-width:85px">Tps Mont. E.</th>
                                {{-- Approvisionnement (2) --}}
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-lime-50 dark:bg-lime-900/20 whitespace-nowrap" style="min-width:95px">Mat. Cmdé</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-lime-50 dark:bg-lime-900/20 whitespace-nowrap" style="min-width:90px">Appro Mat.</th>
                                {{-- Fabrication (9) --}}
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-orange-50 dark:bg-orange-900/20 whitespace-nowrap" style="min-width:80px">Piking</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-orange-50 dark:bg-orange-900/20 whitespace-nowrap" style="min-width:80px">Fin Débit</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-orange-50 dark:bg-orange-900/20 whitespace-nowrap" style="min-width:85px">Déb. Fab.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-orange-50 dark:bg-orange-900/20 whitespace-nowrap" style="min-width:85px">Tuyauteur</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-orange-50 dark:bg-orange-900/20 whitespace-nowrap" style="min-width:80px">Fin Assem.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-orange-50 dark:bg-orange-900/20 whitespace-nowrap" style="min-width:70px">Nb Soud.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-orange-50 dark:bg-orange-900/20 whitespace-nowrap" style="min-width:80px">Soudeur</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-orange-50 dark:bg-orange-900/20 whitespace-nowrap" style="min-width:80px">Fin Soud.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-orange-50 dark:bg-orange-900/20 whitespace-nowrap" style="min-width:80px">Fin Fab.</th>
                                {{-- Traitement (2) --}}
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-pink-50 dark:bg-pink-900/20 whitespace-nowrap" style="min-width:85px">Dép. Trait.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-pink-50 dark:bg-pink-900/20 whitespace-nowrap" style="min-width:85px">Ret. Trait.</th>
                                {{-- Livraison (1) --}}
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-rose-50 dark:bg-rose-900/20 whitespace-nowrap" style="min-width:85px">Livr. Bord</th>
                                {{-- Montage (8) --}}
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-green-50 dark:bg-green-900/20 whitespace-nowrap" style="min-width:85px">Déb. Mont.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-green-50 dark:bg-green-900/20 whitespace-nowrap" style="min-width:70px">Monté</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-green-50 dark:bg-green-900/20 whitespace-nowrap" style="min-width:70px">Soudé</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-green-50 dark:bg-green-900/20 whitespace-nowrap" style="min-width:75px">Supporté</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-green-50 dark:bg-green-900/20 whitespace-nowrap" style="min-width:75px">H. Mont.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-green-50 dark:bg-green-900/20 whitespace-nowrap" style="min-width:90px">Éq. Montage</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-green-50 dark:bg-green-900/20 whitespace-nowrap" style="min-width:75px">H. Soud.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-green-50 dark:bg-green-900/20 whitespace-nowrap" style="min-width:80px">Soudeurs</th>
                                {{-- Réels & Écarts (3) --}}
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-indigo-50 dark:bg-indigo-900/20 whitespace-nowrap" style="min-width:85px">Tps Mont. Réel</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-indigo-50 dark:bg-indigo-900/20 whitespace-nowrap" style="min-width:85px">Diff Mont.</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-indigo-50 dark:bg-indigo-900/20 whitespace-nowrap" style="min-width:85px">Diff Soud.</th>
                                {{-- Qualité (2) --}}
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-red-50 dark:bg-red-900/20 whitespace-nowrap" style="min-width:85px">Éprouvé le</th>
                                <th class="px-1 py-1 border border-gray-300 dark:border-gray-600 bg-red-50 dark:bg-red-900/20 whitespace-nowrap" style="min-width:100px">Non-Conform.</th>
                            </tr>
                        </thead>
                        @php
                        $cb = 'px-1 py-1 border border-gray-200 dark:border-gray-700';
                        $ce = 'cursor-pointer';
                        $ib = 'w-full text-xs border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-1 py-0.5 focus:ring-purple-500';
                        $cellsA = [
                            ['projet', 'text', '', '!bg-blue-100 dark:!bg-blue-900/30'],
                            ['tm', 'text', '', 'font-medium !bg-blue-100 dark:!bg-blue-900/30'],
                            ['indice', 'text', '', '!bg-blue-100 dark:!bg-blue-900/30'],
                            ['activite', 'text', '', '!bg-blue-100 dark:!bg-blue-900/30'],
                            ['stade_montage', 'text', '', '!bg-blue-100 dark:!bg-blue-900/30'],
                            ['lot', 'text', '', '!bg-blue-100 dark:!bg-blue-900/30'],
                            ['bloc', 'text', '', '!bg-blue-100 dark:!bg-blue-900/30'],
                            ['panneau', 'text', '', '!bg-blue-100 dark:!bg-blue-900/30'],
                            ['repere', 'text', '', 'font-medium !bg-blue-100 dark:!bg-blue-900/30'],
                            ['date_reception_iso', 'date', '', 'whitespace-nowrap'],
                            ['fournisseur_prefa', 'text', '', ''],
                            ['classe', 'text', '', ''],
                            ['code_ts', 'text', '', ''],
                            ['traitement_surface', 'text', '', ''],
                            ['schema_ligne', 'text', '', ''],
                            ['trigramme', 'text', '', ''],
                            ['longueur_poids', 'number', 'step="0.01"', 'text-right'],
                            ['pouces_total', 'number', 'step="0.01"', 'text-right'],
                            ['qtt_cintrages', 'number', 'step="1"', 'text-right'],
                            ['dn', 'text', '', ''],
                            ['ep', 'text', '', ''],
                            ['matiere', 'text', '', ''],
                            ['categorie', 'text', '', ''],
                            ['dp_armement', 'text', '', ''],
                            ['temps_fabrication', 'number', 'step="0.1"', 'text-right'],
                            ['temps_montage_total_estime', 'number', 'step="0.1"', 'text-right'],
                            ['temps_soudure_estime', 'number', 'step="0.1"', 'text-right'],
                            ['temps_montage_estime', 'number', 'step="0.1"', 'text-right'],
                            ['matiere_commande_le', 'date', '', 'whitespace-nowrap'],
                            ['appro_matiere', 'date', '', 'whitespace-nowrap'],
                            ['piking', 'date', '', 'whitespace-nowrap'],
                            ['fin_debit', 'date', '', 'whitespace-nowrap'],
                            ['debut_fabrication', 'date', '', 'whitespace-nowrap'],
                            ['tuyauteur', 'text', '', ''],
                            ['fin_assemblage', 'date', '', 'whitespace-nowrap'],
                            ['nbr_soudure', 'number', 'step="1"', 'text-right'],
                            ['soudeur', 'text', '', ''],
                            ['fin_soudage', 'date', '', 'whitespace-nowrap'],
                            ['fin_fabrication', 'date', '', 'whitespace-nowrap font-medium'],
                            ['depart_traitement', 'date', '', 'whitespace-nowrap'],
                            ['retour_traitement', 'date', '', 'whitespace-nowrap'],
                            ['livraison_bord', 'date', '', 'whitespace-nowrap font-medium'],
                            ['debut_montage', 'date', '', 'whitespace-nowrap'],
                            ['monte', 'date', '', 'whitespace-nowrap text-center'],
                            ['soude', 'date', '', 'whitespace-nowrap text-center'],
                            ['supporte', 'date', '', 'whitespace-nowrap text-center'],
                            ['nb_heures_montages', 'number', 'step="0.1"', 'text-right'],
                            ['equipe_montage', 'text', '', ''],
                            ['nb_heures_soudages', 'number', 'step="0.1"', 'text-right'],
                            ['soudeurs', 'text', '', ''],
                            ['temps_montage_total_reel', 'number', 'step="0.1"', 'text-right font-medium'],
                        ];
                        $cellsB = [
                            ['eprouve_le', 'date', '', 'whitespace-nowrap'],
                            ['non_conformite', 'text', '', ''],
                        ];
                        @endphp
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($lignes as $index => $ligne)
                                @php $rid = $ligne->id; @endphp
                                <tr :class="{'bg-red-50/50 dark:bg-red-900/10': rows[{{ $rid }}]?.non_conformite}">
                                    <td class="{{ $cb }} text-center font-medium text-gray-500 bg-gray-50 dark:bg-gray-800 sticky left-0 z-10">{{ $index + 1 }}</td>
                                    @foreach($cellsA as [$field, $type, $step, $extra])
                                    <td class="{{ $cb }} {{ $ce }} {{ $extra }}" @click="startEdit({{ $rid }}, '{{ $field }}')">
                                        <span x-show="editingCell !== '{{ $rid }}-{{ $field }}'" x-text="dv({{ $rid }}, '{{ $field }}')" :class="dvCls({{ $rid }}, '{{ $field }}')"></span>
                                        <input x-show="editingCell === '{{ $rid }}-{{ $field }}'" x-model="editValue" type="{{ $type }}" {!! $step !!} data-edit="{{ $rid }}-{{ $field }}" @blur="saveCell({{ $rid }}, '{{ $field }}')" @keydown.enter.prevent="$event.target.blur()" @keydown.escape="cancelEdit()" class="{{ $ib }} {{ str_contains($extra, 'text-right') ? 'text-right' : '' }}">
                                    </td>
                                    @endforeach
                                    {{-- Diff Montage (calculé) --}}
                                    <td class="{{ $cb }} text-right font-bold whitespace-nowrap" :class="diffCls({{ $rid }}, 'montage')" x-text="diffVal({{ $rid }}, 'montage')"></td>
                                    {{-- Diff Soudure (calculé) --}}
                                    <td class="{{ $cb }} text-right font-bold whitespace-nowrap" :class="diffCls({{ $rid }}, 'soudure')" x-text="diffVal({{ $rid }}, 'soudure')"></td>
                                    @foreach($cellsB as [$field, $type, $step, $extra])
                                    <td class="{{ $cb }} {{ $ce }} {{ $extra }}" @click="startEdit({{ $rid }}, '{{ $field }}')">
                                        <span x-show="editingCell !== '{{ $rid }}-{{ $field }}'" x-text="dv({{ $rid }}, '{{ $field }}')" :class="dvCls({{ $rid }}, '{{ $field }}')"></span>
                                        <input x-show="editingCell === '{{ $rid }}-{{ $field }}'" x-model="editValue" type="{{ $type }}" {!! $step !!} data-edit="{{ $rid }}-{{ $field }}" @blur="saveCell({{ $rid }}, '{{ $field }}')" @keydown.enter.prevent="$event.target.blur()" @keydown.escape="cancelEdit()" class="{{ $ib }} {{ str_contains($extra, 'text-right') ? 'text-right' : '' }}">
                                    </td>
                                    @endforeach
                                    {{-- Actions --}}
                                    <td class="{{ $cb }} text-center bg-gray-50 dark:bg-gray-800 sticky right-0 z-10">
                                        <form method="POST" action="{{ route('affaires.suivi_lignes.delete', [$affaire, $ligne]) }}" onsubmit="return confirm('Supprimer cette ligne ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200" title="Supprimer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="57" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        <p class="text-sm">Aucune ligne de suivi. Cliquez sur "Ajouter une ligne" pour commencer.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        {{-- Totaux --}}
                        @if($lignes->count() > 0)
                        <tfoot class="sticky bottom-0 z-20">
                            <tr class="bg-gray-100 dark:bg-gray-900 font-bold text-xs">
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-center bg-gray-200 dark:bg-gray-900 sticky left-0 z-30">&Sigma;</td>
                                <td colspan="9" class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right text-gray-600 dark:text-gray-400">{{ $stats['total_lignes'] }} lignes</td>
                                {{-- Technique: 7 cols vides --}}
                                <td colspan="7" class="px-1 py-2 border border-gray-300 dark:border-gray-600"></td>
                                {{-- Dimensions --}}
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right">{{ number_format($lignes->sum('longueur_poids'), 2) }}</td>
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right">{{ number_format($lignes->sum('pouces_total'), 2) }}</td>
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right">{{ $lignes->sum('qtt_cintrages') }}</td>
                                <td colspan="5" class="px-1 py-2 border border-gray-300 dark:border-gray-600"></td>
                                {{-- Temps estimés --}}
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right text-amber-700 dark:text-amber-300">{{ fmtH($lignes->sum('temps_fabrication')) }}</td>
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right text-amber-700 dark:text-amber-300">{{ fmtH($lignes->sum('temps_montage_total_estime')) }}</td>
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right text-amber-700 dark:text-amber-300">{{ fmtH($lignes->sum('temps_soudure_estime')) }}</td>
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right text-amber-700 dark:text-amber-300">{{ fmtH($lignes->sum('temps_montage_estime')) }}</td>
                                {{-- Appro: 2 vides --}}
                                <td colspan="2" class="px-1 py-2 border border-gray-300 dark:border-gray-600"></td>
                                {{-- Fabrication --}}
                                <td colspan="5" class="px-1 py-2 border border-gray-300 dark:border-gray-600"></td>
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right">{{ $lignes->sum('nbr_soudure') }}</td>
                                <td colspan="3" class="px-1 py-2 border border-gray-300 dark:border-gray-600"></td>
                                {{-- Traitement + Livraison: 3 vides --}}
                                <td colspan="3" class="px-1 py-2 border border-gray-300 dark:border-gray-600"></td>
                                {{-- Montage --}}
                                <td colspan="4" class="px-1 py-2 border border-gray-300 dark:border-gray-600"></td>
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right text-green-700 dark:text-green-300">{{ fmtH($lignes->sum('nb_heures_montages')) }}</td>
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600"></td>
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right text-green-700 dark:text-green-300">{{ fmtH($lignes->sum('nb_heures_soudages')) }}</td>
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600"></td>
                                {{-- Réels --}}
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right text-indigo-700 dark:text-indigo-300">{{ fmtH($lignes->sum('temps_montage_total_reel')) }}</td>
                                @php
                                    $totalDiffMontage = $lignes->sum('temps_montage_total_reel') - $lignes->sum('temps_montage_total_estime');
                                    $totalDiffSoudure = $lignes->sum('nb_heures_soudages') - $lignes->sum('temps_soudure_estime');
                                    function fmtDiff($v) { if ($v == 0) return '-'; $sign = $v > 0 ? '+' : '-'; $abs = abs($v); $h = intval($abs); $m = round(($abs - $h) * 60); return $sign . $h . 'h' . ($m > 0 ? str_pad($m, 2, '0', STR_PAD_LEFT) : ''); }
                                @endphp
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right {{ $totalDiffMontage > 0 ? 'text-red-600' : ($totalDiffMontage < 0 ? 'text-green-600' : '') }}">
                                    {{ fmtDiff($totalDiffMontage) }}
                                </td>
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-right {{ $totalDiffSoudure > 0 ? 'text-red-600' : ($totalDiffSoudure < 0 ? 'text-green-600' : '') }}">
                                    {{ fmtDiff($totalDiffSoudure) }}
                                </td>
                                {{-- Qualité --}}
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-center">{{ $stats['eprouves'] }}</td>
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 text-center {{ $stats['non_conformites'] > 0 ? 'text-red-600' : '' }}">{{ $stats['non_conformites'] }}</td>
                                <td class="px-1 py-2 border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-900 sticky right-0 z-30"></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Légende --}}
            <div class="mt-3 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h4 class="text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-2">Légende des groupes de colonnes</h4>
                <div class="flex flex-wrap gap-3 text-xs">
                    <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-blue-600"></span> Identification</span>
                    <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-teal-600"></span> Technique</span>
                    <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-cyan-600"></span> Dimensions & Matière</span>
                    <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-amber-600"></span> Temps Estimés</span>
                    <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-lime-700"></span> Approvisionnement</span>
                    <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-orange-600"></span> Fabrication</span>
                    <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-pink-600"></span> Traitement</span>
                    <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-rose-700"></span> Livraison</span>
                    <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-green-600"></span> Montage</span>
                    <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-indigo-600"></span> Temps Réels & Écarts</span>
                    <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded bg-red-600"></span> Qualité</span>
                </div>
                <p class="mt-2 text-xs text-gray-400">Diff Montage/Soudure : <span class="text-red-500">+rouge = dépassement</span> | <span class="text-green-500">-vert = en avance</span></p>
            </div>
        </div>

    </div>

    <script>
        function suiviTuyauterie() {
            return {
                rows: @json($lignes->keyBy('id')->toArray()),
                editingCell: null,
                editValue: '',
                affaireId: {{ $affaire->id }},

                dateFields: [
                    'date_reception_iso', 'matiere_commande_le', 'appro_matiere', 'piking',
                    'fin_debit', 'debut_fabrication', 'fin_assemblage', 'fin_soudage',
                    'fin_fabrication', 'depart_traitement', 'retour_traitement', 'livraison_bord',
                    'debut_montage', 'monte', 'soude', 'supporte', 'eprouve_le'
                ],

                fmtDate(val) {
                    if (!val) return '';
                    const s = String(val).substring(0, 10);
                    const p = s.split('-');
                    if (p.length !== 3) return val;
                    return p[2] + '/' + p[1] + '/' + p[0];
                },

                fmtShortDate(val) {
                    if (!val) return '';
                    const s = String(val).substring(0, 10);
                    const p = s.split('-');
                    if (p.length !== 3) return val;
                    return p[2] + '/' + p[1];
                },

                fmtNum(val, dec) {
                    if (val === null || val === undefined || val === '') return '';
                    return parseFloat(val).toFixed(dec);
                },

                fmtHours(val) {
                    if (val === null || val === undefined || val === '') return '';
                    const v = parseFloat(val);
                    if (isNaN(v)) return '';
                    const abs = Math.abs(v);
                    const h = Math.floor(abs);
                    const m = Math.round((abs - h) * 60);
                    const sign = v < 0 ? '-' : '';
                    return sign + h + 'h' + (m > 0 ? String(m).padStart(2, '0') : '');
                },

                dv(rowId, field) {
                    const row = this.rows[rowId];
                    if (!row) return '';
                    const val = row[field];

                    // Special fields
                    if (field === 'appro_matiere') {
                        if (val) return this.fmtDate(val);
                        if (row.matiere_commande_le) return 'En attente';
                        return '';
                    }
                    if (field === 'fin_fabrication' || field === 'livraison_bord') {
                        return val ? this.fmtDate(val) : '-';
                    }
                    if (field === 'monte' || field === 'soude' || field === 'supporte') {
                        return val ? '✓ ' + this.fmtShortDate(val) : '';
                    }

                    if (val === null || val === undefined || val === '') return '';

                    // Date fields
                    if (this.dateFields.includes(field)) return this.fmtDate(val);

                    // Number fields
                    if (['longueur_poids', 'pouces_total'].includes(field)) return this.fmtNum(val, 2);
                    if (['temps_fabrication', 'temps_montage_total_estime', 'temps_soudure_estime',
                         'temps_montage_estime', 'nb_heures_montages', 'nb_heures_soudages',
                         'temps_montage_total_reel'].includes(field)) return this.fmtHours(val);
                    if (['qtt_cintrages', 'nbr_soudure'].includes(field)) return parseInt(val) || '';

                    return val;
                },

                dvCls(rowId, field) {
                    const row = this.rows[rowId];
                    if (!row) return '';
                    const val = row[field];

                    if (field === 'appro_matiere') {
                        if (val) return 'text-green-600 dark:text-green-400';
                        if (row.matiere_commande_le) return 'text-orange-500';
                        return 'text-gray-700 dark:text-gray-300';
                    }
                    if (field === 'fin_fabrication' || field === 'livraison_bord') {
                        return val ? 'text-green-600 dark:text-green-400' : 'text-gray-400';
                    }
                    if ((field === 'monte' || field === 'soude' || field === 'supporte') && val) {
                        return 'inline-flex items-center px-1.5 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300';
                    }
                    if (field === 'non_conformite') {
                        return val ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-500';
                    }
                    if (['temps_fabrication', 'temps_montage_total_estime', 'temps_soudure_estime', 'temps_montage_estime'].includes(field)) {
                        return 'text-amber-700 dark:text-amber-300';
                    }
                    if (field === 'temps_montage_total_reel') {
                        return 'text-indigo-700 dark:text-indigo-300';
                    }
                    if (['tm', 'repere'].includes(field)) {
                        return 'text-gray-900 dark:text-gray-100';
                    }

                    return 'text-gray-700 dark:text-gray-300';
                },

                diffVal(rowId, type) {
                    const row = this.rows[rowId];
                    if (!row) return '';
                    let diff;
                    if (type === 'montage') {
                        const reel = parseFloat(row.temps_montage_total_reel);
                        const estime = parseFloat(row.temps_montage_total_estime);
                        if (isNaN(reel) || isNaN(estime)) return '';
                        diff = reel - estime;
                    } else {
                        const reel = parseFloat(row.nb_heures_soudages);
                        const estime = parseFloat(row.temps_soudure_estime);
                        if (isNaN(reel) || isNaN(estime)) return '';
                        diff = reel - estime;
                    }
                    const sign = diff > 0 ? '+' : '';
                    return sign + this.fmtHours(diff);
                },

                diffCls(rowId, type) {
                    const row = this.rows[rowId];
                    if (!row) return '';
                    let diff;
                    if (type === 'montage') {
                        const reel = parseFloat(row.temps_montage_total_reel);
                        const estime = parseFloat(row.temps_montage_total_estime);
                        if (isNaN(reel) || isNaN(estime)) return '';
                        diff = reel - estime;
                    } else {
                        const reel = parseFloat(row.nb_heures_soudages);
                        const estime = parseFloat(row.temps_soudure_estime);
                        if (isNaN(reel) || isNaN(estime)) return '';
                        diff = reel - estime;
                    }
                    if (diff > 0) return 'text-red-600 dark:text-red-400';
                    if (diff < 0) return 'text-green-600 dark:text-green-400';
                    return 'text-gray-500';
                },

                startEdit(rowId, field) {
                    if (this.editingCell) return; // déjà en édition, laisser le blur sauvegarder d'abord
                    let val = this.rows[rowId]?.[field] ?? '';
                    if (this.dateFields.includes(field) && val) {
                        val = String(val).substring(0, 10);
                    }
                    this.editingCell = rowId + '-' + field;
                    this.editValue = (val !== null && val !== undefined) ? val : '';
                    this.$nextTick(() => {
                        const input = document.querySelector('[data-edit="' + this.editingCell + '"]');
                        if (input) input.focus();
                    });
                },

                async saveCell(rowId, field) {
                    const cellKey = this.editingCell;
                    const value = this.editValue;
                    this.editingCell = null;

                    // Mettre à jour localement immédiatement
                    if (this.rows[rowId]) {
                        this.rows[rowId][field] = value || null;
                    }

                    const data = {};
                    data[field] = value || null;

                    try {
                        const response = await fetch('/affaires/' + this.affaireId + '/suivi-lignes/' + rowId, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(data)
                        });

                        if (response.ok) {
                            const result = await response.json();
                            this.rows[rowId] = result.ligne;
                        }
                    } catch (e) {
                        console.error('Erreur sauvegarde:', e);
                    }
                },

                cancelEdit() {
                    this.editingCell = null;
                    this.editValue = '';
                }
            };
        }
    </script>
</x-app-layout>
