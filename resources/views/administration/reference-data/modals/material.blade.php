<!-- Modal Créer Matériau -->
<x-modal name="create-material">
    <div class="p-6 bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Créer un matériau</h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('reference-data.material.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nom_material" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nom</label>
                <input type="text" id="nom_material" name="nom" class="input w-full" required>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn">Créer</button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Modales Modifier/Supprimer Matériau -->
@foreach($materials as $material)
<x-modal name="edit-material-{{ $material->id }}">
    <div class="p-6 bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Modifier le matériau</h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('reference-data.material.update', $material) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="nom_edit_material_{{ $material->id }}" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nom</label>
                <input type="text" id="nom_edit_material_{{ $material->id }}" name="nom" value="{{ $material->nom }}" class="input w-full" required>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn">Modifier</button>
            </div>
        </form>
    </div>
</x-modal>

<x-modal name="delete-material-{{ $material->id }}">
    <div class="p-6 bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Supprimer le matériau</h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <p class="mb-4 text-gray-900 dark:text-gray-100">Êtes-vous sûr de vouloir supprimer le matériau "{{ $material->nom }}" ?</p>
        @if(($material->matieres_count ?? 0) > 0)
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-md">
                <p class="text-red-600 dark:text-red-400 text-sm">Ce matériau est utilisé dans {{ $material->matieres_count ?? 0 }} matière(s) et ne peut pas être supprimé.</p>
            </div>
        @endif
        <div class="flex justify-end gap-4">
            <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
            @if(($material->matieres_count ?? 0) == 0)
                <form action="{{ route('reference-data.material.destroy', $material) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn bg-red-500 hover:bg-red-600 text-white">Supprimer</button>
                </form>
            @endif
        </div>
    </div>
</x-modal>
@endforeach
