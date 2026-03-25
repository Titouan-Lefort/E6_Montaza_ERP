<!-- Modal Créer Forme Juridique -->
<x-modal name="create-forme-juridique">
    <div class="p-6 bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Créer une forme juridique</h2>
            <button x-on:click="$dispatch('close')"
                class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <form action="{{ route('reference-data.forme-juridique.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="code_forme"
                    class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Code</label>
                <input type="text" id="code_forme" name="code" class="input w-full" maxlength="10" required>
            </div>
            <div class="mb-4">
                <label for="name_forme"
                    class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nom</label>
                <input type="text" id="name_forme" name="nom" class="input w-full" required>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn">Créer</button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Modales Modifier/Supprimer Forme Juridique -->
@foreach ($formesJuridiques as $forme)
    <x-modal name="edit-forme-juridique-{{ $forme->id }}">
        <div class="p-6 bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Modifier la forme juridique</h2>
                <button x-on:click="$dispatch('close')"
                    class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('reference-data.forme-juridique.update', $forme) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="code_edit_forme_{{ $forme->id }}"
                        class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Code</label>
                    <input type="text" id="code_edit_forme_{{ $forme->id }}" name="code"
                        value="{{ $forme->code }}" class="input w-full" maxlength="10" required>
                </div>
                <div class="mb-4">
                    <label for="name_edit_forme_{{ $forme->id }}"
                        class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nom</label>
                    <input type="text" id="name_edit_forme_{{ $forme->id }}" name="nom"
                        value="{{ $forme->nom }}" class="input w-full" required>
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn">Modifier</button>
                </div>
            </form>
        </div>
    </x-modal>
@endforeach
<!-- Modales de suppression -->
@foreach ($formesJuridiques as $forme)
<x-modal name="delete-forme-juridique-{{ $forme->id }}">
    <div class="p-6 bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Supprimer la forme juridique</h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <p class="mb-4 text-gray-900 dark:text-gray-100">
            Êtes-vous sûr de vouloir supprimer la forme juridique "{{ $forme->nom }}" ?
        </p>
        @if($forme->societes()->count() > 0)
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-md">
                <p class="text-red-600 dark:text-red-400 text-sm">
                    Cette forme juridique est utilisée par {{ $forme->societes()->count() }} société(s) et ne peut pas être supprimée.
                </p>
            </div>
        @endif
        <div class="flex justify-end gap-4">
            <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
            @if($forme->societes()->count() == 0)
                <form action="{{ route('reference-data.forme-juridique.destroy', $forme) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn bg-red-500 hover:bg-red-600 text-white">Supprimer</button>
                </form>
            @endif
        </div>
    </div>
</x-modal>
@endforeach
