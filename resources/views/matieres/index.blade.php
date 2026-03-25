<x-app-layout>
    @section('title', 'Matières')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {!! __('Matières') !!}
            </h2>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center gap-2">
                <a href="{{ route('matieres.devis_verification') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    Vérifier Devis
                </a>

                <form method="GET" action="{!! route('matieres.index') !!}"
                    class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center">
                    <select name="societe" id="societe" class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 w-fit">
                        <option value="" selected>{!! __('Tous les fournisseurs') !!}</option>
                        @foreach ($societes as $societe)
                            <option value="{{ $societe->id }}"
                                {{ request('societe') == $societe->id ? 'selected' : '' }}>
                                {!! $societe->raison_sociale . '&nbsp;&nbsp;' !!}
                            </option>
                        @endforeach
                    </select>
                    <select name="famille" id="famille_id_search" class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 w-fit">
                        <option value="" selected>{!! __('Tous les types&nbsp;&nbsp;') !!}</option>
                        @foreach ($familles as $famille)
                            <option value="{{ $famille->id }}"
                                {{ request('famille') == $famille->id ? 'selected' : '' }}>
                                {!! $famille->nom . '&nbsp;&nbsp;' !!}
                            </option>
                        @endforeach
                    </select>
                    <select name="sous_famille" id="sous_famille_id_search"
                        class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 w-fit">
                        <option value="" selected>{!! __('Toutes les sous-familles &nbsp;&nbsp;') !!}</option>
                    </select>
                    <x-tooltip position="bottom">
                        <x-slot name="slot_item">
                            <input type="text" name="search" placeholder="Rechercher..."
                                value="{!! request('search') !!}"
                                oninput="debounceSubmit(this.form)"
                                class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                        </x-slot>
                        <x-slot name="slot_tooltip">
                            <ul class="whitespace-nowrap">
                                <li>Recherchez par mots-clés</li>
                                <li>Pour une <strong>référence fournisseur</strong>, remplacez les espaces par un
                                    <strong>"_"</strong></li>
                                <li>Pour un <strong>DN</strong>, tapez "<strong>dn25</strong>"</li>
                                <li>Pour une <strong>épaisseur</strong>, tapez "<strong>ep10</strong>"</li>
                            </ul>
                        </x-slot>
                    </x-tooltip>
                    <div class="flex items-center ml-4 my-1 ">
                        <label for="nombre"
                            class="mr-2 text-gray-900 dark:text-gray-100">{!! __('Quantité') !!}</label>
                        <input type="number" name="nombre" id="nombre" value="{!! old('nombre', request('nombre', 50)) !!}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 w-20 mr-2 ">
                    </div>
                    <button type="submit" class="mr-2 btn w-full sm:w-auto sm:mt-0 md:mt-0 lg:mt-0">
                        {!! __('Rechercher') !!}
                    </button>
                </form>
                <x-quick-matiere class="mb-1" />
                <!-- Nouveau bouton d'import Excel -->

            </div>
        </div>

    </x-slot>
    <div class="w-full">
        <div class=" flex transition-all duration-500 max-h-0 overflow-hidden border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800"
            id="slide-down">
            <div class="flex p-4 space-x-2">
                <a href="{!! route('standards.index') !!}" class="btn">
                    {!! __('Standards') !!}
                </a>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-famille-modal')" class="btn">
                    {!! __('Nouvelle famille') !!}
                </button>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-sous-famille-modal')" class="btn">
                    {!! __('Nouvelle sous-famille') !!}
                </button>
                <a href="{{ route('matieres.import.form') }}" class="btn ml-2 flex items-center">
                    <x-icon type="upload" class="mr-1" size="1" />
                    Importer CSV
                </a>

            </div>

        </div>
        <button class="w-full bg-white dark:bg-gray-800 justify-center flex hover:bg-gray-100 dark:hover:bg-gray-700"
            onclick="toggleSlide()">
            <x-icon :size="2" type="arrow_back"
                class="-rotate-90 icons-no_hover -mt-2 mb-1 transition-all duration-500" id="arrow-slide-down" />
        </button>

        <script>
            function toggleSlide() {
                const slideDown = document.getElementById('slide-down');
                const arrow = document.getElementById('arrow-slide-down');
                if (slideDown.classList.contains('max-h-0')) {
                    slideDown.classList.remove('max-h-0');
                    slideDown.classList.add('max-h-40'); // Adjust max height as needed
                    arrow.classList.remove('-rotate-90');
                    arrow.classList.add('rotate-90');
                    arrow.classList.remove('-mt-2');
                    arrow.classList.add('-mb-2');
                } else {
                    slideDown.classList.remove('max-h-40');
                    slideDown.classList.add('max-h-0');
                    arrow.classList.remove('rotate-90');
                    arrow.classList.add('-rotate-90');
                    arrow.classList.remove('-mb-2');
                    arrow.classList.add('-mt-2');
                }
            }
        </script>
    </div>
    <div class="py-8 ">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 ">
            <div class="bg-white dark:bg-gray-800 sm:rounded-lg shadow-md">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead
                                class="bg-linear-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800 text-gray-700 dark:text-gray-100">
                                <tr c>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Référence</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Sous-famille</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Matière</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Désignation</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Qté stock</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Standard</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">DN</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">EP</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-100" id="body_table">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4 flex justify-center items-center pb-3 pagination">
                    <div>

                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- Modal pour créer une famille -->
    <x-modal name="create-famille-modal" focusable maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                {{ __('Créer une nouvelle famille') }}
            </h2>

            <form id="create-famille-form" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="famille_nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Nom de la famille') }}
                    </label>
                    <x-text-input
                        type="text"
                        name="nom"
                        id="famille_nom"
                        class="mt-1 block w-full"
                        placeholder="Nom de la famille"
                        required
                    />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="button" x-on:click="$dispatch('close')" class="btn-secondary mr-3">
                        {{ __('Annuler') }}
                    </button>
                    <button type="submit" class="btn">
                        {{ __('Créer') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal pour créer une sous-famille -->
    <x-modal name="create-sous-famille-modal" focusable maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                {{ __('Créer une nouvelle sous-famille') }}
            </h2>

            <form id="create-sous-famille-form" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="sous_famille_famille_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Famille') }}
                    </label>
                    <select name="famille_id" id="sous_famille_famille_id" class="mt-1 block w-full px-4 py-2 border select" required>
                        <option value="">{{ __('Sélectionner une famille') }}</option>
                        @foreach ($familles as $famille)
                            <option value="{{ $famille->id }}">{{ $famille->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="sous_famille_nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Nom de la sous-famille') }}
                    </label>
                    <x-text-input
                        type="text"
                        name="nom"
                        id="sous_famille_nom"
                        class="mt-1 block w-full"
                        placeholder="Nom de la sous-famille"
                        required
                    />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="button" x-on:click="$dispatch('close')" class="btn-secondary mr-3">
                        {{ __('Annuler') }}
                    </button>
                    <button type="submit" class="btn">
                        {{ __('Créer') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
    <script>
        function updateSousFamilles() {
            var familleId = document.getElementById('famille_id_search').value;
            var sousFamilleSelect = document.getElementById('sous_famille_id_search');

            // Efface les anciennes options
            sousFamilleSelect.innerHTML =
                '<option value="" selected>Toutes les sous-familles &nbsp;&nbsp;</option>';

            if (familleId) {
                fetch(`/matieres/famille/${familleId}/sous-familles/json`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(sousFamille => {
                            var option = document.createElement('option');
                            option.value = sousFamille.id;
                            option.textContent = sousFamille.nom + ' ';
                            option.style.display = 'flex';
                            option.style.justifyContent = 'space-between';
                            option.textContent = `${sousFamille.nom} (${sousFamille.matiere_count})`;
                            sousFamilleSelect.appendChild(option);
                            var sousFamilleId = new URLSearchParams(window.location.search).get('sous_famille');

                            if (sousFamilleId) {
                                document.getElementById('sous_famille_id_search').value = sousFamilleId;
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des sous familles :', error);
                    });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Mise à jour des sous-familles au chargement
            updateSousFamilles();

            // Attache les événements pour la recherche dynamique
            document.querySelector('input[name="search"]').addEventListener('input', debounce(liveSearch, 300));
            document.getElementById('famille_id_search').addEventListener('change', function() {
                updateSousFamilles();
                liveSearch();
            });
            document.getElementById('sous_famille_id_search').addEventListener('change', liveSearch);
            document.getElementById('societe').addEventListener('change', liveSearch);
            document.getElementById('nombre').addEventListener('change', liveSearch);

            // Gestion de la pagination
            // Lancer la première recherche au chargement
            liveSearch();
        });

        let debounceTimeout = null;
        let currentController = null;

        function liveSearch() {
            clearTimeout(debounceTimeout);

            debounceTimeout = setTimeout(() => {
                const searchQuery = document.querySelector('input[name="search"]').value.trim();
                const familleId = document.getElementById('famille_id_search').value;
                const sousFamilleId = document.getElementById('sous_famille_id_search').value;
                const societeId = document.getElementById('societe').value;
                const nombre = document.getElementById('nombre').value;
                const containerSearch = document.getElementById('body_table');
                const page = new URLSearchParams(window.location.search).get('page') || 1;

                // Annule la requête précédente si elle existe
                if (currentController) {
                    currentController.abort();
                }

                // Nouvelle requête avec AbortController
                currentController = new AbortController();
                const {
                    signal
                } = currentController;

                containerSearch.innerHTML =
                    '<tr><td colspan="100"><div id="loading-spinner" class="mt-8 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-32 w-full"><div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div></div><style>.loader {border-top-color: #3498db; animation: spinner 1.5s linear infinite;}@keyframes spinner {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}</style></td></tr>';

                const url =
                    `/matieres/search?search=${encodeURIComponent(searchQuery)}&societe_filter=${societeId}&famille=${familleId}&sous_famille=${sousFamilleId}&nombre=${nombre}&page=${page}`;

                fetch(url, {
                        signal
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Erreur lors de la récupération des données');
                        return response.json();
                    })
                    .then(data => {
                        if (page > data.lastPage || page < 1) {
                            window.location.href =
                                `/matieres?search=${encodeURIComponent(searchQuery)}&famille=${familleId}&sous_famille=${sousFamilleId}&nombre=${nombre}&page=${data.lastPage}`;
                        }
                        updateTable(data.matieres);
                        updatePagination(data.links);
                    })
                    .catch(error => {
                        if (error.name === 'AbortError') {
                        } else {
                            console.error('Erreur lors de la recherche :', error);
                        }
                    });

            }, 300); // 300ms = délai de debounce
        }


        function updateTable(matieres) {
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = ''; // Réinitialise le tableau
            if (matieres.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = '<td class="text-center py-3 px-4" colspan="100">Aucun résultat</td>';
                tbody.appendChild(row);
                return;
            }
            matieres.forEach(matiere => {
                const row = document.createElement('tr');
                row.onclick = function() {
                    window.location.href = `/matieres/${matiere.id}`;
                };
                row.classList.add('cursor-pointer', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
                row.innerHTML = `
            <td class="text-left py-3 px-4">${matiere.refTooltip || '-'}</td>
            <td class="text-left py-3 px-4">${matiere.sousFamille || '-'}</td>
            <td class="text-left py-3 px-4">${matiere.material || '-'}</td>
            <td class="text-left py-3 px-4">${matiere.designation || '-'}</td>
            <td class="text-left py-3 px-4">${matiere.tooltip}</td>
            <td class="text-left py-3 px-4 flex items-center whitespace-nowrap">
                ${matiere.standard ? `<x-icons.pdf class="w-6 h-6" /><a href="/matieres/${matiere.standardPath}" class="lien" target="_blank">${matiere.standard} ${matiere.standardVersion || '-'}</a>` : 'Aucun standard'}
            </td>
            <td class="text-left py-3 px-4">${matiere.dn || '-'}</td>
            <td class="text-left py-3 px-4">${matiere.epaisseur || '-'}</td>
        `;
                tbody.appendChild(row);
            });
        }

        function updatePagination(data) {
            const pagination = document.querySelector('.pagination');
            pagination.innerHTML = data; // Réinitialise la pagination
        }


        function debounce(func, delay) {
            let timer;
            return function(...args) {
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, args), delay);
            };
        }


        // Gestion du formulaire de création de famille
        document.getElementById('create-famille-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');

            submitButton.disabled = true;
            submitButton.textContent = 'Création...';

            fetch('/matieres/familles', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showFlashMessageFromJs('Famille créée avec succès', 2000, 'success');

                    // Ajouter la nouvelle famille au select
                    const familleSelect = document.getElementById('famille_id_search');
                    const sousFamilleSelect = document.getElementById('sous_famille_famille_id');

                    const option1 = new Option(data.famille.nom, data.famille.id);
                    const option2 = new Option(data.famille.nom, data.famille.id);

                    familleSelect.add(option1);
                    sousFamilleSelect.add(option2);

                    // Fermer le modal et réinitialiser le formulaire
                    this.reset();
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'create-famille-modal' }));
                } else {
                    showFlashMessageFromJs(data.message || 'Erreur lors de la création', 2000, 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showFlashMessageFromJs('Erreur lors de la création de la famille', 2000, 'error');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Créer';
            });
        });

        // Gestion du formulaire de création de sous-famille
        document.getElementById('create-sous-famille-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');

            submitButton.disabled = true;
            submitButton.textContent = 'Création...';

            fetch('/matieres/sous-familles', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showFlashMessageFromJs('Sous-famille créée avec succès', 2000, 'success');

                    // Mettre à jour les sous-familles si la famille sélectionnée correspond
                    const familleSelectValue = document.getElementById('famille_id_search').value;
                    if (familleSelectValue == data.sousFamille.famille_id) {
                        updateSousFamilles();
                    }

                    // Fermer le modal et réinitialiser le formulaire
                    this.reset();
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'create-sous-famille-modal' }));
                } else {
                    showFlashMessageFromJs(data.message || 'Erreur lors de la création', 2000, 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showFlashMessageFromJs('Erreur lors de la création de la sous-famille', 2000, 'error');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Créer';
            });
        });

        let timeout = null;
        function debounceSubmit(form) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                form.submit();
            }, 500);
        }
    </script>


</x-app-layout>
