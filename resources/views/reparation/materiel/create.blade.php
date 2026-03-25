<x-app-layout>
    @section('title', isset($materiel) ? 'Modifier le matériel' : 'Ajouter du matériel')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ isset($materiel) ? 'Modifier le matériel' : 'Ajouter du matériel' }}
            </h2>
            <a href="{{ route('reparation.materiel.index') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ isset($materiel) ? route('reparation.materiel.update', $materiel->id) : route('reparation.materiel.store') }}">
                        @csrf
                        @if(isset($materiel))
                            @method('PATCH')
                        @endif

                        <!-- Référence -->
                        <div class="mb-6">
                            <label for="reference" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Référence <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="reference"
                                id="reference"
                                value="{{ old('reference', $materiel->reference ?? '') }}"
                                class="w-full px-4 py-2 border @error('reference') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors"
                                required>
                            @error('reference')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Désignation -->
                        <div class="mb-6">
                            <label for="designation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Désignation <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="designation"
                                id="designation"
                                value="{{ old('designation', $materiel->designation ?? '') }}"
                                class="w-full px-4 py-2 border @error('designation') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors"
                                required>
                            @error('designation')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea
                                name="description"
                                id="description"
                                rows="5"
                                class="w-full px-4 py-2 border @error('description') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">{{ old('description', $materiel->description ?? '') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Numéro de série -->
                        <div class="mb-6">
                            <label for="numero_serie" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Numéro de série
                            </label>
                            <input type="text"
                                name="numero_serie"
                                id="numero_serie"
                                value="{{ old('numero_serie', $materiel->numero_serie ?? '') }}"
                                class="w-full px-4 py-2 border @error('numero_serie') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors font-mono"
                                placeholder="AUTO-XXXXXXXX (généré automatiquement si vide)">
                            @error('numero_serie')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Laissez vide pour générer automatiquement</p>
                        </div>

                        <!-- Date d'acquisition -->
                        <div class="mb-8">
                            <label for="acquisition_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date d'acquisition
                            </label>
                            <input type="date"
                                name="acquisition_date"
                                id="acquisition_date"
                                value="{{ old('acquisition_date', isset($materiel) && $materiel->acquisition_date ? $materiel->acquisition_date->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-2 border @error('acquisition_date') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                            @error('acquisition_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">La date du jour sera utilisée par défaut</p>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex justify-between items-center space-x-4">
                            <a href="{{ route('reparation.materiel.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ isset($materiel) ? 'Enregistrer les modifications' : 'Ajouter le matériel' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
