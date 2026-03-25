<x-app-layout>
    @section('title', 'Modifier Matière - ' . $matiere->designation)

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('matieres.index') }}" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Matières</a>
                >> <a href="{{ route('matieres.show', $matiere->id) }}" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{{ $matiere->designation }}</a>
                >> Modifier
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-blue-100 dark:bg-blue-900/30 rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold">Modifier la Matière</h2>

                    </div>

                    @if ($matiere->isLocked())
                        <x-tooltip position="right" class="mb-6">
                            <x-slot name="slot_item">
                                <div class="flex items-center gap-2 text-yellow-700 dark:text-yellow-300 ">
                                    <x-icons.lock class="w-5 h-5 fill-yellow-700 dark:fill-yellow-300" />
                                    <span class="font-bold">Matière verrouillée</span>
                                </div>
                            </x-slot>
                            <x-slot name="slot_tooltip">
                                <h3 class="font-bold mb-1">Matière verrouillée</h3>
                                <p>Cette matière a déjà été utilisée dans un ou plusieurs mouvements de stock ou est associée à des fournisseurs. Pour préserver l'intégrité des données, seuls certains champs peuvent être modifiés :</p>
                                <ul class="list-disc ml-6 mt-2">
                                    <li><strong>Sous-famille</strong> - Permet de reclasser la matière dans une autre sous-famille</li>
                                    <li><strong>Valeur de référence unitaire</strong> - Peut être ajustée pour refléter les standards actuels</li>
                                    <li><strong>Standard</strong> - Peut être mis à jour selon les normes en vigueur</li>
                                </ul>
                                <p class="mt-2 italic">Les autres caractéristiques comme la désignation, la référence, ou l'unité ne peuvent plus être modifiées car cela pourrait affecter les données historiques et les rapports existants.</p>
                            </x-slot>
                        </x-tooltip>
                    @else
                        <x-tooltip position="right" class="mb-6">
                            <x-slot name="slot_item">
                                <div class="flex items-center gap-2 text-blue-700 dark:text-blue-300">
                                    <x-icon type="edit" class="w-5 h-5" size="2" />
                                    <span class="font-bold">Matière modifiable</span>
                                </div>
                            </x-slot>
                            <x-slot name="slot_tooltip">
                                <h3 class="font-bold mb-1">Matière modifiable</h3>
                                <p>Cette matière n'a pas encore été utilisée dans des mouvements de stock ni associée à des fournisseurs. Tous les champs sont donc modifiables.</p>
                                <p class="mt-2 italic">Une fois que la matière aura été utilisée dans un mouvement de stock ou associée à un fournisseur, certains champs seront verrouillés pour préserver l'intégrité des données.</p>
                            </x-slot>
                        </x-tooltip>
                    @endif

                    <form method="POST" action="{{ route('matieres.update', $matiere->id) }}" class="w-full">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="ref_interne" :value="__('Référence interne')" class="mb-1" />
                                <x-text-input type="text" name="ref_interne" id="ref_interne" class="block w-full" required
                                    value="{{ old('ref_interne', $matiere->ref_interne) }}"
                                    disabled="{{ $matiere->isLocked() ? true : false }}" />
                                @if ($matiere->isLocked())
                                    <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">Ce champ ne peut pas être modifié</p>
                                @endif
                                @error('ref_interne')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="designation" :value="__('Désignation')" class="mb-1" />
                                <x-text-input type="text" name="designation" id="designation" class="block w-full" required value="{{ old('designation', $matiere->designation) }}" disabled="{{ $matiere->isLocked() ? true : false }}"  />
                                @if ($matiere->isLocked())
                                    <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">Ce champ ne peut pas être modifié</p>
                                @endif
                                @error('designation')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- FAMILLE ET SOUS-FAMILLE --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="famille_id" :value="__('Famille')" class="mb-1" />
                                <select name="famille_id" id="famille_id" class="select block w-full"
                                    onchange="updateSousFamilleSelect(this.value)" >
                                    <option value="" disabled>Sélectionner une famille</option>
                                    @foreach ($familles as $famille)
                                        <option value="{{ $famille->id }}" {{ $matiere->sousFamille->famille_id == $famille->id ? 'selected' : '' }}>
                                            {{ $famille->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('famille_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="sous_famille_id" :value="__('Sous-famille')" class="mb-1" />
                                <div class="flex">
                                    <select name="sous_famille_id" id="sous_famille_id" class="select block w-full">
                                        @foreach ($sousFamilles as $sousFamille)
                                            <option value="{{ $sousFamille->id }}" {{ $matiere->sous_famille_id == $sousFamille->id ? 'selected' : '' }}>
                                                {{ $sousFamille->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('sous_famille_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- STANDARD --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <x-input-label for="dossier_standard_id" :value="__('Dossier Standard')" optionnel class="mb-1" />
                                <x-search-select :options="$dossier_standards
                                    ->map(fn($dossier) => [
                                        'value' => $dossier->nom,
                                        'text' => $dossier->nom,
                                        'selected' => $matiere->standardVersion && $matiere->standardVersion->standard->dossier_standard_id == $dossier->id
                                    ])
                                    ->values()"
                                    name="dossier_standard_id"
                                    id="dossier_standard_id"
                                    :placeholder="'Sélectionner un dossier...'"
                                    :searchPlaceholder="'Rechercher un dossier...'"
                                    :value="$matiere->standardVersion ? $matiere->standardVersion->standard->dossierStandard->nom : ''"
                                    required="false"
                                    onChange="updateStandardSelect(value)" />
                                @error('dossier_standard_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="standard_id" :value="__('Standard')" optionnel class="mb-1" />
                                <x-search-select :options="$matiere->standardVersion ?
                                    $standards->map(fn($standard) => [
                                        'value' => $standard->nom,
                                        'text' => $standard->nom,
                                        'selected' => $matiere->standardVersion->standard_id == $standard->id
                                    ])->values() :
                                    [[
                                        'value' => '',
                                        'text' => 'Sélectionner d\'abord un dossier',
                                        'disabled' => true,
                                        'selected' => true
                                    ]]"
                                    name="standard_id"
                                    id="standard_id"
                                    :placeholder="'Sélectionner un standard...'"
                                    :searchPlaceholder="'Rechercher un standard...'"
                                    :value="$matiere->standardVersion ? $matiere->standardVersion->standard->nom : ''"
                                    required="false"
                                    onChange="updateVersionSelect(value)" />
                                @error('standard_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="standard_version" :value="__('Révision')" optionnel class="mb-1" />
                                <div class="flex">
                                    <select name="standard_version" id="standard_version" class="select w-full">
                                        <option value="" disabled {{ !$matiere->standardVersion ? 'selected' : '' }}>Sélectionner d'abord un standard</option>
                                        @if ($matiere->standardVersion)
                                            @foreach ($versions as $version)
                                                <option value="{{ $version->version }}" {{ $matiere->standard_version_id == $version->id ? 'selected' : '' }}>
                                                    {{ $version->version }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <a href="{{ route('standards.create') }}" target="_blank" type="button"
                                        class="btn-select-right" title="Ajouter un Standard">
                                        <x-icons.add class="icons_no_hover" size="1" />
                                    </a>
                                </div>
                                @error('standard_version')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <div class="flex items-center">
                                    <x-input-label for="ref_valeur_unitaire" :value="__('Valeur Réf Unitaire')" class="mb-1" />
                                    <x-tooltip position="top">
                                        <x-slot name="slot_item">
                                            <x-icons.question class="icons" size="1" />
                                        </x-slot>
                                        <x-slot name="slot_tooltip">
                                            <p class="text-sm font-bold">Valeur de référence unitaire de la matière</p>
                                            <p class="text-sm">Exemple: Longueur standard de stockage, comme 6m ou 12m pour un tuyau.</p>
                                        </x-slot>
                                    </x-tooltip>
                                </div>
                                @if ($matiere->isLocked() && $matiere->ref_valeur_unitaire == null)
                                    <x-no-or-number name="ref_valeur_unitaire" id="ref_valeur_unitaire"
                                    value="{{ $matiere->ref_valeur_unitaire ? $matiere->ref_valeur_unitaire : 'non' }}"
                                    numberValue="{{ old('ref_valeur_unitaire', $matiere->ref_valeur_unitaire) }}"
                                    class="block w-full" disabled />
                                    <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">Ce champ ne peut pas être modifié</p>
                                    @elseif ($matiere->isLocked())
                                    <x-no-or-number name="ref_valeur_unitaire" id="ref_valeur_unitaire"
                                    value="{{ $matiere->ref_valeur_unitaire ? $matiere->ref_valeur_unitaire : 'non' }}"
                                    numberValue="{{ old('ref_valeur_unitaire', $matiere->ref_valeur_unitaire) }}"
                                    class="block w-full" onlyNumber />
                                    @else
                                    <x-no-or-number name="ref_valeur_unitaire" id="ref_valeur_unitaire"
                                    value="{{ $matiere->ref_valeur_unitaire ? $matiere->ref_valeur_unitaire : 'non' }}"
                                    numberValue="{{ old('ref_valeur_unitaire', $matiere->ref_valeur_unitaire) }}"
                                    class="block w-full" />
                                @endif

                                @error('ref_valeur_unitaire')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="unite_id" :value="__('Unité')" class="mb-1" />
                                <x-unite-select
                                    name="unite_id"
                                    id="unite_id"
                                    class="block w-full"
                                    required
                                    :selected="$matiere->unite_id"
                                    :disabled="$matiere->isLocked()" />
                                @if ($matiere->isLocked())
                                    <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">Ce champ ne peut pas être modifié</p>
                                @endif
                                @error('unite_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="material_id" :value="__('Matière')" class="mb-1" />
                                <select name="material_id" id="material_id" class="select block w-full"
                                    {{ $matiere->isLocked() ? 'disabled' : '' }}>
                                    <option value="">Aucune</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}" {{ $matiere->material_id == $material->id ? 'selected' : '' }}>
                                            {{ $material->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($matiere->isLocked())
                                    <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">Ce champ ne peut pas être modifié</p>
                                @endif
                                @error('material_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="stock_min" :value="__('Stock Minimum')" class="mb-1" />
                                <x-text-input type="number" name="stock_min" id="stock_min" class="block w-full"
                                    value="{{ old('stock_min', $matiere->stock_min) }}" required
                                     />
                                @error('stock_min')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="dn" :value="__('DN')" optionnel class="mb-1" />
                                <x-text-input type="text" name="dn" id="dn" class="block w-full"
                                    value="{{ old('dn', $matiere->dn) }}"
                                    disabled="{{ $matiere->isLocked() ? true : false }}" />
                                @if ($matiere->isLocked())
                                    <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">Ce champ ne peut pas être modifié</p>
                                @endif
                                @error('dn')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <x-input-label for="epaisseur" :value="__('Épaisseur')" optionnel class="mb-1" />
                                <x-text-input type="text" name="epaisseur" id="epaisseur" class="block w-full"
                                    value="{{ old('epaisseur', $matiere->epaisseur) }}"
                                    disabled="{{ $matiere->isLocked() ? true : false }}" />
                                @if ($matiere->isLocked())
                                    <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">Ce champ ne peut pas être modifié</p>
                                @endif
                                @error('epaisseur')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <a href="{{ route('matieres.show', $matiere->id) }}"
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white rounded-md transition-colors duration-200">
                                Annuler
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white rounded-md transition-colors duration-200">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>

                    <script>
                        function updateSousFamilleSelect(familleId) {
                            var sousFamilleSelect = document.getElementById('sous_famille_id');
                            sousFamilleSelect.innerHTML = '<option value="" disabled selected>Chargement...</option>';

                            fetch(`/matieres/famille/${familleId}/sous-familles/json`)
                                .then(response => response.json())
                                .then(data => {
                                    sousFamilleSelect.innerHTML = '<option value="" disabled selected>Sélectionner une sous famille</option>';
                                    data.forEach(sousFamille => {
                                        var option = document.createElement('option');
                                        option.value = sousFamille.id;
                                        option.textContent = sousFamille.nom;
                                        sousFamilleSelect.appendChild(option);
                                    });
                                })
                                .catch(error => {
                                    console.error('Erreur lors de la récupération des sous familles :', error);
                                });
                        }

                        window.updateStandardSelect = function(dossierId) {
                            // Récupérer le composant Alpine.js du search-select standard
                            const standardSelectElement = document.getElementById('standard_id').closest('[x-data]');
                            const standardSelectComponent = Alpine.$data(standardSelectElement);

                            // Réinitialiser les options avec un message de chargement
                            standardSelectComponent.options = [{
                                value: '',
                                text: 'Chargement...',
                                disabled: true,
                                selected: true
                            }];
                            standardSelectComponent.selected = '';
                            standardSelectComponent.selectedText = '';

                            if (!dossierId) {
                                // Remettre le message par défaut si pas de dossier sélectionné
                                standardSelectComponent.options = [{
                                    value: '',
                                    text: 'Sélectionner d\'abord un dossier',
                                    disabled: true,
                                    selected: true
                                }];
                                return;
                            }

                            fetch(`/matieres/standards/${dossierId}/standards/json`)
                                .then(response => response.json())
                                .then(data => {
                                    let newOptions;
                                    if (!data || data.length === 0) {
                                        newOptions = [{
                                            value: '',
                                            text: 'Aucun standard disponible',
                                            disabled: true,
                                            selected: true
                                        }];
                                    } else {
                                        newOptions = data.map(standard => ({
                                            value: standard.nom,
                                            text: standard.nom,
                                            disabled: false,
                                            selected: false
                                        }));
                                        // Ajouter une option par défaut
                                        newOptions.unshift({
                                            value: '',
                                            text: 'Sélectionner un standard',
                                            disabled: false,
                                            selected: true
                                        });
                                    }

                                    standardSelectComponent.options = newOptions;
                                    standardSelectComponent.selected = '';
                                    standardSelectComponent.selectedText = '';
                                })
                                .catch(error => {
                                    console.error('Erreur lors de la récupération des standards :', error);
                                    standardSelectComponent.options = [{
                                        value: '',
                                        text: 'Erreur lors du chargement',
                                        disabled: true,
                                        selected: true
                                    }];
                                });
                        }

                        function updateVersionSelect(standardNom) {
                            var versionSelect = document.getElementById('standard_version');
                            var dossierId = document.getElementById('dossier_standard_id').value;
                            versionSelect.innerHTML = '<option value="" disabled selected>Chargement...</option>';

                            if (!dossierId || !standardNom) {
                                versionSelect.innerHTML = '<option value="" disabled selected>Sélectionner d\'abord un standard</option>';
                                return;
                            }

                            fetch(`/matieres/standards/${dossierId}/${standardNom}/versions/json`)
                                .then(response => response.json())
                                .then(data => {
                                    versionSelect.innerHTML = ''; // Réinitialiser les options
                                    if (data.length === 1) {
                                        var option = document.createElement('option');
                                        option.value = data[0];
                                        option.textContent = data[0];
                                        option.selected = true;
                                        versionSelect.appendChild(option);
                                        versionSelect.value = data[0];
                                    } else {
                                        versionSelect.innerHTML = '<option value="" disabled selected>Sélectionner une version</option>';

                                        data.forEach(version => {
                                            var option = document.createElement('option');
                                            option.value = version;
                                            option.textContent = version;
                                            versionSelect.appendChild(option);
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Erreur lors de la récupération des versions :', error);
                                    versionSelect.innerHTML = '<option value="" disabled selected>Erreur lors du chargement</option>';
                                });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
