<x-app-layout>
    @section('title', 'Créer ' . $ddp->code)
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Demandes de prix</a>
                >>
                {!! __('Créer une demande de prix') !!}
            </h2>
            <a href="{{ route('ddp.annuler', $ddp->id) }}" class="btn">Annuler la ddp</a>
        </div>
    </x-slot>
    <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Pour Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
    <div id="new-ddp" class="hidden">{{ $ddpid ? $ddpid : '' }}</div>
    <div class="py-4">
        <div class="max-w-8xl mx-auto sm:px-4 lg:px-6">
            <div
                class="shadow-xs sm:rounded-lg text-gray-900 dark:text-gray-100 px-2 grid grid-cols-1 sm:grid-cols-2  gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 flex flex-col gap-4 rounded-md">
                    <h1 class="text-xl font-semibold mb-2">Sélection des matières</h1>
                    <div class="flex flex-wrap gap-2">
                        <div class="flex items-center gap-2 justify-between flex-wrap w-full">
                            <div class="flex items-center gap-2 flex-wrap">
                                <!-- Famille selection dropdown -->
                                <select name="famille" id="famille_id_search"
                                    class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 w-fit">
                                    <option value="" selected>{!! __('Tous les types&nbsp;&nbsp;') !!}</option>
                                    @foreach ($familles as $famille)
                                        <option value="{{ $famille->id }}"
                                            {{ request('famille') == $famille->id ? 'selected' : '' }}>
                                            {!! $famille->nom . '&nbsp;&nbsp;' !!}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Sous-famille selection dropdown -->
                                <select name="sous_famille" id="sous_famille_id_search"
                                    class="px-4 py-2 mr-2 border select mb-2 sm:mb-0 w-fit">
                                    <option value="" selected>{!! __('Toutes les sous-familles &nbsp;&nbsp;') !!}</option>
                                </select>
                            </div>
                            <x-quick-matiere />
                        </div>

                        <!-- Search bar for materials -->
                        <div class="flex w-full">
                            <x-tooltip position="bottom" class="w-full">
                                <x-slot name="slot_item">
                                    <x-text-input placeholder="Recherchez une matière" id="searchbar" class="w-full" />
                                </x-slot>
                                <x-slot name="slot_tooltip">
                                    <ul class="whitespace-nowrap">
                                        <li>Recherchez par mots-clés</li>
                                        <li>Pour une <strong>référence fournisseur</strong>, remplacez les espaces par
                                            un <strong>"_"</strong></li>
                                        <li>Pour un <strong>DN</strong>, tapez "<strong>dn25</strong>"</li>
                                        <li>Pour une <strong>épaisseur</strong>, tapez "<strong>ep10</strong>"</li>
                                    </ul>
                                </x-slot>
                            </x-tooltip>
                            <button class="btn-select-right -ml-1 border-gray-300 dark:border-gray-700" type="button"
                                onclick="liveSearch()">Rechercher</button>
                        </div>
                    </div>
                    <div class="min-h-96 overflow-x-auto bg-gray-100 dark:bg-gray-900 rounded-sm">
                        <table>
                            <thead>
                                <th class="text-sm">Référence</th>
                                <th class="text-sm">Désignation</th>
                                <th class="text-sm">DN</th>
                                <th class="text-sm">EP</th>
                                <th class="text-sm">Stock</th>
                                <th class="text-sm">Sous-famille</th>
                            </thead>
                            <tbody id="matiere-table">
                                <tr>
                                    <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center pt-4">
                                        Recherchez une matière
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="bg-white dark:bg-gray-800 p-4 flex flex-col gap-4 rounded-md">
                    <form class="bg-white dark:bg-gray-800 flex flex-col gap-4 rounded-md">
                        @csrf
                        <input type="hidden" name="ddp_id" value="{{ $ddp->id ?? '' }}">
                        <div class="flex justify-between items-center mb-6">
                            <h1 class="text-xl font-semibold">Demande de prix</h1>
                            <div class="flex items-center">
                                <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 flex items-center hidden"
                                    title="Demande de prix en cours d'enregistrement" id="save-status-0">Enregistrement
                                    en
                                    cours...<x-icons.progress-activity size="2" /></h1>
                                <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 {{ isset($ddp) ? '' : 'hidden' }}"
                                    title="Demande de prix enregistré avec succès" id="save-status-1">Enregistré</h1>
                                <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 {{ isset($ddp) ? 'hidden' : '' }}"
                                    title="Demande de prix non enregistrée" id="save-status-2">Non-enregistré</h1>
                                <button class="" onclick="saveChanges()" type="button">
                                    <x-icons.refresh size="2" class="icons" />
                                </button>
                            </div>
                        </div>
                        {{--
 ######   #######  ##              #####
##    ## ##     ## ##            ##     ##
##       ##     ## ##                   ##
##       ##     ## ##                 ##
##       ##     ## ##              ##
##    ## ##     ## ##            ##
 ######   #######  ########      #########  --}}

                        <div class="w-full flex gap-4">
                            <div class="w-auto">
                                <x-input-label for="ddp-entite" value="Pour" />
                                <select name="ddp-entite" id="ddp-entite"
                                    class="select w-auto {{ isset($ddp) && $ddp->nom != 'undefined' ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : '' }} p-3">
                                    @foreach ($entites as $entite)
                                        <option value="{{ $entite->id }}"
                                            {{ isset($ddp) && $ddp->entite_id == $entite->id ? 'selected' : '' }}>
                                            {{ $entite->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="">
                                <x-input-label for="ddp-nom" value="Nom" />
                                <x-text-input label="Nom" name="ddp-nom" id="ddp-nom"
                                    placeholder="Nom de la demande de prix" autofocus
                                    value="{{ isset($ddp) && $ddp->nom != 'undefined' ? $ddp->nom : '' }}"
                                    class=" {{ isset($ddp) && $ddp->nom != 'undefined' ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : '' }}" />
                            </div>
                            <div>
                                <x-input-label for="ddp-code" value="Code" />
                                <div
                                    class="flex items-center bg-gray-100 dark:bg-gray-900 rounded-sm focus-within:ring-2 focus-within:ring-blue-500 dark:focus-within:ring-blue-600">
                                    <span class="ml-2 font-bold"> DDP-{{ date('y') }}-</span>
                                    <x-text-input label="Code" name="ddp-code" id="ddp-code" placeholder="0000"
                                        autofocus maxlength="4"
                                        value="{{ isset($ddp) && $ddp->code != 'undefined' ? substr($ddp->code, 7, 4) : '' }}"
                                        class="border-0 focus:border-0 dark:border-0 focus:ring-0 dark:focus:ring-0 w-14 px-0 mx-0 {{ isset($ddp) && $ddp->nom != 'undefined' ? 'border-r-green-500 dark:border-r-green-600 border-r-4' : '' }}" />
                                    <span class="-ml-2 mr-2"
                                        id="ddp-code-entite">{{ isset($entite_code) ? $entite_code : '' }}</span>
                                </div>
                            </div>

                        </div>
                        <div class="min-h-96 overflow-x-auto bg-gray-100 dark:bg-gray-900 rounded-sm">
                            <table>
                                <thead>
                                    <th colspan="100" class="border-r-4 border-gray-50 dark:border-gray-800">Matières
                                        sélectionnées</th>

                                </thead>
                                <tbody id="matiere-choisi-table">
                                    @if ($ddp && $ddp->ddpLigne->count() > 0)
                                        @foreach ($ddp->ddpLigne as $ddp_ligne)
                                            @if ($ddp_ligne->ligne_autre_id == null)
                                                <tr data-matiere-id="{{ $ddp_ligne->matiere->id }}" x-data
                                                    data-fournisseurs-ids="{{ $ddp_ligne->fournisseurs->pluck('id')->join(';') }}"
                                                    data-fournisseurs-noms="{{ $ddp_ligne->fournisseurs->pluck('raison_sociale')->join(';') }}"
                                                    class="border-b border-gray-200 dark:border-gray-700 rounded-r-md overflow-hidden bg-white dark:bg-gray-800 border-r-4 border-r-green-500 dark:border-r-green-600">
                                                    <td class="text-left px-4">{{ $ddp_ligne->matiere->ref_interne }}
                                                    </td>
                                                    <td class="text-left px-4">{{ $ddp_ligne->matiere->designation }}
                                                    </td>
                                                    <td class="text-right px-4"
                                                        title="{{ $ddp_ligne->matiere->unite->full }}">
                                                        <div
                                                            class="flex items-center m-1 focus-within:ring-2 focus-within:ring-blue-500 dark:focus-within:ring-blue-600 focus-within:focus:border-indigo-600 rounded-sm">

                                                            <x-text-input type="number"
                                                                name="quantite[{{ $ddp_ligne->matiere->id }}]"
                                                                oninput="saveChanges()"
                                                                class="w-20 border-r-0 rounded-r-none focus:ring-0 focus:border-0 dark:focus:ring-0 border-gray-300 dark:border-gray-700"
                                                                value="{{ formatNumber($ddp_ligne->quantite) }}"
                                                                min="0" />
                                                            {{-- <select name="unite[{{ $ddp_ligne->matiere_id }}]"
                                                            class="w-16 mx-2 select" onchange="saveChanges()">
                                                            @foreach ($unites as $unite)
                                                                <option value="{{ $unite->id }}"
                                                                    title="{{ $unite->full }}"
                                                                    {{ $unite->id === $ddp_ligne->unite_id ? 'selected' : '' }}>
                                                                    {{ $unite->short }}
                                                                </option>
                                                            @endforeach
                                                        </select> --}}
                                                            <div
                                                                class="text-right bg-gray-100 dark:bg-gray-900 w-fit p-2 pl-0 border-1 border-l-0 rounded-r-sm border-gray-300 dark:border-gray-700">
                                                                {{ $ddp_ligne->matiere->unite->short }}</div>
                                                        </div>
                                                    </td>

                                                    <td class="text-right px-4 ">
                                                        <div class="flex">
                                                            <button class="float-right"
                                                                data-matiere-id="{{ $ddp_ligne->matiere->id }}"
                                                                onclick="showFournisseurs(event)"
                                                                x-on:click.prevent="$dispatch('open-modal', 'fournisseurs-modal')"
                                                                title="Fournisseurs">
                                                                <x-icons.list size="2" class="icons" />
                                                            </button>
                                                            <button class="float-right"
                                                                data-matiere-id="{{ $ddp_ligne->matiere->id }}"
                                                                onclick="removeMatiere(event)">
                                                                <x-icons.close size="2" class="icons"
                                                                    tabindex="-1" />
                                                            </button>

                                                        </div>
                                                        <input type="hidden"
                                                            name="fournisseur-{{ $ddp_ligne->matiere->id }}"
                                                            value="{{ $ddp_ligne->fournisseurs->pluck('id')->join(';') }}">
                                                    </td>
                                                </tr>
                                            @else
                                                <tr data-matiere-id="{{ $ddp_ligne->ligne_autre_id }}"
                                                    class="border-b border-gray-200 dark:border-gray-700 rounded-r-md overflow-hidden bg-white dark:bg-gray-800 border-r-4 border-r-green-500 dark:border-r-green-600<<<<<">
                                                    <td class="text-left px-1">
                                                        <x-text-input type="text"
                                                            name="case_ref[{{ $ddp_ligne->ligne_autre_id }}]"
                                                            value="{{ $ddp_ligne->case_ref }}" class="w-full"
                                                            placeholder="AA-0052" oninput="saveChanges()" />
                                                    </td>
                                                    <td class="text-left px-1">
                                                        <x-text-input type="text"
                                                            name="case_designation[{{ $ddp_ligne->ligne_autre_id }}]"
                                                            value="{{ $ddp_ligne->case_designation }}" class="w-full"
                                                            placeholder="Désignation" oninput="saveChanges()" />
                                                    </td>
                                                    <td class="text-left px-5">
                                                        <x-text-input type="text"
                                                            name="case_quantite[{{ $ddp_ligne->ligne_autre_id }}]"
                                                            oninput="saveChanges()"
                                                            class="w-24 border-gray-300 dark:border-gray-700"
                                                            value="{{ formatNumber($ddp_ligne->case_quantite) }}"
                                                            min="0" />
                                                    </td>
                                                    <td>
                                                        <div class="flex justify-end mx-4">
                                                            <button class="float-right"
                                                                data-matiere-id="{{ $ddp_ligne->ligne_autre_id }}"
                                                                onclick="removeMatiere(event)">
                                                                <x-icons.close size="2" class="icons"
                                                                    tabindex="-1" />
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                         <tr id="no-matiere">
                                            <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center pt-4">
                                                Aucune matière sélectionnée
                                            </td>
                                        </tr>


                                    @endif

                                </tbody>
                            </table>
                            <div class="w-full flex justify-end gap-2 text-center">
                                <x-tooltip position="left">
                                    <x-slot:slot_item>
                                        <button type="button"
                                            class="btn w-fit rounded-none rounded-bl-xl bg-white dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 hover:shadow-lg transition-all duration-300 py-0 px-4 mt-0"
                                            onclick="addLigneVide()">
                                            <span class="text-center w-full text-4xl">
                                                +
                                            </span>
                                        </button>
                                    </x-slot:slot_item>
                                    <x-slot:slot_tooltip>
                                        <span class="block text-sm text-gray-700 dark:text-gray-300">
                                            Cliquez ici pour ajouter une ligne personnalisée.<br>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                Permet de saisir une matière manuellement (hors stock).
                                            </span>
                                        </span>
                                    </x-slot:slot_tooltip>
                                </x-tooltip>
                            </div>
                        </div>
                    </form>
                    <div class="flex justify-between gap-4">
                        <button class="bg-red-500 hover:bg-red-600 btn text-white"
                            onclick="event.preventDefault(); document.getElementById('confirm-delete-modal').classList.remove('hidden');">Supprimer</button>
                        <div id="confirm-delete-modal"
                            class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                                <h2 class="text-xl font-semibold mb-4">Voulez-vous vraiment supprimer ?</h2>
                                <p class="mb-4">Cette action est irréversible.</p>
                                <div class="flex justify-end gap-4">
                                    <button class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-sm"
                                        onclick="document.getElementById('confirm-delete-modal').classList.add('hidden');">Annuler</button>
                                    <form action="{{ route('ddp.destroy', ['ddp' => $ddpid]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-sm">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <button class=" btn"
                            onclick="if (document.getElementById('ddp-nom').value.trim() != '') { window.location.href = '{{ route('ddp.validation', ['ddp' => $ddpid]) }}'; } else { alert('Veuillez renseigner le nom de la demande de prix'); }">Suivant</button>
                    </div>
                </div>
            </div>
        </div>
    </div>







    <x-modal name="fournisseurs-modal" title="Fournisseurs" max-width="5xl">
        <div class="flex flex-col gap-4 p-4 text-gray-900 dark:text-gray-100">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-semibold">Fournisseurs</h1>
                <a x-on:click="$dispatch('close')">
                    <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                </a>
            </div>
            <table class="rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
                <thead>
                    <th class="text-sm">Nom</th>
                    <th>
                        <button class="float-right" onclick="showFournisseurs(event,1)">
                            <x-icons.refresh size="2" class="icons" />
                        </button>
                    </th>
                </thead>
                <tbody id="fournisseurs-table" class="">
                    <tr>
                        <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
                            <div id="loading-spinner"
                                class=" mt-8 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 w-full">
                                <div
                                    class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32">
                                </div>
                            </div>
                            <style>
                                .loader {
                                    border-top-color: #3498db;
                                    animation: spinner 1.5s linear infinite;
                                }

                                @keyframes spinner {
                                    0% {
                                        transform: rotate(0deg);
                                    }

                                    100% {
                                        transform: rotate(360deg);
                                    }
                                }
                            </style>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div>
                <h1 class="text-xl font-semibold">Autres Fournisseurs</h1>
                <div class="flex gap-2 m-2">
                    <x-text-input placeholder="Nom du fournisseur" class=" w-1/2" id="searchbarFournisseur" />
                    <button class="btn" onclick="liveSearchFournisseurs()">Rechercher</button>
                </div>
                <table class="rounded-md overflow-hidden bg-gray-100 dark:bg-gray-900">
                    <thead>
                        <th colspan="100" class="text-sm">Nom</th>
                    </thead>
                    <tbody id="quicksearchfournisseurs-table" class="">
                        <tr>
                            <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
                                Recherchez un fournisseur
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </x-modal>
    <script>
        const unites = @json($unites);
        // Function to update sous-familles based on selected famille
        function updateSousFamilles() {
            var familleId = document.getElementById('famille_id_search').value;
            var sousFamilleSelect = document.getElementById('sous_famille_id_search');

            // Clear previous options
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
                        liveSearch();
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des sous familles :', error);
                    });
            }
        }

        let debounceQuickTimeout = null;
        let currentQuickSearchController = null;

        function liveSearch() {
            clearTimeout(debounceQuickTimeout);

            debounceQuickTimeout = setTimeout(() => {
                const search = document.getElementById('searchbar').value;
                const familleId = document.getElementById('famille_id_search').value;
                const sousFamilleId = document.getElementById('sous_famille_id_search').value;
                const matiereTable = document.getElementById('matiere-table');

                // Annule la requête précédente si elle existe
                if (currentQuickSearchController) {
                    currentQuickSearchController.abort();
                }

                // Affiche le loader dans le tableau
                matiereTable.innerHTML = `
            <tr>
                <td colspan="100">
                    <div id="loading-spinner" class="mt-8 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-32 w-full">
                        <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-16 w-16"></div>
                    </div>
                    <style>
                        .loader {
                            border-top-color: #3498db;
                            animation: spinner 1.5s linear infinite;
                        }
                        @keyframes spinner {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                    </style>
                </td>
            </tr>
        `;

                currentQuickSearchController = new AbortController();
                const {
                    signal
                } = currentQuickSearchController;

                const url =
                    `/matieres/quickSearch?search=${encodeURIComponent(search)}&famille=${familleId}&sous_famille=${sousFamilleId}`;
                console.log(url);

                fetch(url, {
                        signal
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Erreur lors de la récupération des données');
                        return response.json();
                    })
                    .then(data => {
                        matiereTable.innerHTML = '';

                        if (data.matieres && data.matieres.length > 0) {
                            data.matieres.forEach(matiere => {
                                const tr = document.createElement('tr');
                                tr.classList.add(
                                    'border-b', 'border-gray-200', 'dark:border-gray-700',
                                    'rounded-r-md', 'overflow-hidden', 'bg-white',
                                    'dark:bg-gray-800',
                                    'cursor-pointer', 'hover:bg-gray-200', 'dark:hover:bg-gray-700'
                                );
                                tr.setAttribute('data-matiere-id', matiere.id || '');
                                tr.setAttribute('data-matiere-ref', matiere.refInterne || '');
                                tr.setAttribute('data-matiere-designation', matiere.designation || '');
                                tr.setAttribute('data-matiere-basic-unite', matiere.lastPriceUnite ||
                                    '');
                                tr.setAttribute('data-matiere-unite', matiere.lastPriceUnite || matiere
                                    .Unite || '');
                                tr.addEventListener('click', addMatiere);

                                tr.innerHTML = `
                            <td class="text-left px-4">${matiere.refTooltip || '-'}</td>
                            <td class="text-left px-4">${matiere.designation || '-'}</td>
                            <td class="text-left px-4">${matiere.dn || '-'}</td>
                            <td class="text-left px-4">${matiere.epaisseur || '-'}</td>
                            <td class="text-left px-4">${matiere.tooltip || '-'}</td>
                            <td class="text-right px-4">${matiere.sousFamille || '-'}</td>
                        `;
                                matiereTable.appendChild(tr);
                            });
                        } else {
                            matiereTable.innerHTML = `
                        <tr>
                            <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center">
                                Aucune matière trouvée
                            </td>
                        </tr>
                    `;
                        }
                    })
                    .catch(error => {
                        if (error.name !== 'AbortError') {
                            console.error('Erreur lors de la recherche :', error);
                        }
                    });

            }, 300); // délai de debounce
        }



        // Function to add selected material to the chosen list
        function addMatiere(event) {
            const matiereId = event.currentTarget.getAttribute('data-matiere-id');
            const matiereRef = event.currentTarget.getAttribute('data-matiere-ref');
            const matiereUnite = event.currentTarget.getAttribute('data-matiere-unite');
            const matiereDesignation = event.currentTarget.getAttribute('data-matiere-designation');
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            const existingRow = matiereChoisiTable.querySelector(`tr[data-matiere-id="${matiereId}"]`);

            if (existingRow) {
                const quantityInput = existingRow.querySelector('input[name^="quantite"]');
                quantityInput.value = parseInt(quantityInput.value) + 1;
            } else {
                const tr = document.createElement('tr');
                tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                    'rounded-r-md', 'overflow-hidden', 'bg-white', 'dark:bg-gray-800', 'border-r-4');
                tr.setAttribute('data-matiere-id', matiereId);
                tr.setAttribute('data-fournisseurs-ids', '');
                tr.setAttribute('data-fournisseurs-noms', '');
                tr.innerHTML = `
            <td class="text-left px-4">${matiereRef || '-'}</td>
            <td class="text-left px-4">${matiereDesignation || '-'}</td>
            <td class="text-right px-4">
                <div class="flex items-center m-1 focus-within:ring-2 focus-within:ring-blue-500 dark:focus-within:ring-blue-600 focus-within:focus:border-indigo-600 rounded-sm">
                <x-text-input type="number" name="quantite[${matiereId}]" value="1" min="1" oninput="saveChanges()" class="w-20 border-r-0 rounded-r-none focus:ring-0 focus:border-0 dark:focus:ring-0 border-gray-300 dark:border-gray-700"


                />
                    ${unites.map(unite => `

                                                                ${unite.short === matiereUnite ? '<div class="text-right bg-gray-100 dark:bg-gray-900 w-fit p-2 pl-0 border-1 border-l-0 rounded-r-sm border-gray-300 dark:border-gray-700" title="'+unite.full+'">' : ''}

                                                                ${unite.short === matiereUnite ? unite.short+'</div>' : ''}
                                                        `).join('')}
                </div>
            </td>
            <td class="text-right px-4" >
                <div class="flex">
                    <button class=" float-right" data-matiere-id="${matiereId}" onclick="showFournisseurs(event)"
                x-on:click.prevent="$dispatch('open-modal', 'fournisseurs-modal')"
                title="Fournisseurs">
                <x-icons.list size="2" class="icons" />
                </button>
                <button class=" float-right" data-matiere-id="${matiereId}" onclick="removeMatiere(event)" tabindex="-1">
                <x-icons.close size="2" class="icons" />
                </button>


                </div>
                <input type="hidden" name="fournisseur-${matiereId}" value="">
            </td>
            `;
                if (matiereChoisiTable.querySelector('#no-matiere')) {
                    matiereChoisiTable.innerHTML = '';
                }
                matiereChoisiTable.appendChild(tr);
            }
        }

        function addLigneVide() {
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            const tr = document.createElement('tr');
            id = "ligne_autre_id-" + Date.now();
            if (document.querySelector(`tr[data-matiere-id="${id}"]`)) {
                id = id + Math.floor(Math.random() * 1000) + 1;
            }
            tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                'rounded-r-md', 'overflow-hidden', 'bg-white', 'dark:bg-gray-800', 'border-r-4');
            tr.setAttribute('data-matiere-id', id);
            tr.innerHTML = `
                <td class="text-left px-1">
                    <x-text-input type="text" name="case_ref[` + id + `]" value="" class="w-full" placeholder="AA-0052"
                    oninput="saveChanges()"
                    />
                </td>
                <td class="text-left px-1">
                    <x-text-input type="text" name="case_designation[` + id + `]" value="" class="w-full" placeholder="Désignation"
                    oninput="saveChanges()"
                    />
                </td>
                <td class="text-left px-5">
                    <x-text-input type="text"
                                name="case_quantite[` + id + `]"
                                oninput="saveChanges()"
                                class="w-24 border-gray-300 dark:border-gray-700"
                                value=""
                                min="0" />
                </td>
                <td>
                    <div class="flex justify-end mx-4">
                        <button class="float-right"
                                data-matiere-id="` + id + `"
                                onclick="removeMatiere(event)">
                                <x-icons.close size="2" class="icons"
                                    tabindex="-1" />
                            </button>
                    </div>
                </td>
            `;
            matiereChoisiTable.appendChild(tr);
            tr.focus();
        }

        function removeMatiere(event) {
            const matiereId = event.target.getAttribute('data-matiere-id');
            const row = event.target.closest('tr');
            row.remove();
            // const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            // if (matiereChoisiTable.querySelectorAll('tr').length === 0) {
            //     const tr = document.createElement('tr');
            //     tr.id = 'no-matiere';
            //     tr.innerHTML = `
        //     <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
        //         Aucune matière sélectionnée
        //     </td>
        // `;
            //     matiereChoisiTable.appendChild(tr);
            // }
            saveChanges();
        }


        // Function to show the list of suppliers for the selected material
        function showFournisseurs(event, isRefresh = 0) {
            let matiereId = "";
            liveSearchFournisseurs();
            const fournisseursTable = document.getElementById('fournisseurs-table');

            if (isRefresh == 1) {
                matiereId = fournisseursTable.querySelector('tr:first-child').getAttribute('data-matiere-id');

            } else {
                matiereId = event.currentTarget.getAttribute('data-matiere-id');
            }
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            const existingRow = matiereChoisiTable.querySelector(`tr[data-matiere-id="${matiereId}"]`);
            const fournisseursIds = existingRow.getAttribute('data-fournisseurs-ids');
            const fournisseursNoms = existingRow.getAttribute('data-fournisseurs-noms');
            const fournisseurInput = document.querySelector(`input[name="fournisseur-${matiereId}"]`);
            if (fournisseursIds != "" && isRefresh == 0) {
                const fournisseursSelecteds = fournisseurInput ? fournisseurInput.value : '';
                if (!fournisseurInput) {
                    console.error('Fournisseur input not found for matiere:', matiereId);
                    return;
                }
                fournisseursTable.innerHTML = '';
                fournisseursIds.split(';').forEach((fournisseurId, index) => {
                    const tr = document.createElement('tr');
                    const fournisseursSelected = fournisseursSelecteds.split(';').find(f => f == fournisseurId);
                    if (fournisseursSelected) {
                        tr.classList.add('bg-green-500', 'dark:bg-green-600', 'hover:bg-green-600',
                            'dark:hover:bg-green-700');
                    } else {
                        tr.classList.add('bg-white', 'dark:bg-gray-800', 'hover:bg-gray-200',
                            'dark:hover:bg-gray-700');
                    }
                    tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                        'rounded-r-md', 'overflow-hidden', 'cursor-pointer');
                    tr.setAttribute('data-fournisseur-id', fournisseurId);
                    tr.setAttribute('data-matiere-id', matiereId);
                    tr.addEventListener('click', addFournisseur);
                    tr.innerHTML = `
                    <td class="text-left px-4" colspan="2">${fournisseursNoms.split(';')[index] || '-'}</td>
                    `;
                    if (fournisseursNoms.split(';')[index] == "") {
                        tr.classList.add('hidden');
                    }
                    fournisseursTable.appendChild(tr);
                });
                return;
            }
            fournisseursTable.innerHTML =
                '<tr><td colspan="100"><div id="loading-spinner" class=" mt-8 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-32 w-full"><div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div></div><style>.loader {border-top-color: #3498db;animation: spinner 1.5s linear infinite;}@keyframes spinner {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}</style></tr></td>';
            fetch(`/matieres/${matiereId}/fournisseurs/json`)
                .then(response => response.json())
                .then(data => {
                    fournisseursTable.innerHTML = '';
                    let FinalDataIds = [];
                    let FinalDataNoms = [];
                    data.forEach(fournisseur => {
                        FinalDataIds.push(fournisseur.id.toString());
                        FinalDataNoms.push(fournisseur.raison_sociale);
                    });
                    if (fournisseurInput.value != "") {
                        fournisseurInput.value.split(';').forEach(id => {
                            if (!FinalDataIds.includes(id)) {
                                FinalDataIds.push(id);
                                let index = existingRow.getAttribute('data-fournisseurs-ids').split(';')
                                    .indexOf(id.toString());
                                FinalDataNoms.push(existingRow.getAttribute('data-fournisseurs-noms').split(
                                    ';')[index]);
                            }
                        });
                    }
                    FinalDataIds = [...new Set(FinalDataIds)];
                    FinalDataNoms = [...new Set(FinalDataNoms)];
                    if (FinalDataIds.length == 0) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
                            Aucun fournisseur trouvé
                        </td>
                    `;
                        tr.setAttribute('data-matiere-id', matiereId);
                        tr.id = 'no-fournisseur';
                        fournisseursTable.appendChild(tr);
                    }

                    FinalDataIds.forEach((fournisseurId, index) => {
                        const tr = document.createElement('tr');
                        const fournisseursSelected = fournisseurInput.value.split(';').find(f => f ==
                            fournisseurId);
                        if (fournisseursSelected) {
                            tr.classList.add('bg-green-500', 'dark:bg-green-600', 'hover:bg-green-600',
                                'dark:hover:bg-green-700');
                        } else {
                            tr.classList.add('bg-white', 'dark:bg-gray-800', 'hover:bg-gray-200',
                                'dark:hover:bg-gray-700');
                        }
                        tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                            'rounded-r-md', 'overflow-hidden', 'cursor-pointer');
                        tr.setAttribute('data-fournisseur-id', fournisseurId);
                        tr.setAttribute('data-matiere-id', matiereId);
                        tr.addEventListener('click', addFournisseur);
                        tr.innerHTML = `
                        <td class="text-left px-4" colspan="2">${FinalDataNoms[index] || '-'}</td>
                        `;
                        fournisseursTable.appendChild(tr);
                    });
                    existingRow.setAttribute('data-fournisseurs-ids', FinalDataIds.join(';'));
                    existingRow.setAttribute('data-fournisseurs-noms', FinalDataNoms.join(';'));
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des fournisseurs :', error);
                });
            liveSearchFournisseurs();
        }

        // Function to add selected supplier to the material
        function addFournisseur(event) {
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            const fournisseurId = event.currentTarget.getAttribute('data-fournisseur-id');

            let matiereId;
            if (event.currentTarget.getAttribute('data-is-from-quicksearch') == 'true') {
                matiereId = document.getElementById('fournisseurs-table').querySelector('tr:first-child').getAttribute(
                    'data-matiere-id');
            } else {
                matiereId = event.currentTarget.getAttribute('data-matiere-id');
            }
            const existingRow = matiereChoisiTable.querySelector(`tr[data-matiere-id="${matiereId}"]`);
            if (existingRow) {
                const fournisseurInput = existingRow.querySelector(`input[name="fournisseur-${matiereId}"]`);
                const fournisseursNoms = existingRow.getAttribute('data-fournisseurs-noms') || '';
                const currentFournisseurs = fournisseurInput.value ? fournisseurInput.value.split(';') : [];
                const index = currentFournisseurs.indexOf(fournisseurId);
                if (index === -1) {
                    // Add fournisseur
                    var fournisseurtotal = Array.from(new Set(Array.from(document.querySelectorAll(
                        `input[name^="fournisseur-"]`)).map(input => input.value.split(';')).flat().concat(
                        fournisseurId))).length;
                    if (fournisseurtotal > 10) {
                        showFlashMessageFromJs('Vous ne pouvez pas ajouter plus de 10 fournisseurs différents', duree =
                            2000, type = 'error')
                        return;
                    }
                    currentFournisseurs.push(fournisseurId);
                    event.currentTarget.classList.remove('bg-white', 'dark:bg-gray-800', 'hover:bg-gray-200',
                        'dark:hover:bg-gray-700');
                    event.currentTarget.classList.add('bg-green-500', 'dark:bg-green-600', 'hover:bg-green-600',
                        'dark:hover:bg-green-700');
                    const firstChild = document.getElementById('fournisseurs-table').querySelector('tr:first-child');
                    if (firstChild && firstChild.id == 'no-fournisseur') {
                        firstChild.remove();
                    }
                } else {
                    // Remove fournisseur
                    currentFournisseurs.splice(index, 1);
                    event.currentTarget.classList.remove('bg-green-500', 'dark:bg-green-600', 'hover:bg-green-600',
                        'dark:hover:bg-green-700');
                    event.currentTarget.classList.add('bg-white', 'dark:bg-gray-800', 'hover:bg-gray-200',
                        'dark:hover:bg-gray-700');
                }
                if (event.currentTarget.getAttribute('data-is-from-quicksearch') == 'true') {
                    const fournisseurNom = event.currentTarget.getAttribute('data-fournisseur-nom');
                    const existingFournisseur = document.querySelector(
                        `#fournisseurs-table tr[data-fournisseur-id="${fournisseurId}"]`);
                    if (!existingFournisseur) {
                        const clonedRow = event.currentTarget.cloneNode(true);
                        clonedRow.setAttribute('data-matiere-id', matiereId);
                        document.getElementById('fournisseurs-table').appendChild(clonedRow);
                        existingRow.setAttribute('data-fournisseurs-ids',
                            `${existingRow.getAttribute('data-fournisseurs-ids')};${fournisseurId}`);
                        existingRow.setAttribute('data-fournisseurs-noms',
                            `${existingRow.getAttribute('data-fournisseurs-noms')};${fournisseurNom}`);
                        event.currentTarget.remove();
                    }
                }

                fournisseurInput.value = currentFournisseurs.join(';');
                saveChanges();
            }
        }

        function saveChanges() {
            const ddpEntite = document.getElementById('ddp-entite');
            const ddpNom = document.querySelector('input[name="ddp-nom"]');
            const ddpCode = document.querySelector('input[name="ddp-code"]');
            const ddpCodeEntite = document.getElementById('ddp-code-entite');
            const ddpId = document.getElementById('new-ddp').textContent.trim();
            const saveStatus0 = document.getElementById('save-status-0');
            const saveStatus1 = document.getElementById('save-status-1');
            const saveStatus2 = document.getElementById('save-status-2');
            const matiereChoisiTable = document.getElementById('matiere-choisi-table');
            saveStatus0.classList.remove('hidden');
            saveStatus1.classList.add('hidden');
            saveStatus2.classList.add('hidden');

            if ('' === ddpCode.value.trim()) {
                saveStatus0.classList.add('hidden');
                saveStatus2.classList.remove('hidden');
                return;
            }

            if (ddpId === '') {
                saveStatus0.classList.add('hidden');
                saveStatus2.classList.remove('hidden');
                return;
            }
            if (!matiereChoisiTable.querySelector('tr[data-matiere-id]')) {

                saveStatus0.classList.add('hidden');
                saveStatus2.classList.remove('hidden');
                return;
            }
            // if (matiereChoisiTable.querySelector('tr[data-matiere-id] input[name^="fournisseur-"]').value === '') {
            //     saveStatus0.classList.add('hidden');
            //     saveStatus2.classList.remove('hidden');
            //     return;
            // }
            const matieres = [];
            document.querySelectorAll('#matiere-choisi-table tr[data-matiere-id]').forEach(row => {
                const matiereId = row.getAttribute('data-matiere-id');
                if (matiereId.startsWith('ligne_autre_id')) {
                    const case_ref = row.querySelector(`input[name="case_ref[${matiereId}]"]`).value;
                    const case_designation = row.querySelector(`input[name="case_designation[${matiereId}]"]`)
                        .value;
                    const case_quantite = row.querySelector(`input[name="case_quantite[${matiereId}]"]`).value;

                    if (case_ref.trim() === '' && case_designation.trim() === '' && case_quantite.trim() === '') {
                        saveStatus0.classList.add('hidden');
                        saveStatus2.classList.remove('hidden');
                        return;
                    }

                    matieres.push({
                        ligne_autre_id: matiereId,
                        case_ref: case_ref,
                        case_designation: case_designation,
                        case_quantite: case_quantite,
                    });
                    row.classList.add('border-r-green-500', 'dark:border-r-green-600');
                } else {
                    const quantity = row.querySelector(`input[name="quantite[${matiereId}]"]`).value;
                    const fournisseurs = row.querySelector(`input[name="fournisseur-${matiereId}"]`).value;
                    // const unite_id = row.querySelector(`select[name="unite[${matiereId}]`).value;
                    row.classList.remove('border-r-green-500', 'dark:border-r-green-600');
                    if (quantity < 1) {
                        saveStatus0.classList.add('hidden');
                        saveStatus2.classList.remove('hidden');
                        return;
                    }
                    if (fournisseurs !== '') {
                        matieres.push({
                            id: matiereId,
                            quantity: quantity,
                            // unite_id: unite_id,

                            fournisseurs: fournisseurs.split(';')
                        });
                        row.classList.add('border-r-green-500', 'dark:border-r-green-600');
                        ddpNom.classList.add('border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
                        ddpCode.classList.add('border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
                        ddpEntite.classList.add('border-r-green-500', 'dark:border-r-green-600', 'border-r-4');
                        if (ddpEntite.value == 1) {
                            ddpCodeEntite.textContent = '';
                        } else if (ddpEntite.value == 2) {
                            ddpCodeEntite.textContent = 'AV';
                        } else if (ddpEntite.value == 3) {
                            ddpCodeEntite.textContent = 'AMB';
                        } else {
                            ddpCodeEntite.textContent = '';
                        }
                        document.title =
                            `Créer - DDP-${new Date().getFullYear().toString().slice(-2)}-${ddpCode.value}${ddpCodeEntite.textContent}`;

                    }
                    if ('' === ddpNom.value.trim()) {
                        ddpNom.value = 'DDP-' + new Date().getFullYear().toString().slice(-2) + '-' + ddpCode
                            .value + ddpCodeEntite
                            .textContent;
                    }
                }
            });
            console.log(matieres);
            fetch('/ddp/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        ddp_id: ddpId,
                        entite_id: ddpEntite.value,
                        nom: ddpNom.value,
                        code: ddpCode.value,
                        matieres: matieres
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    saveStatus0.classList.add('hidden');
                    saveStatus1.classList.remove('hidden');
                })
                .catch(error => {
                    saveStatus0.classList.add('hidden');
                    saveStatus2.classList.remove('hidden');
                });
        }

        async function liveSearchFournisseurs() {
            const search = document.getElementById('searchbarFournisseur').value;
            const response = await fetch(
                `/societes/fournisseurs/quickSearch?search=${encodeURIComponent(search)}`
            );
            const data = await response.json();
            const fournisseursTable = document.getElementById('quicksearchfournisseurs-table');
            fournisseursTable.innerHTML = '';
            if (data.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td colspan="100" class="text-gray-500 dark:text-gray-400 text-center ">
                        Aucun fournisseur trouvé
                    </td>
                `;
                fournisseursTable.appendChild(tr);
            } else {
                const matiereChoisiTable = document.getElementById('matiere-choisi-table');
                matiereId = document.getElementById('fournisseurs-table').querySelector('tr:first-child').getAttribute(
                    'data-matiere-id');
                const existingRow = matiereChoisiTable.querySelector(`tr[data-matiere-id="${matiereId}"]`);
                var fournisseursIds = "";
                var fournisseursIdsTemp = "";
                if (existingRow) {
                    fournisseursIds = existingRow.getAttribute('data-fournisseurs-ids');
                } else {
                    fournisseursIds = "";
                }
                var fournisseursChoisiAilleurs = [];
                document.querySelectorAll('input[name^="fournisseur-"]').forEach(input => {
                    if (!input.name.endsWith(matiereId + "]") && !input.name.endsWith(matiereId)) {
                        if (input.value !== "") {
                            fournisseursIdsTemp += ';' + input.value;
                        }
                    }
                });
                fournisseursChoisiAilleurs = fournisseursIdsTemp.split(';');
                console.log(fournisseursChoisiAilleurs);
                data.forEach(fournisseur => {
                    if (!matiereId || !fournisseursIds || !fournisseursIds.split(';').includes(fournisseur.id
                            .toString())) {


                        const tr = document.createElement('tr');
                        tr.classList.add('border-b', 'border-gray-200', 'dark:border-gray-700',
                            'rounded-r-md', 'overflow-hidden', 'cursor-pointer',
                            'hover:bg-gray-200', 'dark:hover:bg-gray-700');
                        const fournisseurId = fournisseur.id || '';
                        tr.setAttribute('data-fournisseur-id', fournisseurId);
                        tr.setAttribute('data-fournisseur-nom', fournisseur.raison_sociale || '');
                        tr.setAttribute('data-is-from-quicksearch', 'true');
                        tr.addEventListener('click', addFournisseur);
                        tr.innerHTML = `
                        <td class="text-left px-4" colspan="2">
                            ${fournisseur.raison_sociale || '-'}
                            ${fournisseursChoisiAilleurs.includes(fournisseur.id.toString()) ? '<span class="ml-2 text-xs text-yellow-600 dark:text-yellow-400">(déjà sélectionné ailleurs)</span>' : ''}
                        </td>
                    `;
                        fournisseursTable.appendChild(tr);
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Event listener for famille selection change
            document.getElementById('famille_id_search').addEventListener('change', function() {
                updateSousFamilles();
            });
            document.getElementById('sous_famille_id_search').addEventListener('change', function() {
                liveSearch();
            });
            const searchbar = document.getElementById('searchbar');
            const searchbarFournisseur = document.getElementById('searchbarFournisseur');
            const matiereTable = document.getElementById('matiere-table');
            const ddpEntite = document.getElementById('ddp-entite');
            const ddpNom = document.getElementById('ddp-nom');
            const ddpCode = document.getElementById('ddp-code');

            // Event listener for search bar input

            searchbar.addEventListener('input', function() {
                liveSearch();
            });
            searchbarFournisseur.addEventListener('input', function() {
                liveSearchFournisseurs();
            });



            ddpNom.addEventListener('input', function() {
                if (ddpNom.value !== undefined && ddpNom.value.trim() !== '') {
                    saveChanges();
                }
            });
            ddpCode.addEventListener('input', function() {

                saveChanges();
            });
            ddpEntite.addEventListener('change', function() {
                fetch('/ddp/get-last-code/' + ddpEntite.value, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.title =
                            `Créer DDP-${new Date().getFullYear().toString().slice(-2)}-${data.code}${data.entite_code}`;
                        document.getElementById('ddp-code').value = data.code;
                        document.getElementById('ddp-code-entite').textContent = data.entite_code;
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération du code :', error);
                    });
                saveChanges();
            });

        });
    </script>
</x-app-layout>
