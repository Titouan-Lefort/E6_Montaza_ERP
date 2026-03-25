<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {!! __('Sociétés') !!}
            </h2>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center ">
                <form method="GET" action="{!! route('societes.index') !!}"
                    class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center">
                    <select name="type" id="type" onchange="this.form.submit()"
                        class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 ">
                        <option value="" selected>{!! __('Tous les types') !!}</option>
                        @foreach ($societeTypes as $societeType)
                            <option value="{{ $societeType->id }}"
                                {{ request('type') == $societeType->id ? 'selected' : '' }}>
                                {!! $societeType->nom . '&nbsp;&nbsp;' !!}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" name="search" placeholder="Rechercher..." value="{!! request('search') !!}"
                        oninput="debounceSubmit(this.form)"
                        class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    <div class="flex items-center ml-4 my-1 ">
                        <label for="nombre"
                            class="mr-2 text-gray-900 dark:text-gray-100">{!! __('Quantité') !!}</label>
                        <input type="number" name="nombre" id="nombre" value="{!! old('nombre', request('nombre', 20)) !!}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 w-20 mr-2 ">
                    </div>
                    <button type="submit" class="mr-2 btn w-full sm:w-auto sm:mt-0 md:mt-0 lg:mt-0">
                        {!! __('Rechercher') !!}
                    </button>
                    @if (Auth::user()->hasPermission('gerer_les_societes'))
                        <a href="{!! route('societes.create') !!}"
                            class="btn whitespace-nowrap w-fit-content sm:mt-0 md:mt-0 lg:mt-0">
                            {!! __('Ajouter une société') !!}
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            @if ($societes->isEmpty())
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg shadow-md p-6 text-center text-gray-700 dark:text-gray-300">
                    {!! __('Aucune société trouvée') !!}
                </div>
            @else
                <div class="space-y-2">
                    @foreach ($societes as $societe)
                        <div
                            class="bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg shadow-sm transition-all duration-300 border border-gray-200 dark:border-gray-700">
                            <!-- En-tête de la société (toujours visible) -->
                            <div class="flex justify-between items-center p-3 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors"
                                onclick="toggleSociete('{{ $societe->id }}'); rotateArrow('societe-arrow-{{ $societe->id }}')">
                                <div class="flex items-center">
                                    <x-icon :size="1" type="arrow_back" id="societe-arrow-{{ $societe->id }}"
                                        class="transform -rotate-90 mr-2 transition-transform text-gray-600 dark:text-gray-400" />
                                    <div>
                                        <h3 class="font-semibold text-base text-gray-800 dark:text-gray-200">
                                            {{ $societe->raison_sociale }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $societe->societeType->nom }}
                                            @if ($societe->etablissements->count() > 0)
                                                • {{ $societe->etablissements->count() }}
                                                établissement{{ $societe->etablissements->count() > 1 ? 's' : '' }}
                                            @endif
                                            • {{ $societe->societeContacts->count() }}
                                            contact{{ $societe->societeContacts->count() > 1 ? 's' : '' }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('societes.show', $societe->id) }}" class="btn-sm"
                                    onclick="event.stopPropagation()" target="_blank">
                                    <x-icon size="1" type="open_in_new" class="icons" />
                                </a>
                            </div>

                            <!-- Contenu expansible de la société -->
                            <div id="societe-content-{{ $societe->id }}" class="hidden">
                                <!-- Onglets -->
                                <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-850">
                                    <ul class="flex flex-wrap -mb-px text-xs font-medium text-center">
                                        <li class="mr-2" role="presentation">
                                            <button id="infos-tab-{{ $societe->id }}"
                                                onclick="showSocieteTab('{{ $societe->id }}', 'infos')"
                                                class="inline-block p-3 border-b-2 border-blue-500 dark:border-blue-400 rounded-t-lg text-blue-600 dark:text-blue-400"
                                                type="button">
                                                Informations société
                                            </button>
                                        </li>
                                        <li class="mr-2" role="presentation">
                                            <button id="etablissements-tab-{{ $societe->id }}"
                                                onclick="showSocieteTab('{{ $societe->id }}', 'etablissements')"
                                                class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:border-gray-300 dark:hover:border-gray-600 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                                                type="button">
                                                Établissements ({{ $societe->etablissements->count() }})
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Contenu des onglets -->
                                <div class="p-3 bg-white dark:bg-gray-800">
                                    <!-- Onglet Informations société -->
                                    <div id="societe-infos-{{ $societe->id }}" class="societe-tab-content">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div class="space-y-1">
                                                <x-copiable_text titre="Siren : " text="{{ $societe->siren }}" />
                                                <x-copiable_text titre="Forme juridique : "
                                                    text="{{ $societe->formeJuridique->code }}" />
                                                <x-copiable_text titre="Code APE : "
                                                    text="{{ $societe->codeApe->code ?? '' }}" />
                                                <x-copiable_text titre="N° de TVA intra. : "
                                                    text="{{ $societe->numero_tva }}" />
                                            </div>
                                            <div class="space-y-1">
                                                <x-copiable_text titre="Téléphone : "
                                                    text="{{ $societe->telephone }}" />
                                                <x-copiable_text titre="Email : " text="{{ $societe->email }}" />
                                                @if ($societe->site_web)
                                                    <div class="mb-1">
                                                        <span
                                                            class="font-semibold text-gray-700 dark:text-gray-300 text-sm">{!! __('Site web : ') !!}</span>
                                                        <a href="https://{{ $societe->site_web }}" target="_blank"
                                                            class="text-blue-500 hover:underline dark:text-blue-400 text-sm">
                                                            {{ parse_url($societe->site_web, PHP_URL_HOST) ?: $societe->site_web }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label for="commentaire-societe-{{ $societe->id }}"
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300">{!! __('Commentaire') !!}</label>
                                            <textarea rows="2" id="commentaire-societe-{{ $societe->id }}" name="commentaire"
                                                class="mt-1 block w-full px-2 py-1 border border-gray-300 dark:border-gray-700 rounded-md shadow-xs focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs dark:bg-gray-900 dark:text-gray-100"
                                                data-societe-id="{{ $societe->id }}" onblur="updateCommentaireSociete(this)">{{ $societe->commentaire ? $societe->commentaire->contenu : '' }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Onglet Établissements -->
                                    <div id="societe-etablissements-{{ $societe->id }}"
                                        class="societe-tab-content hidden">
                                        @if ($societe->etablissements->isEmpty())
                                            <p class="text-center py-3 text-gray-500 dark:text-gray-400 text-sm">Aucun
                                                établissement</p>
                                        @else
                                            <div class="space-y-2">
                                                @foreach ($societe->etablissements as $etablissement)
                                                    <div
                                                        class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                                        <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-850 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                                                            onclick="toggleEtablissement('{{ $etablissement->id }}'); rotateArrow('etab-arrow-{{ $etablissement->id }}')">
                                                            <div class="flex items-center">
                                                                <x-icon :size="0.8" type="arrow_back"
                                                                    id="etab-arrow-{{ $etablissement->id }}"
                                                                    class="transform {{ $societe->etablissements->count() === 1 ? '-rotate-180' : '-rotate-90' }} mr-2 transition-transform text-gray-600 dark:text-gray-400" />
                                                                <span class="font-medium text-gray-800 dark:text-gray-200 text-sm">
                                                                    {{ $etablissement->nom }}
                                                                    @if ($etablissement->societeContacts->count() == 0)
                                                                    <br/>
                                                                        <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-400 dark:border-red-800 rounded inline-flex items-center">
                                                                            <x-icon :size="1" type="error_icon" class="icons-no_hover fill-red-500 dark:fill-red-400 mr-2" />
                                                                            <p class="text-sm text-red-500 dark:text-red-400">
                                                                                Aucun contact n'est actuellement associé à cet établissement.
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <a href="/societe/{{ $societe->id }}/etablissement/{{ $etablissement->id }}" class="btn"
                                                                onclick="event.stopPropagation()">
                                                                <x-icon size="1" type="open_in_new" class="icons" />
                                                            </a>
                                                        </div>

                                                        <div id="etablissement-content-{{ $etablissement->id }}"
                                                            class="{{ $societe->etablissements->count() === 1 ? '' : 'hidden' }} p-3 bg-gray-100 dark:bg-gray-900">
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                                <div class="space-y-1">
                                                                    <x-copiable_text titre="Adresse : "
                                                                        text="{{ $etablissement->adresse }}" />
                                                                    @if ($etablissement->complement_adresse)
                                                                        <x-copiable_text
                                                                            titre="Complément d'adresse : "
                                                                            text="{{ $etablissement->complement_adresse }}" />
                                                                    @endif
                                                                    <x-copiable_text titre="Code postal : "
                                                                        text="{{ $etablissement->code_postal }}" />
                                                                    <x-copiable_text titre="Ville : "
                                                                        text="{{ $etablissement->ville }}" />
                                                                    <x-copiable_text titre="Région : "
                                                                        text="{{ $etablissement->region }}" />
                                                                    <x-copiable_text titre="Pays : "
                                                                        text="{{ $etablissement->pays->nom }}" />
                                                                    <x-copiable_text titre="Siret : "
                                                                        text="{{ $etablissement->siret }}" />
                                                                </div>
                                                                <div>
                                                                    <!-- Liste des contacts -->
                                                                    <div class="mb-3">
                                                                        <div
                                                                            class="flex items-center justify-between mb-2">
                                                                            <h4
                                                                                class="font-medium text-gray-800 dark:text-gray-200 text-sm">
                                                                                Contacts</h4>
                                                                            <button type="button"
                                                                                class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-md px-2 py-1 text-xs flex items-center transition-colors"
                                                                                x-data=""
                                                                                x-on:click.prevent="$dispatch('open-modal', 'contacts-modal-{{ $etablissement->id }}')">
                                                                                <x-icon :size="1"
                                                                                    type="contact"
                                                                                    class="icons-no_hover" />
                                                                            </button>
                                                                        </div>

                                                                        @php
                                                                            $contacts = $etablissement
                                                                                ->societeContacts()
                                                                                ->get();
                                                                        @endphp
                                                                        <x-modals.contacts
                                                                            name="contacts-modal-{{ $etablissement->id }}"
                                                                            :contacts="$contacts" />
                                                                    </div>

                                                                    <!-- Commentaire établissement -->
                                                                    <div>
                                                                        <label
                                                                            for="commentaire-etab-{{ $etablissement->id }}"
                                                                            class="block text-xs font-medium text-gray-700 dark:text-gray-300">{!! __('Commentaire') !!}</label>
                                                                        <textarea rows="2" id="commentaire-etab-{{ $etablissement->id }}" name="commentaire"
                                                                            class="mt-1 block w-full px-2 py-1 border border-gray-300 dark:border-gray-700 rounded-md shadow-xs focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs dark:bg-gray-800 dark:text-gray-100"
                                                                            data-etablissement-id="{{ $etablissement->id }}" onblur="updateCommentaireEtablissement(this)">{{ $etablissement->commentaire ? $etablissement->commentaire->contenu : '' }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Bouton Ajouter un établissement -->
                                        <div class="mt-3 text-center">
                                            <a href="{{ route('etablissements.create', $societe->id) }}"
                                                class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-3 py-1 rounded-md inline-flex items-center transition-colors text-sm">
                                                <span class="text-lg mr-1">+</span> {!! __('Ajouter un établissement') !!}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex justify-center items-center pb-3">
                    {{ $societes->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleSociete(id) {
            const content = document.getElementById('societe-content-' + id);
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                content.classList.add('animate-fadeIn');
            } else {
                content.classList.add('hidden');
            }
        }

        function toggleEtablissement(id) {
            const content = document.getElementById('etablissement-content-' + id);
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                content.classList.add('animate-fadeIn');
            } else {
                content.classList.add('hidden');
            }
        }

        function rotateArrow(id) {
            const arrow = document.getElementById(id);
            if (arrow.classList.contains('-rotate-90')) {
                arrow.classList.remove('-rotate-90');
                arrow.classList.add('-rotate-180');
            } else {
                arrow.classList.add('-rotate-90');
                arrow.classList.remove('-rotate-180');
            }
        }

        function showSocieteTab(societeId, tabName) {
            // Masquer tous les contenus d'onglets pour cette société
            document.getElementById(`societe-infos-${societeId}`).classList.add('hidden');
            document.getElementById(`societe-etablissements-${societeId}`).classList.add('hidden');

            // Désactiver tous les onglets
            const infosTab = document.getElementById(`infos-tab-${societeId}`);
            const etablissementsTab = document.getElementById(`etablissements-tab-${societeId}`);

            // Reset all tabs to inactive state
            infosTab.classList.remove('border-blue-500', 'dark:border-blue-400', 'text-blue-600', 'dark:text-blue-400');
            infosTab.classList.add('border-transparent', 'hover:border-gray-300', 'dark:hover:border-gray-600',
                'text-gray-500', 'dark:text-gray-400', 'hover:text-gray-700', 'dark:hover:text-gray-300');

            etablissementsTab.classList.remove('border-blue-500', 'dark:border-blue-400', 'text-blue-600',
                'dark:text-blue-400');
            etablissementsTab.classList.add('border-transparent', 'hover:border-gray-300', 'dark:hover:border-gray-600',
                'text-gray-500', 'dark:text-gray-400', 'hover:text-gray-700', 'dark:hover:text-gray-300');

            // Afficher le contenu de l'onglet sélectionné
            document.getElementById(`societe-${tabName}-${societeId}`).classList.remove('hidden');

            // Activer l'onglet sélectionné
            const activeTab = document.getElementById(`${tabName}-tab-${societeId}`);
            activeTab.classList.remove('border-transparent', 'hover:border-gray-300', 'dark:hover:border-gray-600',
                'text-gray-500', 'dark:text-gray-400', 'hover:text-gray-700', 'dark:hover:text-gray-300');
            activeTab.classList.add('border-blue-500', 'dark:border-blue-400', 'text-blue-600', 'dark:text-blue-400');

            // Si on affiche l'onglet établissements et qu'il n'y a qu'un seul établissement, s'assurer qu'il reste ouvert
            if (tabName === 'etablissements') {
                // Vérifier tous les établissements de cette société pour voir s'il n'y en a qu'un
                const etablissementContainers = document.querySelectorAll(
                    `[id^="etablissement-content-"][id*="${societeId}"]`);
                if (etablissementContainers.length === 1) {
                    const etablissementId = etablissementContainers[0].id.replace('etablissement-content-', '');
                    const content = document.getElementById(`etablissement-content-${etablissementId}`);
                    const arrow = document.getElementById(`etab-arrow-${etablissementId}`);

                    if (content && content.classList.contains('hidden')) {
                        content.classList.remove('hidden');
                        content.classList.add('animate-fadeIn');
                    }

                    if (arrow && arrow.classList.contains('-rotate-90')) {
                        arrow.classList.remove('-rotate-90');
                        arrow.classList.add('-rotate-180');
                    }
                }
            }
        }

        function updateCommentaireSociete(element) {
            const societeId = element.dataset.societeId;
            const commentaireTexte = element.value;

            fetch('/societe/' + societeId + '/commentaire/save', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        commentaire: commentaireTexte,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (!(data.message == 'Commentaire inchangé')) {
                        showFlashMessageFromJs(data.message, 2000);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la mise à jour du commentaire', error);
                });
        }

        function updateCommentaireEtablissement(element) {
            const etablissementId = element.dataset.etablissementId;
            const commentaireTexte = element.value;

            fetch('/societe/etablissement/' + etablissementId + '/commentaire/save', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        commentaire: commentaireTexte,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (!(data.message == 'Commentaire inchangé')) {
                        showFlashMessageFromJs(data.message, 2000);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la mise à jour du commentaire', error);
                });
        }
    </script>

    <style>
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Dark mode improvements */
        .dark .bg-gray-850 {
            background-color: rgb(31, 41, 55);
        }

        .dark .bg-gray-750 {
            background-color: rgb(55, 65, 81);
        }
    </style>
    <script>
        let timeout = null;
        function debounceSubmit(form) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                form.submit();
            }, 500);
        }
    </script>
</x-app-layout>
