<x-app-layout>
    @section('title', 'Réparations')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {!! __('Demande de réparation') !!}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <form method="POST" action="{{ route('reparation.store') }}" class="space-y-6">
                @csrf

                {{-- Materiel select --}}
                <div>
                    <label for="materiel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Matériel') }}</label>
                    <div class="mt-1">
                        <select id="materiel_id" name="materiel_id" required class="block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">{{ __('Choisir un matériel') }}</option>
                            @foreach($materiels as $materiel)
                                <option value="{{ $materiel->id }}" {{ old('materiel_id') == $materiel->id ? 'selected' : '' }}>{{ $materiel->designation }}, {{ $materiel->reference }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('materiel_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Description textarea --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Description du problème') }}</label>
                    <div class="mt-1">
                        <textarea id="description" name="description" rows="5" required class="block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">{{ old('description') }}</textarea>
                    </div>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Submit button --}}
                <div class="flex items-center justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">{{ __('Envoyer la demande') }}</button>
                </div>
            </form>
        </div>
    </div>
    <script>

    </script>
</x-app-layout>
