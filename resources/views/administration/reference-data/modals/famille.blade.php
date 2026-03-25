<!-- Modal Créer Famille -->
<x-modal name="create-famille">
    <div class="p-6 bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Créer une famille</h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('reference-data.famille.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nom" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nom</label>
                <input type="text" id="nom" name="nom" class="input w-full" required>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn">Créer</button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Modales Modifier Famille -->
@foreach($familles as $famille)
<x-modal name="edit-famille-{{ $famille->id }}">
    <div class="p-6 bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Modifier la famille</h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('reference-data.famille.update', $famille) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="nom_edit_{{ $famille->id }}" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nom</label>
                <input type="text" id="nom_edit_{{ $famille->id }}" name="nom" value="{{ $famille->nom }}" class="input w-full" required>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn">Modifier</button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Modal Supprimer Famille -->
<x-modal name="delete-famille-{{ $famille->id }}">
    <div class="p-6 bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Supprimer la famille</h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <p class="mb-4 text-gray-900 dark:text-gray-100">Êtes-vous sûr de vouloir supprimer la famille "{{ $famille->nom }}" ?</p>
        @if($famille->sousFamilles->count() > 0)
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-md">
                <p class="text-red-600 dark:text-red-400 text-sm">Cette famille contient {{ $famille->sousFamilles->count() }} sous-famille(s) et ne peut pas être supprimée.</p>
            </div>
        @endif
        <div class="flex justify-end gap-4">
            <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
            @if($famille->sousFamilles->count() == 0)
                <form action="{{ route('reference-data.famille.destroy', $famille) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn bg-red-500 hover:bg-red-600 text-white">Supprimer</button>
                </form>
            @endif
        </div>
    </div>
</x-modal>
@endforeach
