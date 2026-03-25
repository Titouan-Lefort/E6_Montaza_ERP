<x-app-layout>
    @section('title', 'Modifier une affaire')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier une affaire') }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 sm:rounded-lg shadow-md">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form id="edit-affaire-form" method="POST" action="{{ route('affaires.update', $affaire->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="code" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Code</label>
                            <input type="text" name="code" id="code" value="{{ $affaire->code }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 focus:outline-hidden" required>
                        </div>
                        <div class="mb-4">
                            <label for="nom" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Nom</label>
                            <input type="text" name="nom" id="nom" value="{{ $affaire->nom }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden" required>
                        </div>
                        <div class="mb-4">
                            <label for="budget" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Budget</label>
                            <input type="number" step="0.01" name="budget" id="budget" value="{{ $affaire->budget }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="date_debut" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Date de début</label>
                                <input type="date" name="date_debut" id="date_debut" value="{{ $affaire->date_debut }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden" required>
                            </div>
                            <div>
                                <label for="date_fin_prevue" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Date de fin prévue</label>
                                <input type="date" name="date_fin_prevue" id="date_fin_prevue" value="{{ $affaire->date_fin_prevue }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 focus:outline-hidden cursor-not-allowed" disabled title="La date de fin ne peut pas être modifiée">
                                <p class="text-xs text-gray-500 mt-1">La date de fin ne peut pas être modifiée.</p>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
