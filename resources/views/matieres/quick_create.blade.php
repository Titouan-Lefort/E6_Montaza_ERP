<div class="p-2">
    <a x-on:click="$dispatch('close')">
        <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
    </a>
    <div class="p-6 ">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ajouter une Matière</h2>
        <form method="POST" action="{{ route('matieres.quickStore', $modal_id) }}" onsubmit="handleFormSubmit(event)"
            class="w-full text-gray-900 dark:text-gray-100" id="quick-create-form">
            @csrf
            <div class="mb-4 flex">
                <div class="mb-4">
                    <x-input-label for="ref_interne" :value="__('référence interne')" />
                    <x-text-input type="text" name="ref_interne" id="ref_interne" class="mt-1 block w-full" required
                        value="{{ $last_ref }}" />
                </div>
                <div class="mb-4 ml-2 flex-grow">
                    <x-input-label for="societe_id" :value="__('référence externe')" />
                    <div class="flex w-full">
                        <select name="societe_id" id="societe_id-{{ $modal_id }}"
                            class="mt-1 py-3 select-left rounded-r-none">
                            <option value="" disabled selected>Sélectionner un fournisseur</option>
                            @foreach ($societes as $societe)
                                <option value="{{ $societe->id }}">{{ $societe->raison_sociale }}</option>
                            @endforeach
                        </select>
                        <x-text-input type="text" name="ref_externe" id="ref_externe"
                            class="mt-1 block w-full rounded-l-none" placeholder="Référence" />
                    </div>
                </div>
            </div>
            {{-- désognation --}}
            <div class="mb-4 flex gap-2">
                <div class="w-3/4">
                    <x-input-label for="designation" :value="__('Désignation')" />
                    <x-text-input type="text" name="designation" id="designation" class="mt-1 block w-full" required
                        maxlength="255" />
                </div>
                <div class="">
                    <x-input-label for="material_id" :value="__('matériau')" />
                    <select name="material_id" id="material_id-{{ $modal_id }}" class="mt-1 py-3 select" required>
                        <option value="0" selected>Aucun</option>
                        @foreach ($materiaux as $materiau)
                            <option value="{{ $materiau->id }}">{{ $materiau->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- FAMILLE --}}
            <div class="mb-4 flex">
                <div class="mb-4 w-full mr-2">
                    <x-input-label for="famille_id-{{ $modal_id }}"> Famille</x-input-label>
                    <select name="famille_id" id="famille_id-{{ $modal_id }}" class="select" required
                        onchange="updateSousFamilleSelect(this.value)">
                        <option value="" disabled selected>Sélectionner une famille</option>
                        @foreach ($familles as $famille)
                            <option value="{{ $famille->id }}">{{ $famille->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4 w-full">
                    <x-input-label for="sous_famille_id-{{ $modal_id }}">Sous Famille</x-input-label>
                    <div class="flex">
                        <select name="sous_famille_id" id="sous_famille_id-{{ $modal_id }}"
                            class="select-left w-full" required>
                            <option value="" disabled selected>Sélectionner d'abord une famille</option>
                        </select>

                        <button class="btn-select-right" x-data id="addSousFamille-{{ $modal_id }}" disabled
                            x-on:click.prevent="$dispatch('open-modal', 'addSousFamille-{{ $modal_id }}')"><x-icons.add /></button>

                    </div>
                </div>
            </div>
            {{-- STANDARD --}}
            <div class="mb-4 flex">
                <div class="mb-4 w-fit mr-2">
                    <x-input-label for="dossier_standard_id-{{ $modal_id }}" optionnel
                        class="whitespace-nowrap">Dossier Standard</x-input-label>
                    <x-search-select :options="$dossier_standards
                        ->map(fn($dossier) => ['value' => $dossier->nom, 'text' => $dossier->nom])
                        ->values()" name="dossier_standard_id"
                        id="dossier_standard_id-{{ $modal_id }}" :placeholder="'Sélectionner un dossier...'" :searchPlaceholder="'Rechercher un dossier...'"
                        :value="old('dossier_standard_id')" required="false" onChange="updateStandardSelect(value)" />
                </div>
                <div class="mb-4 w-fit mr-2">
                    <x-input-label for="standard_id-{{ $modal_id }}" optionnel>Standard</x-input-label>
                    <x-search-select :options="null" name="standard_id" id="standard_id-{{ $modal_id }}"
                        :placeholder="'Sélectionner un standard...'" :searchPlaceholder="'Rechercher un standard...'" :value="old('standard_id')" required="false"
                        onChange="updateVersionSelect(value)" />
                </div>
                <div class="mb-4 w-fit">
                    <x-input-label for="standard_version_id-{{ $modal_id }}" optionnel>Rév</x-input-label>
                    <div class="flex">
                        <select name="standard_version_id" id="standard_version_id-{{ $modal_id }}"
                            class="select w-fit">
                        </select>
                        <a href="{{ route('standards.create') }}" target="_blank" type="button"
                            class="btn-select-right" title="Ajouter un Standard">
                            <x-icons.add class="icons_no_hover" size="1" />
                        </a>
                    </div>
                </div>
            </div>
            <div class="mb-4 flex">
                <div class=" mr-2">
                    <div class="w-full flex">
                        <x-input-label for="ref_valeur_unitaire-{{ $modal_id }}"
                            value="{{ __('Valeur Réf Unitaire') }}" />
                        <x-tooltip position="left">
                            <x-slot name="slot_item">
                                <x-icons.question class="icons" size="1" />
                            </x-slot>
                            <x-slot name="slot_tooltip">
                                <p class="text-sm font-bold">Valeur de référence unitaire de la matière</p>
                                <p class="text-sm">Exemple: Longueur standard de stockage, comme 6m ou 12m pour un
                                    tuyau.</p>
                            </x-slot>
                        </x-tooltip>
                    </div>
                    <x-no-or-number name="ref_valeur_unitaire" id="ref_valeur_unitaire-{{ $modal_id }}" required
                        value="non" class="mt-1" />
                </div>
                <div class="w-1/4">
                    <x-input-label for="unite_id">Unité</x-input-label>
                    <x-unite-select name="unite_id" id="unite_id" class="mt-1 py-3 select" required />
                </div>
            </div>
            <div class="mb-4">
                <x-input-label for="dn" value="{{ __('DN') }}" optionnel />
                <x-text-input type="text" name="dn" id="dn" class="mt-1 block w-full" />
            </div>
            <div class="mb-4">
                <x-input-label for="epaisseur" value="{{ __('Épaisseur') }}" optionnel />
                <x-text-input type="text" name="epaisseur" id="epaisseur" class="mt-1 block w-full" />
            </div>
            <div class="mb-4">
                <x-input-label for="stock_min" value="{{ __('Stock Minimum') }}" />
                <x-text-input type="number" name="stock_min" id="stock_min" class="mt-1 block w-full"
                    value="0" required />
            </div>
            <div class="col-span-2 flex justify-between">
                <button type="button" class="btn" x-on:click="$dispatch('close')"
                    id="quick-create-matiere-cancel-{{ $modal_id }}">Annuler</button>
                <button type="submit" class="btn">Ajouter</button>
            </div>

        </form>
        {{-- FORMULAIRE AJOUTER SOUS FAMILLE --}}
        <x-modal name="addSousFamille-{{ $modal_id }}" id="addSousFamille-{{ $modal_id }}">
            <div class="p-2">
                <a x-on:click="$dispatch('close')">
                    <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                </a>
                <div class="p-6 ">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ajouter une Sous Famille</h2>
                    <form method="POST" action="{{ route('matieres.sous_familles.store') }}"
                        onsubmit="handleSousFamilleSubmit(event)" class="w-full text-gray-900 dark:text-gray-100">
                        <div class="mb-4">
                            <x-input-label for="addSousFamille-famille_id-{{ $modal_id }}">Famille</x-input-label>
                            <select name="famille_id" id="addSousFamille-famille_id-{{ $modal_id }}"
                                class="select">
                                @foreach ($familles as $famille)
                                    <option value="{{ $famille->id }}" disabled>{{ $famille->nom }}</option>
                                @endforeach
                            </select>
                            <select name="famille_id" id="addSousFamille-famille_id_hidden-{{ $modal_id }}"
                                class="hidden">
                                @foreach ($familles as $famille)
                                    <option value="{{ $famille->id }}">{{ $famille->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <x-input-label for="nom" :value="__('Nom')" />
                            <x-text-input type="text" name="nom" id="nom" class="mt-1 block w-full"
                                required />
                        </div>
                        <div class="col-span-2 flex justify-between">
                            <button type="button" class="btn" x-on:click="$dispatch('close')"
                                id="addSousFamille-button_cancel-{{ $modal_id }}"> Annuler</button>
                            <button type="submit" class="btn">Ajouter</button>
                        </div>
                    </form>
                    <script class="SCRIPT">
                        function handleSousFamilleSubmit(event) {
                            event.preventDefault();
                            var form = event.target;
                            var formData = new FormData(form);
                            var url = form.action;

                            fetch(url, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        var sousFamilleSelect = document.getElementById('sous_famille_id-{{ $modal_id }}');
                                        var option = document.createElement('option');
                                        option.value = data.sousFamille.id;
                                        option.textContent = data.sousFamille.nom;
                                        sousFamilleSelect.appendChild(option);
                                        sousFamilleSelect.value = data.sousFamille.id;
                                        showFlashMessageFromJs('Sous Famille ajoutée avec succès !', 2000, 'success');
                                        document.getElementById('addSousFamille-button_cancel-{{ $modal_id }}').click();
                                    } else {
                                        showFlashMessageFromJs('Erreur lors de l\'ajout de la Sous Famille.', 2000, 'error');
                                    }
                                })
                                .catch(error => {
                                    showFlashMessageFromJs('Erreur lors de l\'ajout de la Sous Famille.', 2000, 'error');
                                    console.error('Erreur lors de l\'ajout de la Sous Famille :', error);
                                });
                        }
                    </script>
                </div>
            </div>
        </x-modal>
    </div>
</div>

{{-- MODAL POUR AFFICHER LES DOUBLONS --}}
<x-modal name="doublon-alert-{{ $modal_id }}" maxWidth="4xl" id="doublon-alert-{{ $modal_id }}">
    <div class="p-2">
        <a x-on:click="$dispatch('close')">
            <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
        </a>
        <div class="p-6">
            <div class="flex items-center mb-6">
                <div class="flex-shrink-0 mr-4">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                        <x-icon type="error_icon" class="h-8 w-8 text-yellow-600 dark:text-yellow-400" />
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        Doublons de références détectés
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Des références similaires ont été trouvées dans le système. Veuillez vérifier les informations ci-dessous.
                    </p>
                </div>
            </div>

            <div class="mb-6">
                <div id="doublons-list-{{ $modal_id }}" class="space-y-4">
                    <!-- Les doublons seront ajoutés ici par JavaScript -->
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                        <svg class="h-5 w-5 text-blue-500 dark:text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                            Information importante
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Créer une matière avec des références similaires peut créer de la confusion dans la gestion des stocks et des commandes.
                            Assurez-vous que c'est bien ce que vous souhaitez faire.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close')" id="doublon-cancel-{{ $modal_id }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Annuler
                </button>
                <button type="button" class="btn btn-warning" id="doublon-confirm-{{ $modal_id }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Continuer malgré les doublons
                </button>
            </div>
        </div>
    </div>
</x-modal>

<script class="SCRIPT">
    function updateSousFamilleSelect(familleId) {
        var sousFamilleSelect = document.getElementById('sous_famille_id-{{ $modal_id }}');
        sousFamilleSelect.innerHTML =
            '<option value="" disabled selected>chargement...</option>';
        fetch(`/matieres/famille/${familleId}/sous-familles/json`)
            .then(response => response.json())
            .then(data => {
                sousFamilleSelect.innerHTML =
                    '<option value="" disabled selected>Sélectionner une sous famille</option>';
                data.forEach(sousFamille => {
                    var option = document.createElement('option');
                    option.value = sousFamille.id;
                    option.textContent = sousFamille.nom;
                    sousFamilleSelect.appendChild(option);
                });
                document.getElementById('addSousFamille-{{ $modal_id }}').disabled = false;
                document.getElementById('addSousFamille-famille_id-{{ $modal_id }}').value = familleId;
                document.getElementById('addSousFamille-famille_id_hidden-{{ $modal_id }}').value = familleId;
            })

            .catch(error => {
                console.error('Erreur lors de la récupération des sous familles :', error);
            });
    }


    window.updateStandardSelect = function(dossierId) {
        // Récupérer le composant Alpine.js du search-select standard
        const standardSelectElement = document.getElementById('standard_id-{{ $modal_id }}').closest(
            '[x-data]');
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
        var versionSelect = document.getElementById('standard_version_id-{{ $modal_id }}');
        var dossierId = document.getElementById('dossier_standard_id-{{ $modal_id }}').value;
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
                    versionSelect.innerHTML =
                        '<option value="" disabled selected>Sélectionner une version</option>';

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

    function handleFormSubmit(event) {
        event.preventDefault();
        var form = event.target;
        var formData = new FormData(form);
        var url = form.action;

        fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.status === 409) {
                    // Doublon détecté
                    return response.json().then(data => {
                        showDoublonModal(data, form, formData, url);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    showFlashMessageFromJs('Matière ajoutée avec succès !', 2000, 'success');
                    document.getElementById('quick-create-matiere-cancel-{{ $modal_id }}').click();
                    // Check if a searchbar exists on the page
                    if (document.getElementById('searchbar')) {
                        // Get the designation from the form
                        var designation = formData.get('ref_interne') + ' ' + formData.get('designation');
                        // Get the searchbar element
                        var searchbar = document.getElementById('searchbar');

                        // Clear the current value
                        searchbar.value = '';

                        // Simulate typing the designation letter by letter
                        var i = 0;

                        function typeDesignation() {
                            if (i < designation.length) {
                                // Create and dispatch keyboard event
                                const event = new KeyboardEvent('keydown', {
                                    key: designation.charAt(i),
                                    code: 'Key' + designation.charAt(i).toUpperCase(),
                                    bubbles: true
                                });
                                searchbar.dispatchEvent(event);
                                // Also update the value
                                searchbar.value += designation.charAt(i);
                                // Trigger input event to ensure search functionality activates
                                searchbar.dispatchEvent(new Event('input', {
                                    bubbles: true
                                }));
                                i++;
                                setTimeout(typeDesignation, 50); // 50ms delay between each character
                            }
                        }

                        // Start typing after a small delay
                        setTimeout(typeDesignation, 300);
                    }
                } else if (data && !data.doublon_detected) {
                    showFlashMessageFromJs('Erreur lors de l\'ajout de la matière.', 2000, 'error');
                }
            })
            .catch(error => {
                showFlashMessageFromJs('Erreur lors de l\'ajout de la matière.', 2000, 'error');
                console.error('Erreur lors de l\'ajout de la matière :', error);
            });
    }

    function showDoublonModal(data, form, formData, url) {
        // Remplir la liste des doublons
        const doublonsList = document.getElementById('doublons-list-{{ $modal_id }}');
        doublonsList.innerHTML = '';

        data.doublons.forEach((doublon, index) => {
            const doublonDiv = document.createElement('div');
            doublonDiv.className = 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg p-4 shadow-sm';

            let iconColor = '';
            let badgeColor = '';
            let typeText = '';

            if (doublon.type === 'ref_interne_existe_comme_ref_externe') {
                iconColor = 'text-orange-500';
                badgeColor = 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300';
                typeText = 'Référence interne utilisée comme externe';
            } else {
                iconColor = 'text-blue-500';
                badgeColor = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300';
                typeText = 'Référence externe utilisée comme interne';
            }

            doublonDiv.innerHTML = `
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${badgeColor}">
                                ${typeText}
                            </span>
                        </div>
                        <p class="text-sm text-gray-900 dark:text-gray-100 mb-3 leading-relaxed">
                            ${doublon.message}
                        </p>
                        ${doublon.matiere_id ? `
                            <a href="/matieres/${doublon.matiere_id}" target="_blank"
                               class="inline-flex items-center text-sm ${iconColor} hover:underline font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Voir la matière existante
                            </a>
                        ` : ''}
                    </div>
                </div>
            `;

            doublonsList.appendChild(doublonDiv);
        });

        // Configurer le bouton de confirmation
        const confirmButton = document.getElementById('doublon-confirm-{{ $modal_id }}');
        confirmButton.onclick = function() {
            // Fermer le modal
            document.getElementById('doublon-cancel-{{ $modal_id }}').click();

            // Forcer la création
            formData.set('force_create', '1');

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showFlashMessageFromJs('Matière ajoutée avec succès !', 2000, 'success');
                        document.getElementById('quick-create-matiere-cancel-{{ $modal_id }}').click();
                        // Check if a searchbar exists on the page
                        if (document.getElementById('searchbar')) {
                            // Get the designation from the form
                            var designation = formData.get('ref_interne') + ' ' + formData.get('designation');
                            // Get the searchbar element
                            var searchbar = document.getElementById('searchbar');

                            // Clear the current value
                            searchbar.value = '';

                            // Simulate typing the designation letter by letter
                            var i = 0;

                            function typeDesignation() {
                                if (i < designation.length) {
                                    // Create and dispatch keyboard event
                                    const event = new KeyboardEvent('keydown', {
                                        key: designation.charAt(i),
                                        code: 'Key' + designation.charAt(i).toUpperCase(),
                                        bubbles: true
                                    });
                                    searchbar.dispatchEvent(event);
                                    // Also update the value
                                    searchbar.value += designation.charAt(i);
                                    // Trigger input event to ensure search functionality activates
                                    searchbar.dispatchEvent(new Event('input', {
                                        bubbles: true
                                    }));
                                    i++;
                                    setTimeout(typeDesignation, 50); // 50ms delay between each character
                                }
                            }

                            // Start typing after a small delay
                            setTimeout(typeDesignation, 300);
                        }
                    } else {
                        showFlashMessageFromJs('Erreur lors de l\'ajout de la matière.', 2000, 'error');
                    }
                })
                .catch(error => {
                    showFlashMessageFromJs('Erreur lors de l\'ajout de la matière.', 2000, 'error');
                    console.error('Erreur lors de l\'ajout de la matière :', error);
                });
        };

        // Ouvrir le modal
        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'doublon-alert-{{ $modal_id }}'
        }));
    }
</script>
