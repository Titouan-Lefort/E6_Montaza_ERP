<!-- Modal Créer Unité -->
<x-modal name="create-unite">
    <div class="p-6 bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Créer une unité</h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('reference-data.unite.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="short_unite" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Abréviation</label>
                <input type="text" id="short_unite" name="short" class="input w-full" maxlength="10" required>
            </div>
            <div class="mb-4">
                <label for="full_unite" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nom complet</label>
                <input type="text" id="full_unite" name="full" class="input w-full" maxlength="50" required>
            </div>
            <div class="mb-4">
                <label for="full_plural_unite" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nom au pluriel</label>
                <input type="text" id="full_plural_unite" name="full_plural" class="input w-full" maxlength="50">
            </div>
            <div class="mb-4">
                <label for="type_unite" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Type</label>
                <input type="text" id="type_unite" name="type" class="input w-full" maxlength="50">
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn">Créer</button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Modales Modifier/Supprimer Unité -->
@foreach($unites as $unite)
<x-modal name="edit-unite-{{ $unite->id }}">
    <div class="p-6 bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Modifier l'unité</h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('reference-data.unite.update', $unite) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="short_edit_unite_{{ $unite->id }}" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Abréviation</label>
                <input type="text" id="short_edit_unite_{{ $unite->id }}" name="short" value="{{ $unite->short }}" class="input w-full" maxlength="10" required>
            </div>
            <div class="mb-4">
                <label for="full_edit_unite_{{ $unite->id }}" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nom complet</label>
                <input type="text" id="full_edit_unite_{{ $unite->id }}" name="full" value="{{ $unite->full }}" class="input w-full" maxlength="50" required>
            </div>
            <div class="mb-4">
                <label for="full_plural_edit_unite_{{ $unite->id }}" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nom au pluriel</label>
                <input type="text" id="full_plural_edit_unite_{{ $unite->id }}" name="full_plural" value="{{ $unite->full_plural }}" class="input w-full" maxlength="50">
            </div>
            <div class="mb-4">
                <label for="type_edit_unite_{{ $unite->id }}" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Type</label>
                <input type="text" id="type_edit_unite_{{ $unite->id }}" name="type" value="{{ $unite->type }}" class="input w-full" maxlength="50">
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn">Modifier</button>
            </div>
        </form>
    </div>
</x-modal>

<x-modal name="delete-unite-{{ $unite->id }}">
    <div class="p-6 bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Supprimer l'unité</h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <p class="mb-4 text-gray-900 dark:text-gray-100">Êtes-vous sûr de vouloir supprimer l'unité "{{ $unite->short }} - {{ $unite->full }}" ?</p>
        @if($unite->matieres_count > 0)
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-md">
                <p class="text-red-600 dark:text-red-400 text-sm">Cette unité est utilisée dans {{ $unite->matieres_count }} matière(s) et ne peut pas être supprimée.</p>
            </div>
        @endif
        <div class="flex justify-end gap-4">
            <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
            @if($unite->matieres_count == 0)
                <form action="{{ route('reference-data.unite.destroy', $unite) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn bg-red-500 hover:bg-red-600 text-white">Supprimer</button>
                </form>
            @endif
        </div>
    </div>
</x-modal>
@endforeach
