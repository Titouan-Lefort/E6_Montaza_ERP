<x-modal name="{{ $name ?? 'contacts-modal' }}" focusable maxWidth="6xl">
    <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 rounded-2xl">
        <!-- En-tête du modal -->
        <div
            class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-t-2xl">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Contacts - {{ $contacts->first() ? $contacts->first()->etablissement->nom : '' }} <small class="text-gray-600 dark:text-gray-300 text-xs">{{ $contacts->first() ? $contacts->first()->etablissement->societe->raison_sociale : '' }}</small></h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Gérez les contacts de cette société</p>
            </div>
            <button x-on:click="$dispatch('close')"
                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors duration-200">
                <x-icons.close
                    class="icons text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    size="1.5" unfocus />
            </button>
        </div>

        <div class="p-6">
            <div
                class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Tableau des contacts -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <!-- En-tête du tableau -->
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Nom
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Poste
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Email
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Téléphone
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <!-- Corps du tableau -->
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                            id="{{ $name ?? 'contacts-modal' }}-tbody">
                            @if ($contacts->isEmpty())
                                <tr>
                                    <td class="px-6 py-12 text-center" colspan="5">
                                        <div class="flex flex-col items-center space-y-3">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Aucun
                                                    contact</h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Aucun contact n'a
                                                    été ajouté pour cette société.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @foreach ($contacts as $contact)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200"
                                        data-contact-id="{{ $contact->id }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $contact->prenom }} {{ $contact->nom }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                                {{ $contact->fonction }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="mailto:{{ $contact->email }}"
                                                class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                {{ $contact->email }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="space-y-1">
                                                @if ($contact->telephone_fixe)
                                                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                            </path>
                                                        </svg>
                                                        <span class="text-xs text-gray-500 mr-1">Fixe:</span>
                                                        <a href="tel:{{ $contact->telephone_fixe }}"
                                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                            {{ $contact->telephone_fixe }}
                                                        </a>
                                                    </div>
                                                @endif
                                                @if ($contact->telephone_portable)
                                                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                        <span class="text-xs text-gray-500 mr-1">Mobile:</span>
                                                        <a href="tel:{{ $contact->telephone_portable }}"
                                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                            {{ $contact->telephone_portable }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <x-boutons.supprimer modalTitle="Supprimer le contact"
                                                confirmButtonText="Confirmer la suppression" cancelButtonText="Annuler"
                                                modalName="delete-contact-modal-{{ $contact->id }}"
                                                errorName="delete-contact-{{ $contact->id }}"
                                                onSubmit="deleteContact({{ $contact->id }})"
                                                userInfo="Êtes-vous sûr de vouloir supprimer ce contact ? Cette action est irréversible.">
                                                <x-slot:customButton>
                                                    <button type="button"
                                                        class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </x-slot:customButton>
                                            </x-boutons.supprimer>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            <!-- Formulaire d'ajout -->
                            <tr class=" border-t-2 border-gray-200 dark:border-gray-700">
                                <td colspan="5" class="p-0">
                                    <form method="POST" class="w-full" id="form-{{ $name ?? 'contacts-modal' }}">
                                        <h4
                                            class="text-md px-6 pt-4 font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Ajouter
                                            un contact</h4>

                                        @csrf
                                        <input type="hidden" name="etablissement_id"
                                            value="{{ $contact->etablissement->id ?? '' }}">

                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 pt-2">
                                            <!-- Nom et Prénom -->
                                            <div class="space-y-2">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom
                                                    complet</label>
                                                <div class="space-y-2">
                                                    <x-text-input type="text" name="nom"
                                                        placeholder="Prénom & nom" required />
                                                </div>
                                            </div>

                                            <!-- Poste -->
                                            <div class="space-y-2">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Poste</label>
                                                <x-text-input type="text" name="fonction"
                                                    placeholder="Poste ou fonction" />
                                            </div>

                                            <!-- Email -->
                                            <div class="space-y-2">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                                <x-text-input type="email" name="email"
                                                    placeholder="email@exemple.com" required />
                                            </div>

                                            <!-- Téléphones et bouton -->
                                            <div class="space-y-2">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Téléphones</label>
                                                <div class="flex space-x-2">
                                                    <div class="flex-1 space-y-2">
                                                        <x-text-input type="tel" name="telephone_fixe"
                                                            placeholder="Fixe" />
                                                        <x-text-input type="tel" name="telephone_portable"
                                                            placeholder="Mobile" />
                                                    </div>
                                                    <button type="submit" class="btn self-end h-24 w-12">
                                                        <svg class="w-6 h-6 mx-auto" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour supprimer un contact
        function deleteContact(contactId) {

            fetch(`/societe/contact/${contactId}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showFlashMessageFromJs(data.message || 'Contact supprimé avec succès', 2000, 'success');

                        // Supprimer la ligne du tableau
                        const row = document.querySelector(`tr[data-contact-id="${contactId}"]`);
                        if (row) {
                            row.remove();
                        }
                    } else {
                        showFlashMessageFromJs(data.message || 'Erreur lors de la suppression', 2000, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showFlashMessageFromJs('Erreur lors de la suppression du contact', 2000, 'error');
                });
        }

        document.getElementById('form-{{ $name ?? 'contacts-modal' }}').addEventListener('submit', function(event) {
            event.preventDefault();

            let formData = new FormData(this);
            // Supprimer le token CSRF des données du formulaire
            formData.delete('_token');

            const submitButton = this.querySelector('button[type="submit"]');


            // Animation du bouton pendant le chargement
            submitButton.innerHTML =
                '<svg class="w-5 h-5 mx-auto animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
            submitButton.disabled = true;

            fetch('{{ route('societes.contacts.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.getElementById('form-{{ $name ?? 'contacts-modal' }}')
                            .querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showFlashMessageFromJs('Contact ajouté avec succès', 2000, 'success');

                        // Récupérer les données du formulaire
                        const nom = formData.get('nom');
                        const fonction = formData.get('fonction');
                        const email = formData.get('email');
                        const telephoneFixe = formData.get('telephone_fixe');
                        const telephonePortable = formData.get('telephone_portable');

                        const tbody = document.getElementById('{{ $name ?? 'contacts-modal' }}-tbody');
                        // Créer la nouvelle ligne HTML
                        const newRowHTML = `
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200" data-contact-id="${data.contact.id}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                         ${nom}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                        ${fonction || ''}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="mailto:${email}"
                                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        ${email}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="space-y-1">
                                        ${telephoneFixe ? `
                                                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                            </svg>
                                                            <span class="text-xs text-gray-500 mr-1">Fixe:</span>
                                                            <a href="tel:${telephoneFixe}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                                ${telephoneFixe}
                                                            </a>
                                                        </div>
                                                    ` : ''}
                                        ${telephonePortable ? `
                                                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                            </svg>
                                                            <span class="text-xs text-gray-500 mr-1">Mobile:</span>
                                                            <a href="tel:${telephonePortable}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                                ${telephonePortable}
                                                            </a>
                                                        </div>
                                                    ` : ''}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button onclick="if(confirm('Êtes-vous sûr de vouloir supprimer ce contact ? Cette action est irréversible.')) deleteContact(${data.contact.id})"
                                        class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        `;

                        // Trouver la ligne du formulaire (dernière ligne)
                        const allRows = tbody.querySelectorAll('tr');
                        const formRow = allRows[allRows.length - 1];

                        // Insérer la nouvelle ligne avant la ligne du formulaire
                        formRow.insertAdjacentHTML('beforebegin', newRowHTML);

                        // Réinitialiser le formulaire
                        this.reset();
                    } else {
                        showFlashMessageFromJs('Erreur lors de l\'ajout du contact', 2000, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showFlashMessageFromJs('Erreur lors de l\'ajout du contact', 2000, 'error');
                })
                .finally(() => {
                    // Restaurer le bouton
                    submitButton.innerHTML =
                        '<svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>';
                    submitButton.disabled = false;
                });
        });
    </script>
</x-modal>
