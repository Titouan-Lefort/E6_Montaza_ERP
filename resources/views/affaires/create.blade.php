<x-app-layout>
    @section('title', 'Nouvelle affaire')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                <a href="{{ route('affaires.index') }}" class="p-2 mr-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                {{ __('Nouvelle affaire') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="mb-8 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Informations générales</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Remplissez les informations ci-dessous pour créer une nouvelle affaire.</p>
                    </div>

                    <form id="create-affaire-form" method="POST" action="{{ route('affaires.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Code -->
                            <div class="md:col-span-1">
                                <label for="code" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Code Affaire</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                    </div>
                                    <input type="text" name="code" id="code" value="{{ $code ?? '' }}"
                                        class="pl-10 w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-purple-500 focus:ring-purple-500/20 transition-all font-mono font-bold"
                                        placeholder="Ex: AF-2024-001" required>
                                </div>
                            </div>

                            <!-- Nom -->
                            <div class="md:col-span-3">
                                <label for="nom" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nom de l'affaire</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <input type="text" name="nom" id="nom"
                                        class="pl-10 w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-purple-500 focus:ring-purple-500/20 transition-all"
                                        placeholder="Saisissez le nom du projet" required>
                                </div>
                            </div>
                        </div>

                        <!-- Budget -->
                        <div>
                            <label for="budget" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Budget prévisionnel</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold">€</span>
                                </div>
                                <input type="number" step="0.01" name="budget" id="budget"
                                    class="pl-8 w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-purple-500 focus:ring-purple-500/20 transition-all"
                                    placeholder="0.00">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date début -->
                            <div>
                                <label for="date_debut" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date de début</label>
                                <div class="relative">
                                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="date" name="date_debut" id="date_debut"
                                        class="pl-10 w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-purple-500 focus:ring-purple-500/20 transition-all" required>
                                </div>
                            </div>

                            <!-- Date fin -->
                            <div>
                                <label for="date_fin_prevue" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date de fin prévue</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <input type="date" name="date_fin_prevue" id="date_fin_prevue"
                                        class="pl-10 w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-purple-500 focus:ring-purple-500/20 transition-all" required>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700 mt-6">
                            <a href="{{ route('affaires.index') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors shadow-sm">Annuler</a>

                            <!-- Bouton transformé en lien (avec comportement submit) -->
                            <a href="#" onclick="event.preventDefault(); document.getElementById('create-affaire-form').submit();"
                                class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl text-sm font-semibold hover:from-purple-700 hover:to-indigo-700 transition-colors duration-200 shadow-lg shadow-purple-500/30 hover:shadow-purple-600/40">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Créer l'affaire
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
