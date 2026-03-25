<x-app-layout>
    @section('title', 'Modifier la réparation')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Modifier la réparation</h2>
            <a href="{{ route('reparation.show', $reparation->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                Annuler
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="px-6 py-6 sm:p-8">
                    <form method="POST" action="{{ route('reparation.update', $reparation->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description du problème</label>
                            <textarea name="description" id="description" rows="5" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $reparation->description) }}</textarea>
                            @error('description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Statut</label>
                            <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @php $statuses = ['pending'=>'En attente', 'in_progress'=>'En cours', 'completed'=>'Terminé', 'closed'=>'Clos']; @endphp
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $reparation->status) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('reparation.show', $reparation->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Annuler</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
