<x-app-layout>
    @section('title', 'Créer une Note de Commande')

    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('administration.index') }}"
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                {{ __('Administration') }}
            </a>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                >>
            </h2>
            <a href="{{ route('administration.cdeNote.index', $entite) }}"
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                {{ __('Notes de commande') }}
            </a>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                >>
            </h2>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Créer une Note') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 max-w-4xl mx-auto">
            <h3 class="font-medium text-lg mb-4">Créer une Note de Commande</h3>
            <form action="{{ route('administration.cdeNote.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="contenu" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contenu</label>
                    <textarea id="contenu" name="contenu" rows="8" class="textarea">{{ old('contenu') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="entite_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Entité</label>
                    <select id="entite_id" name="entite_id" class="select">
                        @foreach ($entites as $entit)
                            <option value="{{ $entit->id }}" {{ old('entite_id') == $entit->id ? 'selected' : '' }}>
                                {{ $entit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <x-toggle
                        id="is_checked"
                        name="is_checked"
                        label=" Précocher la note de commande"
                         />
                    @error('is_checked')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('administration.cdeNote.index', $entite) }}" class="mr-4 text-gray-600 dark:text-gray-400 hover:underline">Annuler</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Créer</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
