<!-- Modal Créer Sous-famille -->
<x-modal name="create-sous-famille">
    <div class="p-6 bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Créer une sous-famille</h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('reference-data.sous-famille.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="famille_id" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Famille</label>
                <select id="famille_id" name="famille_id" class="select w-full" required>
                    <option value="">Sélectionner une famille</option>
                    @foreach($familles as $famille)
                        <option value="{{ $famille->id }}">{{ $famille->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="nom_sf" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nom</label>
                <input type="text" id="nom_sf" name="nom" class="input w-full" required>
            </div>
            <div class="mb-4">
                <label for="type_affichage_stock" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Type d'affichage stock</label>
                <select id="type_affichage_stock" name="type_affichage_stock" class="select w-full">
                    <option value="1" selected>Quantité uniquement</option>
                    <option value="2">Quantité et valeur unitaire</option>
                </select>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn">Créer</button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Modales pour chaque famille pour créer une sous-famille directement -->
@foreach($familles as $famille)
<x-modal name="create-sous-famille-{{ $famille->id }}">
    <div class="p-6 bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Créer une sous-famille pour "{{ $famille->nom }}"</h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('reference-data.sous-famille.store') }}" method="POST">
            @csrf
            <input type="hidden" name="famille_id" value="{{ $famille->id }}">
            <div class="mb-4">
                <label for="nom_sf_{{ $famille->id }}" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nom</label>
                <input type="text" id="nom_sf_{{ $famille->id }}" name="nom" class="input w-full" required>
            </div>
            <div class="mb-4">
                <label for="type_affichage_stock_{{ $famille->id }}" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Type d'affichage stock</label>
                <select id="type_affichage_stock_{{ $famille->id }}" name="type_affichage_stock" class="select w-full">
                    <option value="1" selected>Quantité uniquement</option>
                    <option value="2">Quantité et valeur unitaire</option>

                </select>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn">Créer</button>
            </div>
        </form>
    </div>
</x-modal>
@endforeach

<!-- Modales Modifier/Supprimer Sous-famille -->
@foreach($familles as $famille)
    @foreach($famille->sousFamilles as $sousFamille)
    <x-modal name="edit-sous-famille-{{ $sousFamille->id }}">
        <div class="p-6 bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Modifier la sous-famille</h2>
                <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('reference-data.sous-famille.update', $sousFamille) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="famille_id_edit_{{ $sousFamille->id }}" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Famille</label>
                    <select id="famille_id_edit_{{ $sousFamille->id }}" name="famille_id" class="select w-full" required>
                        @foreach($familles as $fam)
                            <option value="{{ $fam->id }}" {{ $fam->id == $sousFamille->famille_id ? 'selected' : '' }}>{{ $fam->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="nom_edit_sf_{{ $sousFamille->id }}" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nom</label>
                    <input type="text" id="nom_edit_sf_{{ $sousFamille->id }}" name="nom" value="{{ $sousFamille->nom }}" class="input w-full" required>
                </div>
                <div class="mb-4">
                    <label for="type_affichage_stock_edit_{{ $sousFamille->id }}" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Type d'affichage stock</label>
                    <select id="type_affichage_stock_edit_{{ $sousFamille->id }}" name="type_affichage_stock" class="select w-full">
                        <option value="">Par défaut</option>
                        <option value="1" {{ $sousFamille->type_affichage_stock == 1 ? 'selected' : '' }}>Quantité et valeur unitaire</option>
                        <option value="2" {{ $sousFamille->type_affichage_stock == 2 ? 'selected' : '' }}>Valeur unitaire uniquement</option>
                    </select>
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn">Modifier</button>
                </div>
            </form>
        </div>
    </x-modal>

    <x-modal name="delete-sous-famille-{{ $sousFamille->id }}">
        <div class="p-6 bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Supprimer la sous-famille</h2>
                <button x-on:click="$dispatch('close')" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="mb-4 text-gray-900 dark:text-gray-100">Êtes-vous sûr de vouloir supprimer la sous-famille "{{ $sousFamille->nom }}" ?</p>
            @if($sousFamille->matieres->count() > 0)
                <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-md">
                    <p class="text-red-600 dark:text-red-400 text-sm">Cette sous-famille contient {{ $sousFamille->matieres->count() }} matière(s) et ne peut pas être supprimée.</p>
                </div>
            @endif
            <div class="flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Annuler</button>
                @if($sousFamille->matieres->count() == 0)
                    <form action="{{ route('reference-data.sous-famille.destroy', $sousFamille) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn bg-red-500 hover:bg-red-600 text-white">Supprimer</button>
                    </form>
                @endif
            </div>
        </div>
    </x-modal>
    @endforeach
@endforeach
