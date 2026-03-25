@php
    use Carbon\Carbon;
@endphp

<x-app-layout>
    @section('title', 'Validation - ' . $cde->code)
    <x-slot name="header">

        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('cde.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Commandes</a>
                >>
                <a href="{{ route('cde.show', $cde->id) }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Créer une commande') !!}</a>
                >> Validation
            </h2>
            @if ($listeChangement != false)
                <div class="flex items-center">
                    <button x-data x-on:click="$dispatch('open-modal', 'listeChangement-modal')" type="button"
                        id="open-modal-listeChangement" class="btn btn-primary">
                        <span class="ml-2">Liste des changements</span>
                    </button>
                </div>
            @endif
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
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <form action="{{ route('cde.validate', $cde->id) }}" method="POST">
            @csrf
            <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
                <div class="flex justify-between mb-6">

                    <div class="flex items-center mb-6">
                        <h1 class="text-3xl font-bold text-left mr-2">
                            {{ $cde->code }}{{ $cde->code !== $cde->nom ? ' - ' . $cde->nom : '' }}</h1>
                        <div class="text-center w-fit px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                            style="background-color: {{ $cde->statut->couleur }}; color: {{ $cde->statut->couleur_texte }}">
                            {{ $cde->statut->nom }}</div>
                    </div>
                    <button type="submit" class="btn h-fit w-fit"
                        onclick="document.getElementById('quick_save').value = 'true';">
                        <span class="">Enregistrer</span>
                    </button>
                    <input type="hidden" name="quick_save" id="quick_save" value="false">
                    @error('quick_save')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>


                {{--
##     ##    ###    ##     ## ########      ########  ########      ########     ###     ######   ########
##     ##   ## ##   ##     ##    ##         ##     ## ##            ##     ##   ## ##   ##    ##  ##
##     ##  ##   ##  ##     ##    ##         ##     ## ##            ##     ##  ##   ##  ##        ##
######### ##     ## ##     ##    ##         ##     ## ######        ########  ##     ## ##   #### ######
##     ## ######### ##     ##    ##         ##     ## ##            ##        ######### ##    ##  ##
##     ## ##     ## ##     ##    ##         ##     ## ##            ##        ##     ## ##    ##  ##
##     ## ##     ##  #######     ##         ########  ########      ##        ##     ##  ######   ########
 --}}
                <h2 class="text-xl font-bold mb-6 text-left border-b-2 border-gray-200 dark:border-gray-700 p-2">Haut
                    de page</h2>
                <div class="flex justify-between">
                    <div class="flex flex-col gap-4 m-4">
                        <div class="flex gap-4 items-end">
                            <div class="flex flex-col gap-2 w-full">
                                <div class="flex gap-4">
                                <x-input-label value="Affaire associée" />
                                <small>(Optionnel)</small>
                            </div>
                                <div class="flex items-center">
                                    <select name="affaire_id" id="affaire_id" class="select-left w-fit min-w-96"
                                        >
                                        <option value="">Sélectionner une affaire</option>
                                        @foreach ($affaires as $affaire)
                                            <option value="{{ $affaire->id }}"
                                                {{ old('affaire_id', $cde->affaire_id) == $affaire->id ? 'selected' : '' }}>
                                                {{ $affaire->code }} — {{ $affaire->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-tooltip position="top" class="w-full">
                                        <x-slot name="slot_item">
                                            <button type="button" class="btn-select-right" x-data
                                                x-on:click.prevent="$dispatch('open-modal', 'create-affaire-modal')">
                                                +
                                            </button>
                                        </x-slot>
                                        <x-slot name="slot_tooltip">
                                            Ajouter une affaire
                                        </x-slot>
                                    </x-tooltip>

                                </div>
                                @error('affaire_id')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-4">
                                <x-input-label value="Numéro de devis" />
                                <small>(Optionnel)</small>
                            </div>
                            <x-text-input name="numero_devis" :value="old('numero_devis', $cde->devis_numero)" />
                            @error('numero_devis')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-4">
                                <x-input-label value="Affaire suivi par " />
                                <small>(Optionnel)</small>
                            </div>
                            <select name="affaire_suivi_par" class="select w-fit min-w-96">
                                <option value=""
                                    {{ old('affaire_suivi_par', $cde->affaire_suivi_par_id) == 0 ? 'selected' : '' }}>
                                    Non
                                    suivi</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('affaire_suivi_par', $cde->affaire_suivi_par_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('affaire_suivi_par')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-4">
                                <x-input-label value="Acheteur " />
                                <small>(Optionnel)</small>
                            </div>
                            <select name="acheteur_id" class="select w-fit min-w-96">
                                <option value=""
                                    {{ old('acheteur_id', $cde->acheteur_id) == 0 ? 'selected' : '' }}>
                                    Sans Acheteur
                                </option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('acheteur_id', $cde->acheteur_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('acheteur_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex gap-4">
                            <div class="flex gap-2">
                                <x-toggle :checked="old('afficher_destinataire', true)" :label="'Afficher le destinataire dans le PDF ?'" id="afficher_destinataire"
                                    name="afficher_destinataire" class="toggle-class" />
                                <x-tooltip position="top" class="w-full">
                                    <x-slot name="slot_item">
                                        <x-icons.question class="icons" size="1" />
                                    </x-slot>
                                    <x-slot name="slot_tooltip">
                                        <p class="text-sm font-bold">Affiche:</p>
                                        <p class="text-sm">À l'attention de : {{ $cde->societeContacts->first()?->nom }}
                                        </p>
                                        <p class="text-sm">{{ $cde->societeContacts->first()?->email }}.</p>
                                    </x-slot>
                                </x-tooltip>
                            </div>
                            @error('afficher_destinataire')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <img src="{{ asset($entite->logo) }}" alt="Logo"
                        class="w-1/4 h-1/4 mb-4 object-contain float-right">
                </div>
                {{--
 ######   #######  ########  ########   ######
##    ## ##     ## ##     ## ##     ## ##    ##
##       ##     ## ##     ## ##     ## ##
##       ##     ## ########  ########   ######
##       ##     ## ##   ##   ##              ##
##    ## ##     ## ##    ##  ##        ##    ##
 ######   #######  ##     ## ##         ######
--}}
                <h2 class="text-xl font-bold mb-6 text-left border-b-2 border-gray-200 dark:border-gray-700 p-2">Corps
                    de la commande</h2>
                <div class="flex">
                    <div class="m-4">
                        <x-input-label value="TVA (%)" />
                        <x-text-input name="tva" type="number" :value="old('tva', $cde->tva)" onblur="recalculateTotal()" />
                        @error('tva')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col m-4">
                        <div class="flex gap-4">
                            <x-input-label value="Frais de port" />
                            <small>(Optionnel)</small>
                        </div>
                        <div class="price-input-container">
                            <x-text-input name="frais_de_port" type="number" step="0.01" :value="old('frais_de_port', formatNumber($cde->frais_de_port, true))"
                                onblur="recalculateTotal()" class=" price-input" />
                        </div>
                        @error('frais_de_port')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <div class="flex flex-col m-4">
                            <div class="flex gap-4">
                                <x-input-label value="Frais divers" />
                                <small>(Optionnel)</small>
                            </div>
                            <div class="price-input-container">
                                <x-text-input name="frais_divers" type="number" step="0.01" :value="old('frais_divers', formatNumber($cde->frais_divers, true))"
                                    onblur="fraisDiversChange();recalculateTotal()" class="price-input" />
                            </div>
                            @error('frais_divers')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col m-4 {{ $cde->frais_divers == null ? 'hidden' : '' }}">
                            <div class="flex gap-4">
                                <x-input-label value="Description des frais divers" />
                                <small>(Optionnel)</small>
                            </div>
                            <x-text-input name="frais_divers_texte" :value="old('frais_divers_texte', $cde->frais_divers_texte)" />
                            @error('frais_divers_texte')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <script>
                            function fraisDiversChange() {
                                const fraisDivers = document.querySelector('input[name="frais_divers"]');
                                const fraisDiversTexte = document.querySelector('input[name="frais_divers_texte"]');
                                if (fraisDivers.value != '') {
                                    fraisDiversTexte.parentElement.classList.remove('hidden');
                                } else {
                                    fraisDiversTexte.parentElement.classList.add('hidden');
                                }
                            }
                        </script>
                    </div>
                </div>
                <table class="min-w-0 bg-gray-100 dark:bg-gray-900 ">
                    <thead>
                        <tr>
                            <th style="width: 5px; padding: 0%;padding-top:5px;">
                                <div class="poste">Poste</div>
                            </th>
                            <th class="text-left">Référence</th>
                            <th class="text-left">Désignation</th>
                            <th class="text-left px-1">Quantité</th>
                            <th class="text-left">Prix unitaire</th>
                            <th class="text-left">Total HT</th>
                            <th class="text-left">date de livraison</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cde->cdeLignes as $ligne)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="w-6 text-center border border-gray-200 dark:border-gray-700">
                                    {{ $ligne->poste ?? '-' }}
                                </td>
                                <td class="text-left ml-1 p-2">
                                    <div class="flex flex-col {{ $showRefFournisseur ? '' : 'hidden' }}"
                                        id="refs-{{ $ligne->matiere_id }}">
                                        <div class="flex flex-col">
                                            <span class="text-xs">Réf. Interne</span>
                                            <span class="font-bold">{{ $ligne->ref_interne ?? '-' }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-xs">Réf. Fournisseur</span>
                                            <span class="font-bold">{{ $ligne->ref_fournisseur ?? '-' }}</span>

                                        </div>
                                    </div>
                                    <div class="flex flex-col {{ $showRefFournisseur ? 'hidden' : '' }}"
                                        id="ref-{{ $ligne->matiere_id }}">
                                        <div class="flex flex-col">
                                            <span class="text-xs">Réf. Interne</span>
                                            <span class="font-bold">{{ $ligne->ref_interne ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-2 text-left border border-gray-200 dark:border-gray-700">
                                    {{ $ligne->designation }}
                                    @if ($ligne->sous_ligne != null)
                                        <br />
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $ligne->sous_ligne }}
                                        </span>
                                    @endif
                                </td>
                                <td class="p-2 text-center border border-gray-200 dark:border-gray-700"
                                    title="{{ formatNumber($ligne->quantite) . ($ligne->matiere ? ' ' . $ligne->matiere->unite->full : '') }}">
                                    {{ formatNumber($ligne->quantite) . ($ligne->matiere ? ' ' . $ligne->matiere->unite->short : '') }}
                                </td>
                                <td class="p-2 text-left border border-gray-200 dark:border-gray-700"
                                    title="{{ formatNumberArgent($ligne->prix_unitaire) }} {{ $ligne->matiere ? 'euro(s) par' . $ligne->matiere->unite->full : '' }}">
                                    {{ formatNumberArgent($ligne->prix_unitaire) }}{{ $ligne->matiere ? '/' . $ligne->matiere->unite->short : '' }}
                                </td>
                                <td class="p-2 text-left border border-gray-200 dark:border-gray-700">
                                    {{ formatNumberArgent($ligne->prix) }} </td>
                                <td class="p-2 text-left border border-gray-200 dark:border-gray-700">
                                    {{ Carbon::parse($ligne->date_livraison)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                        <tr class="border-t-2 border-gray-200 dark:border-gray-700">
                            <td class="p-2 " colspan="400">
                                <div class="w-full">

                                    <table class="min-w-0 float-right text-right">
                                        <tbody>
                                            <tr
                                                class="{{ $cde->frais_de_port || $cde->frais_divers ? '' : 'hidden' }}">
                                                <td class="pr-4 text-gray-500">
                                                    Total HT :
                                                </td>
                                                <td id="total_ht_gray" class="text-gray-500">
                                                    {{ formatNumberArgent($cde->total_ht) }}
                                                </td>
                                            </tr>
                                            <tr class="{{ $cde->frais_de_port ? '' : 'hidden' }}">
                                                <td class="pr-4 text-gray-500">
                                                    Frais de port :
                                                </td>
                                                <td id="frais_de_port" class="text-gray-500">
                                                    {{ formatNumberArgent($cde->frais_de_port) }}
                                                </td>
                                            </tr>
                                            <tr class="{{ $cde->frais_divers ? '' : 'hidden' }}">
                                                <td class="pr-4 text-gray-500">
                                                    Frais divers :
                                                </td>
                                                <td id="frais_divers" class="text-gray-500">
                                                    {{ formatNumberArgent($cde->frais_divers) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pr-4">
                                                    Total HT :
                                                </td>
                                                <td id="total_ht">
                                                    {{ formatNumberArgent($cde->total_ht + $cde->frais_de_port + $cde->frais_divers) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pr-4" id="tva_container">
                                                    TVA ({{ $cde->tva }}%) :
                                                </td>
                                                <td id="total_tva_plus">
                                                    {{ formatNumberArgent(round((($cde->total_ht + $cde->frais_de_port + $cde->frais_divers) * $cde->tva) / 100, 3)) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pr-4">
                                                    Total TTC :
                                                </td>
                                                <td id="total_ttc">
                                                    {{ formatNumberArgent(round($cde->total_ht + $cde->frais_de_port + $cde->frais_divers + (($cde->total_ht + $cde->frais_de_port + $cde->frais_divers) * $cde->tva) / 100, 3)) }}

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                {{--
########  #### ######## ########       ########  ########      ########     ###     ######   ########
##     ##  ##  ##       ##     ##      ##     ## ##            ##     ##   ## ##   ##    ##  ##
##     ##  ##  ##       ##     ##      ##     ## ##            ##     ##  ##   ##  ##        ##
########   ##  ######   ##     ##      ##     ## ######        ########  ##     ## ##   #### ######
##         ##  ##       ##     ##      ##     ## ##            ##        ######### ##    ##  ##
##         ##  ##       ##     ##      ##     ## ##            ##        ##     ## ##    ##  ##
##        #### ######## ########       ########  ########      ##        ##     ##  ######   ########
--}}
                <h2 class="text-xl font-bold mb-6 text-left border-b-2 border-gray-200 dark:border-gray-700 p-2">Pied
                    de page</h2>
                <div class="flex flex-col md:flex-row gap-4">
                    <div>
                        <div class="flex flex-col gap-4 m-4">

                            <div
                                class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 pr-0 border border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between pr-4">
                                    <h3 class="font-medium text-lg">Notes de commande</h3>
                                    <a href="{{ route('administration.cdeNote.index', $cde->entite_id) }}"
                                        target="_blank">
                                        <x-tooltip position="bottom">
                                            <x-slot name="slot_item">
                                                <x-icons.edit-note size="2" class="icons" />
                                            </x-slot>
                                            <x-slot name="slot_tooltip">
                                                <span>Ajouter ou modifier les notes de commande</span> <br />
                                                <span class="text-sm text-gray-500">Veuillez actualiser cette page
                                                    après avoir modifié
                                                    les notes de commande.</span>
                                            </x-slot>
                                        </x-tooltip>
                                    </a>
                                </div>
                                <div class="grid grid-cols-1 gap-2 mb-4 max-h-80 overflow-y-auto pr-2">
                                    @foreach ($cde_notes as $note)
                                        <label
                                            class="flex items-center p-3 rounded-md border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                            <input type="checkbox" name="cdenotes[]" value="{{ $note->id }}"
                                                {{ in_array($note->id, old('cdenotes', $cde->cdenotes->pluck('id')->toArray())) ? 'checked' : ($note->is_checked ? 'checked' : '') }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-3">{{ $note->contenu }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4 pr-4 -ml-4 pl-4">
                                    <label
                                        class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                        Note personnalisée
                                    </label>
                                    <textarea name="custom_note" class="textarea" rows="3"
                                        placeholder="Ajoutez ici des informations spécifiques à cette commande...">{{ old('note_personnalisee', $cde->custom_note) }}</textarea>
                                    <div class="hidden" id="save_custom_note_div">
                                        <x-toggle name="save_custom_note" id="save_custom_note" :checked="old('save_custom_note')"
                                            :label="'Enregistrer cette note personnalisée pour les prochaines commandes ?'" class="toggle-class" />
                                    </div>
                                    @error('custom_note')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>

                            @error('cdenotes')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            @error('note_personnalisee')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 m-4">
                        <x-input-label value="type d'expédition" />
                        <select name="type_expedition_id" required class="select w-fit min-w-96"
                            onchange="changeTypeExpedition(this)">
                            @foreach ($typesExpedition as $typeExpedition)
                                <option value="{{ $typeExpedition->id }}"
                                    {{ old('type_expedition_id', $cde->type_expedition_id) == $typeExpedition->id ? 'selected' : '' }}>
                                    {{ $typeExpedition->nom }}
                                </option>
                            @endforeach
                        </select>
                        @php
                            if ($cde->adresse_livraison == null) {
                                $adresse_livraison = new stdClass();
                                $adresse_livraison->adresse = $entite->adresse;
                                $adresse_livraison->ville = $entite->ville;
                                $adresse_livraison->code_postal = $entite->code_postal;
                                $adresse_livraison->pays = 'France';
                                $adresse_livraison->horaires = $entite->horaires;
                            } else {
                                $adresse_livraison = json_decode($cde->adresse_livraison);
                            }
                        @endphp
                        <div class="flex flex-col gap-4 m-4 ml-0" id="adresse_livraison">
                            <div>
                                <x-input-label value="horaires de livraison" />
                                <textarea name="horaires"
                                    class="mt-1 block px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-900 dark:text-gray-100 w-fit min-w-96">{{ old('horaires', $adresse_livraison->horaires ?? '') }}</textarea>
                                @error('horaires')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <x-input-label value="Adresse de livraison" />
                                <x-text-input name="adresse" :value="old('adresse', $adresse_livraison->adresse)" class="w-fit min-w-96" />
                                @error('adresse')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <x-input-label value="Ville" />
                                <x-text-input name="ville" :value="old('ville', $adresse_livraison->ville)" />
                                @error('ville')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <x-input-label value="Code Postal" />
                                <x-text-input name="code_postal" :value="old('code_postal', $adresse_livraison->code_postal)" />
                                @error('code_postal')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <x-input-label value="Pays" />
                                <x-text-input name="pays" :value="old('pays', $adresse_livraison->pays)" />
                                @error('pays')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex flex-col gap-4 m-4">
                            <x-input-label value="Conditions de paiement" />
                            <div>
                                <select name="condition_paiement_id" required class="select w-fit min-w-96"
                                    onchange="changeConditionPaiement()">

                                    @foreach ($conditionsPaiement as $conditionPaiement)
                                        <option value="{{ $conditionPaiement->id }}"
                                            {{ old(
                                                'condition_paiement_id',
                                                $cde->condition_paiement_id != null
                                                    ? ($cde->condition_paiement_id == $conditionPaiement->id
                                                        ? 'selected'
                                                        : '')
                                                    : ($cde->societe && $cde->societe->condition_paiement_id == $conditionPaiement->id
                                                        ? 'selected'
                                                        : ''),
                                            ) }}>
                                            {{ $conditionPaiement->nom }}
                                        </option>
                                    @endforeach
                                    <option value="0">Autre</option>
                                </select>
                                <x-text-input name="condition_paiement_text" :value="old('condition_paiement_text')"
                                    class="w-fit min-w-96 rounded-t-none border-0 pt-2 -mt-2 hidden focus:border-t-0 focus:ring-0" />
                                <script>
                                    function changeConditionPaiement() {
                                        const select = document.querySelector('select[name="condition_paiement_id"]');
                                        const input = document.querySelector('input[name="condition_paiement_text"]');
                                        if (select.value == 0) {
                                            input.classList.remove('hidden');
                                            input.required = true;
                                            input.focus();
                                        } else {
                                            input.classList.add('hidden');
                                            input.value = '';
                                            input.required = false;
                                        }
                                    }
                                </script>
                            </div>
                            @error('condition_paiement_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="flex justify-between mt-4">
                    <a href="{{ route('cde.show', $cde->id) }}" class="btn">{{ __('Retour') }}</a>
                    <div class="flex gap-2">
                        <button type="submit" class="btn h-fit w-fit"
                            onclick="document.getElementById('quick_save').value = 'true';">
                            <span class="">Enregistrer</span>
                        </button>
                        <button type="submit" class="btn"
                            title="enregistrer et valider">{{ __('Valider') }}</button>
                    </div>
                </div>
            </div>
            {{--
 ######  ##     ##    ###    ##    ##  ######   ######## ##     ## ######## ##    ## ########
##    ## ##     ##   ## ##   ###   ## ##    ##  ##       ###   ### ##       ###   ##    ##
##       ##     ##  ##   ##  ####  ## ##        ##       #### #### ##       ####  ##    ##
##       ######### ##     ## ## ## ## ##   #### ######   ## ### ## ######   ## ## ##    ##
##       ##     ## ######### ##  #### ##    ##  ##       ##     ## ##       ##  ####    ##
##    ## ##     ## ##     ## ##   ### ##    ##  ##       ##     ## ##       ##   ###    ##
 ######  ##     ## ##     ## ##    ##  ######   ######## ##     ## ######## ##    ##    ##



########  ########      ########  ######## ########
##     ## ##            ##     ## ##       ##
##     ## ##            ##     ## ##       ##
##     ## ######        ########  ######   ######
##     ## ##            ##   ##   ##       ##
##     ## ##            ##    ##  ##       ##
########  ########      ##     ## ######## ##

--}}

            @if ($listeChangement != false)

                <x-modal name="listeChangement-modal" id="listeChangement-modal" title="Liste des changements" show
                    maxWidth="5xl">
                    <div class="p-2 text-gray-700 dark:text-gray-300">
                        <a x-on:click="$dispatch('close')">
                            <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                        </a>
                        <div class="p-6 ">
                            <div class="flex flex-col gap-4">
                                <h2
                                    class="text-xl font-bold mb-6 text-left border-b-2 border-gray-200 dark:border-gray-700 p-2">
                                    Ces références ont changé, voulez-vous les enregistrer ou les garder seulement pour
                                    cette commande ?</h2>
                                <table class="min-w-0 bg-gray-100 dark:bg-gray-900 ">
                                    <thead>
                                        <tr>
                                            <th class="text-left">Ref Interne</th>
                                            <th class="text-left">Désignation</th>
                                            <th class="text-left">Changement</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($listeChangement as $changement)
                                            <tr>
                                                <td class="p-2 text-left">{{ $changement['ref_interne'] }}</td>
                                                <td class="p-2 text-left">{{ $changement['designation'] }}</td>
                                                <td class="p-2 text-left flex items-center">
                                                    <span title="Ancienne référence">{!! $changement['ref_externe'] ?? '<span class="italic text-gray-500">Non définie</span>' !!}</span>
                                                    <x-icon size="1" type="arrow_forward"
                                                        class="icons-no_hover" />
                                                    <span
                                                        title="Nouvelle référence">{{ $changement['ref_fournisseur'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="flex justify-end mt-4">
                                    <x-toggle :checked="old('enregistrer_changement', true)" :label="'Enregistrer les changements ?'" id="enregistrer_changement"
                                        name="enregistrer_changement" class="toggle-class" />
                                    @error('enregistrer_changement')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex justify-end mt-4">
                                <button type="button" x-on:click="$dispatch('close')"
                                    class="btn">{{ __('Fermer') }}</button>
                            </div>
                        </div>
                </x-modal>
            @endif
        </form>
    </div>






    <script>
        function recalculateTotal() {
            const totalHtElement = document.getElementById('total_ht');
            const tvatext = document.getElementById('tva_container');
            const tvaElement = document.getElementById('total_tva_plus');
            const totalTtcElement = document.getElementById('total_ttc');
            const tvaInput = document.querySelector('input[name="tva"]');
            const frais_de_portInput = document.querySelector('input[name="frais_de_port"]');
            const frais_diversInput = document.querySelector('input[name="frais_divers"]');
            const total_ht_grayElement = document.getElementById('total_ht_gray');
            const frais_de_portElement = document.getElementById('frais_de_port');
            const frais_diversElement = document.getElementById('frais_divers');
            const total_ht_gray = parseFloat(@json($cde->total_ht)) || 0;
            const frais_de_port = parseFloat(frais_de_portInput.value) || 0;
            const frais_divers = parseFloat(frais_diversInput.value) || 0;
            const totalHt = parseFloat(@json($cde->total_ht)) + frais_de_port + frais_divers;
            const tva = parseFloat(tvaInput.value) || 0;
            const tvaAmount = (totalHt * tva / 100);
            const totalTtc = totalHt + tvaAmount;
            if (frais_de_port > 0) {
                frais_de_portElement.parentElement.classList.remove('hidden');
            } else {
                frais_de_portElement.parentElement.classList.add('hidden');
            }
            if (frais_divers > 0) {
                frais_diversElement.parentElement.classList.remove('hidden');
            } else {
                frais_diversElement.parentElement.classList.add('hidden');
            }
            frais_de_portElement.textContent = frais_de_port.toFixed(2) + ' €';
            frais_diversElement.textContent = frais_divers.toFixed(2) + ' €';
            total_ht_grayElement.textContent = total_ht_gray.toFixed(2) + ' €';
            tvatext.textContent = 'TVA (' + tva + '%) :';
            tvaElement.textContent = tvaAmount.toFixed(2) + ' €';
            totalHtElement.textContent = totalHt.toFixed(2) + ' €';
            totalTtcElement.textContent = totalTtc.toFixed(2) + ' €';
        };

        function changeTypeExpedition(select) {
            const adresse_livraison = document.getElementById('adresse_livraison');
            const typeExpedition = select.value;
            if (typeExpedition == 1) {
                adresse_livraison.classList.remove('hidden');
            } else {
                adresse_livraison.classList.add('hidden');
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            const typeExpedition = document.querySelector('select[name="type_expedition_id"]');
            const customNoteTextarea = document.querySelector('textarea[name="custom_note"]');
            const saveCustomNoteToggleParent = document.getElementById('save_custom_note_div');

            if (customNoteTextarea.value.trim() !== '') {
                saveCustomNoteToggleParent.classList.remove('hidden');
            }

            customNoteTextarea.addEventListener('input', function() {
                if (customNoteTextarea.value.trim() !== '') {
                    saveCustomNoteToggleParent.classList.remove('hidden');
                } else {
                    saveCustomNoteToggleParent.classList.add('hidden');
                }
            });
            changeTypeExpedition(typeExpedition);
            changeConditionPaiement();
        });
    </script>


    {{-- Modal pour création rapide d'affaire --}}
    <x-modal name="create-affaire-modal" :show="false" maxWidth="lg">
        <div class="p-4">
            <a x-on:click="$dispatch('close')">
                <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
            </a>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Nouvelle affaire</h2>
            <div id="create-affaire-modal-body">
                <div id="loading-spinner"
                    class="m-6 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-32 w-full">
                    <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div>
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
            </div>
        </div>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('open-modal', function(e) {
                if (e.detail === 'create-affaire-modal') {
                    const modalBody = document.getElementById('create-affaire-modal-body');
                    modalBody.innerHTML = document.getElementById('loading-spinner').outerHTML;
                    fetch("{{ route('affaires.create') }}")
                        .then(response => response.text())
                        .then(html => {
                            modalBody.innerHTML = html;
                            attachCreateFormListener();
                        });
                }
            });
        });

        function attachCreateFormListener() {
            const form = document.getElementById('create-affaire-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.affaire) {
                                // Ajoute la nouvelle affaire au select
                                const select = document.getElementById('affaire_id');
                                const option = document.createElement('option');
                                option.value = data.affaire.id;
                                option.text = data.affaire.code + ' — ' + data.affaire.nom;
                                option.selected = true;
                                select.appendChild(option);
                                window.dispatchEvent(new CustomEvent('close-modal', {
                                    detail: 'create-affaire-modal'
                                }));
                                select.focus();
                                showFlashMessageFromJs(
                                    'Affaire créée avec succès : ' + data.affaire.code + ' — ' + data
                                    .affaire.nom, 3000, 'success')
                            } else if (data.errors) {
                                let errorHtml = '<div class="text-red-500 mb-2">';
                                for (const key in data.errors) {
                                    errorHtml += data.errors[key].join('<br>') + '<br>';
                                }
                                errorHtml += '</div>';
                                form.insertAdjacentHTML('afterbegin', errorHtml);
                                showFlashMessageFromJs(
                                    'Une erreur est survenue.', 3000, 'error');
                            }
                        });
                });
            }
        }
    </script>
</x-app-layout>
