<div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 rounded-2xl">
    <!-- En-tête du modal -->
    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-t-2xl">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Ajouter un Contact</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Créez rapidement un nouveau contact</p>
        </div>
        <button x-on:click="$dispatch('close')"
            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors duration-200">
            <x-icons.close
                class="icons text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                size="1.5" unfocus />
        </button>
    </div>

    <!-- Contenu du formulaire -->
    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('societes.contacts.store') }}"
                  onsubmit="handleContactFormSubmit(event)"
                  class="w-full" id="quick-create-form">

                <!-- Section Société et Établissement -->
                <div class=" px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Société et Établissement</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="societe_id_quick_create" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Société</label>
                            <select name="societe_id" id="societe_id_quick_create" class="select w-full" required
                                    onchange="updateEtablissementSelect(this.value)">
                                @if (isset($selected_societe))
                                    <option value="{{ $selected_societe->id }}" selected>{{ $selected_societe->raison_sociale }}</option>
                                @else
                                    <option value="" disabled selected>Sélectionner une société</option>
                                @endif
                                @foreach ($societes as $societe)
                                    @if (isset($selected_societe) && $societe->id == $selected_societe->id)
                                        @continue
                                    @endif
                                    <option value="{{ $societe->id }}">{{ $societe->raison_sociale }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label for="etablissement_id_quick_create" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Établissement</label>
                            <select name="etablissement_id" id="etablissement_id_quick_create" class="select w-full" required>
                                @if (isset($selected_societe, $selected_etablissement))
                                    <option value="{{ $selected_etablissement->id }}" selected>{{ $selected_etablissement->nom }}</option>
                                @else
                                    <option value="" disabled selected>Sélectionner d'abord une société</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section Informations Contact -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Informations Contact</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom -->
                        <div class="space-y-2">
                            <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom complet</label>
                            <x-text-input type="text" name="nom" id="nom" class="w-full" placeholder="Prénom et nom" required />
                        </div>

                        <!-- Fonction -->
                        <div class="space-y-2">
                            <label for="fonction" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Poste</label>
                            <x-text-input type="text" name="fonction" id="fonction" class="w-full" placeholder="Poste ou fonction" />
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <x-text-input type="email" name="email" id="email" class="w-full pl-10" placeholder="email@exemple.com" required />
                            </div>
                        </div>

                        <!-- Téléphones -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Téléphones</label>
                            <div class="space-y-2">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                    <x-text-input type="tel" name="telephone_fixe" id="telephone_fixe" class="w-full pl-10" placeholder="Téléphone fixe" />
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <x-text-input type="tel" name="telephone_portable" id="telephone_portable" class="w-full pl-10" placeholder="Téléphone portable" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 flex justify-between items-center rounded-b-2xl">
                    <button type="button" class="btn transition-colors duration-200" x-on:click="$dispatch('close')">
                        Annuler
                    </button>
                    <button type="submit" class="btn transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Ajouter le contact</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script class="SCRIPT">
    function handleContactFormSubmit(event) {
        event.preventDefault();
        var form = event.target;
        var formData = new FormData(form);
        var submitButton = form.querySelector('button[type="submit"]');

        // Animation du bouton pendant le chargement
        var originalContent = submitButton.innerHTML;
        submitButton.innerHTML = `
            <svg class="w-5 h-5 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <span>Ajout en cours...</span>
        `;
        submitButton.disabled = true;

        fetch(form.action, {
            method: 'POST',
            headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fermer le modal
                document.querySelector('[x-on\\:click="$dispatch(\'close\')"]').click();
                // Afficher le message
                showFlashMessageFromJs('Contact ajouté avec succès', 2000, 'success');
            } else {
                showFlashMessageFromJs('Erreur lors de la création du contact', 2000, 'error');
                console.error('Erreur lors de la création du contact :', data);
            }
        })
        .catch(error => {
            showFlashMessageFromJs('Erreur lors de la création du contact', 2000, 'error');
            console.error('Erreur lors de la création du contact :', error);
        })
        .finally(() => {
            // Restaurer le bouton
            submitButton.innerHTML = originalContent;
            submitButton.disabled = false;
            @if (isset($reload_after_submit) && $reload_after_submit == true)
            location.reload()
            @endif
        });
    }

    function updateEtablissementSelect(societeId) {
        var etablissementSelect = document.getElementById('etablissement_id_quick_create');

        // Efface les anciennes options
        etablissementSelect.innerHTML = '<option value="" disabled selected>Chargement...</option>';
            fetch(`/societe/${societeId}/etablissements/json`)
                .then(response => response.json())
                .then(data => {
                    etablissementSelect.innerHTML = '';
                    if (data.length === 1) {
                        var etablissement = data[0];
                        var option = document.createElement('option');
                        option.value = etablissement.id;
                        option.textContent = etablissement.nom;
                        option.selected = true;
                        etablissementSelect.appendChild(option);
                    } else {
                        var defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = "Sélectionner un établissement";
                        defaultOption.disabled = true;
                        defaultOption.selected = true;
                        etablissementSelect.appendChild(defaultOption);

                        data.forEach(etablissement => {
                            var option = document.createElement('option');
                            option.value = etablissement.id;
                            option.textContent = etablissement.nom;
                            etablissementSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des établissements :', error);
                });
    }
</script>
