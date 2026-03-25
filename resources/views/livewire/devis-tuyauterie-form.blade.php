<div class="space-y-8">

    <!-- 1. En-tête Affaire -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border-l-4 border-blue-500">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Informations du Chantier
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Sélection de l'affaire (TEMPORAIREMENT OPTIONNEL) -->
            <div class="md:col-span-2">
                <x-input-label for="affaire_id" :value="__('Affaire liée')" help="Sélectionner l'affaire à laquelle ce devis est rattaché" />
                <select id="affaire_id" wire:model="affaire_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">-- Sélectionner une affaire (optionnel) --</option>
                    @foreach($affaires as $affaire)
                        <option value="{{ $affaire->id }}">{{ $affaire->code }} - {{ $affaire->nom }}</option>
                    @endforeach
                </select>
                @error('affaire_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-input-label for="reference_projet" :value="__('Référence Projet / Nom Chantier')" help="Nom interne du projet pour le suivi. Ex: Remplacement Vapeur Ligne 4" />
                <x-text-input id="reference_projet" type="text" wire:model="reference_projet" placeholder="Ex: Remplacement Vapeur Ligne 4" class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label for="lieu_intervention" :value="__('Lieu d\'intervention (Précis)')" help="Localisation exacte (Bâtiment, Étage, Zone). Important pour l'accès et la logistique." />
                <x-text-input id="lieu_intervention" type="text" wire:model="lieu_intervention" placeholder="Ex: Usine Nord, Atelier Méca, Niv +2" class="mt-1 block w-full" />
                <p class="text-xs text-gray-500 mt-1">Influe sur les frais de déplacement et accès.</p>
            </div>

            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="societe_id" :value="__('Société')" help="Client à facturer." />
                    <div class="space-y-2">
                        <select id="societe_id" wire:model.live="societe_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white mb-2">
                            <option value="">-- Sélectionner --</option>
                            @foreach($societes as $societe)
                                <option value="{{ $societe->id }}">{{ $societe->raison_sociale }}</option>
                            @endforeach
                        </select>
                        <x-text-input type="text" wire:model="client_nom" placeholder="Nom Client (Affiché)" class="mt-1 block w-full" />
                    </div>
                </div>
                <div>
                    <x-input-label for="societe_contact_id" :value="__('Contact Technique')" help="Interlocuteur principal pour ce devis." />
                    <div class="space-y-2">
                        @if($societe_id)
                            <select id="societe_contact_id" wire:model.live="societe_contact_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white mb-2">
                                <option value="">-- Sélectionner contact --</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact['id'] }}">{{ $contact['nom'] }}</option>
                                @endforeach
                            </select>
                        @endif
                        <x-text-input type="text" wire:model="client_contact" placeholder="Nom Contact" class="mt-1 block w-full" />
                    </div>
                </div>
                <div>
                    <x-input-label for="client_adresse" :value="__('Adresse Facturation')" help="Adresse complète qui apparaîtra sur le devis final." />
                    <textarea id="client_adresse" wire:model="client_adresse" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="date_emission" :value="__('Date d\'émission')" help="Date affichée sur le devis." />
                    <x-text-input id="date_emission" type="date" wire:model="date_emission" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="duree_validite" :value="__('Validité (Jours)')" help="Durée de validité de l'offre. Attention aux variations du cours des matières." />
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <x-text-input id="duree_validite" type="number" wire:model="duree_validite" class="block w-full pr-12" />
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-gray-500 sm:text-sm">Jours</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Corps du Devis -->
    <div class="flex flex-col gap-8">
        @foreach($sections as $index => $section)
            <div wire:key="section-{{ $index }}" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700 relative">
                <button wire:click="removeSection({{ $index }})" class="absolute top-4 right-4 text-red-500 hover:text-red-700" title="Supprimer la section">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>

                <div class="mb-4">
                    <x-input-label :value="__('Intitulé de la Zone / Lot')" help="Divisez votre devis en sous-ensembles (lots, zones, phases) pour une meilleure lecture client." />
                    <x-text-input type="text" wire:model="sections.{{ $index }}.titre" class="mt-1 text-lg font-bold block w-full border-none border-b-2 border-gray-300 focus:border-blue-500 focus:ring-0 dark:bg-gray-800 dark:text-white px-0" placeholder="Ex: Zone 1 - Préfabrication" />
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase" title="Type de dépense (Matériel, MO, etc.)">Type <sup class="text-blue-500 cursor-help">?</sup></th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-1/3" title="Description détaillée pour le client">Désignation</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase" title="Nuance matière ou norme applicable">Matière/Norme</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase bg-green-50 dark:bg-green-900/20" title="Quantité de matière pour 1 élément">Qté Mat./U <sup class="text-green-500 cursor-help">?</sup></th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase w-20">Qté</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase w-24">Unité</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase w-24" title="Prix de revient interne (visible seulement par vous)">Prix Achat (Caché) <sup class="text-blue-500 cursor-help">?</sup></th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase w-24" title="Prix vendu au client">P.U. Vente</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase w-24">Total HT</th>
                                <th class="px-3 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($section['lignes'] as $lineIndex => $line)
                                <tr>
                                    <td class="px-2 py-2">
                                        <select wire:model="sections.{{ $index }}.lignes.{{ $lineIndex }}.type" class="block w-full text-xs rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                                            <option value="fourniture">Matériel</option>
                                            <option value="main_d_oeuvre">Main d'œuvre</option>
                                            <option value="sous_traitance">Sous-traitance</option>
                                            <option value="consommable">Consommable</option>
                                        </select>
                                    </td>
                                    <td class="px-2 py-2">
                                        <div class="relative">
                                            <input
                                                type="text"
                                                wire:model.lazy="sections.{{ $index }}.lignes.{{ $lineIndex }}.designation"
                                                placeholder="Desc. Technique"
                                                class="block w-full text-sm rounded-md border-gray-300 dark:bg-gray-700 dark:text-white"
                                                list="designations-list-{{ $index }}-{{ $lineIndex }}"
                                                autocomplete="off">

                                            <datalist id="designations-list-{{ $index }}-{{ $lineIndex }}">
                                                @foreach($designations_standards as $designation)
                                                    <option value="{{ $designation }}">
                                                @endforeach
                                            </datalist>

                                            <button
                                                type="button"
                                                onclick="document.getElementById('modal-designations-{{ $index }}-{{ $lineIndex }}').classList.remove('hidden')"
                                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                                title="Voir toutes les désignations">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Modal de recherche des désignations -->
                                        <div id="modal-designations-{{ $index }}-{{ $lineIndex }}" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                                                <div class="flex justify-between items-center mb-4">
                                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Désignations standards</h3>
                                                    <button
                                                        type="button"
                                                        onclick="document.getElementById('modal-designations-{{ $index }}-{{ $lineIndex }}').classList.add('hidden')"
                                                        class="text-gray-400 hover:text-gray-500">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <input
                                                    type="text"
                                                    id="search-designation-{{ $index }}-{{ $lineIndex }}"
                                                    placeholder="🔍 Rechercher..."
                                                    class="w-full mb-3 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                                    onkeyup="filterDesignations({{ $index }}, {{ $lineIndex }})">

                                                <div class="max-h-96 overflow-y-auto">
                                                    <div id="designations-container-{{ $index }}-{{ $lineIndex }}" class="space-y-1">
                                                        @foreach($designations_standards as $designation)
                                                            <button
                                                                type="button"
                                                                class="designation-item w-full text-left px-3 py-2 text-sm hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded cursor-pointer text-gray-700 dark:text-gray-300"
                                                                onclick="selectDesignation({{ $index }}, {{ $lineIndex }}, '{{ addslashes($designation) }}')">
                                                                {{ $designation }}
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($line['type'] === 'fourniture')
                                            <div class="mt-1">
                                                <select wire:change="selectMatiere({{ $index }}, {{ $lineIndex }}, $event.target.value)" class="block w-full text-xs rounded-md border-gray-300 dark:bg-gray-700 dark:text-white bg-blue-50 dark:bg-blue-900/20">
                                                    <option value="">🔍 Sélectionner une matière répertoriée...</option>
                                                    @foreach($matieres as $matiere)
                                                        <option value="{{ $matiere->id }}">
                                                            {{ $matiere->ref_interne }} - {{ $matiere->designation }}
                                                            @if($matiere->material) ({{ $matiere->material->nom }}) @endif
                                                            - Stock: {{ number_format($matiere->quantite(), 2) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-2 py-2">
                                        <input type="text" wire:model.lazy="sections.{{ $index }}.lignes.{{ $lineIndex }}.matiere" placeholder="Ex: 316L, ISO" class="block w-full text-sm rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                                    </td>
                                    <td class="px-2 py-2 bg-green-50 dark:bg-green-900/20">
                                        @if($line['type'] === 'fourniture')
                                            <div class="flex gap-1">
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    wire:model.lazy="sections.{{ $index }}.lignes.{{ $lineIndex }}.quantite_matiere_unitaire"
                                                    class="block w-20 text-xs rounded-md border-gray-300 dark:bg-gray-700 dark:text-white text-right bg-green-50 dark:bg-green-900/30"
                                                    placeholder="0"
                                                    title="Quantité de matière pour fabriquer 1 élément">
                                                <select
                                                    wire:model="sections.{{ $index }}.lignes.{{ $lineIndex }}.unite_matiere"
                                                    class="block w-16 text-xs rounded-md border-gray-300 dark:bg-gray-700 dark:text-white bg-green-50 dark:bg-green-900/30"
                                                    title="Unité de la matière">
                                                    <option value="ml">ml</option>
                                                    <option value="m">m</option>
                                                    <option value="kg">kg</option>
                                                    <option value="g">g</option>
                                                    <option value="u">u</option>
                                                    <option value="l">l</option>
                                                </select>
                                            </div>
                                            @if(isset($line['quantite_matiere_unitaire']) && $line['quantite_matiere_unitaire'] > 0 && $line['quantite'] > 0)
                                                <div class="text-xs text-green-600 dark:text-green-400 mt-1 font-semibold">
                                                    = {{ number_format($line['quantite_matiere_unitaire'] * $line['quantite'], 2) }} {{ $line['unite_matiere'] ?? 'ml' }} total
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-2 py-2">
                                        <input type="number" step="0.01" wire:model.live.debounce.500ms="sections.{{ $index }}.lignes.{{ $lineIndex }}.quantite" class="block w-full text-sm rounded-md border-gray-300 dark:bg-gray-700 dark:text-white text-right">
                                    </td>
                                    <td class="px-2 py-2">
                                        <select wire:model="sections.{{ $index }}.lignes.{{ $lineIndex }}.unite" class="block w-full text-xs rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                                            <option value="u">U</option>
                                            <option value="ml">ml</option>
                                            <option value="h">h</option>
                                            <option value="f">Forfait</option>
                                            <option value="ens">Ens</option>
                                        </select>
                                    </td>
                                    <td class="px-2 py-2">
                                        <input type="number" step="0.01" wire:model.live.debounce.500ms="sections.{{ $index }}.lignes.{{ $lineIndex }}.prix_achat" class="block w-full text-sm rounded-md border-gray-300 dark:bg-gray-700 dark:text-white text-right" placeholder="0.00">
                                    </td>
                                    <td class="px-2 py-2">
                                        <input type="number" step="0.01" wire:model.live.debounce.500ms="sections.{{ $index }}.lignes.{{ $lineIndex }}.prix_unitaire" class="block w-full text-sm rounded-md border-gray-300 dark:bg-gray-700 dark:text-white text-right font-bold">
                                    </td>
                                    <td class="px-2 py-2 text-right font-mono text-sm text-gray-900 dark:text-white">
                                        {{ number_format($line['total_ht'], 2) }} €
                                    </td>
                                    <td class="px-2 py-2 text-center">
                                        <button wire:click="removeLine({{ $index }}, {{ $lineIndex }})" class="text-gray-400 hover:text-red-500">
                                            &times;
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <button wire:click="addLine({{ $index }})" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajouter une ligne
                    </button>
                </div>
            </div>
        @endforeach

        <button wire:click="addSection" class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-blue-500 hover:text-blue-500 transition-colors flex justify-center items-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Ajouter une nouvelle Zone / Lot
        </button>
    </div>

    <!-- 3. Options Métier -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Options & Spécificités Tuyauterie</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Essais -->
            <div class="space-y-3">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 text-sm uppercase border-b pb-1 mb-2">Essais & CND</h4>
                <label class="flex items-center group">
                    <input type="checkbox" wire:model="options.essais_hydrauliques" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200">Épreuves Hydrauliques</span>
                    <x-help-icon text="Mise en pression pour vérifier l'étanchéité." />
                </label>
                <label class="flex items-center group">
                    <input type="checkbox" wire:model="options.ressuage" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200">Ressuage (PT)</span>
                    <x-help-icon text="Contrôle de surface des soudures par pénétrant coloré." />
                </label>
                <label class="flex items-center group">
                    <input type="checkbox" wire:model="options.radiographie" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200">Radiographie (RT)</span>
                    <x-help-icon text="Contrôle volumétrique des soudures (Rayons X/Gamma)." />
                </label>
            </div>

            <!-- Documents -->
            <div class="space-y-3">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 text-sm uppercase border-b pb-1 mb-2">Documentation Technique</h4>
                <label class="flex items-center group">
                    <input type="checkbox" wire:model="options.dossier_fin_travaux" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200">Dossier Fin de Travaux (DFT)</span>
                    <x-help-icon text="Compilation de tous les documents techniques finaux." />
                </label>
                <label class="flex items-center group">
                    <input type="checkbox" wire:model="options.cahier_soudage" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200">Cahier de Soudage</span>
                    <x-help-icon text="Inclut les DMOS, QMOS et qualifications des soudeurs." />
                </label>
                <label class="flex items-center group">
                    <input type="checkbox" wire:model="options.certificats_matiere" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200">Certificats Matières (3.1)</span>
                    <x-help-icon text="Traçabilité des matériaux utilisés (CCPU)." />
                </label>
            </div>

            <!-- Logistique -->
            <div class="space-y-3">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300 text-sm uppercase border-b pb-1 mb-2">Moyens Spécifiques</h4>
                <div class="flex flex-col gap-2">
                    <label class="flex items-center group">
                        <input type="checkbox" wire:model="options.nacelle" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200">Nacelle / PEMP</span>
                        <x-help-icon text="Nécessaire pour travaux en hauteur (sans échafaudage)." />
                    </label>
                    <label class="flex items-center group">
                        <input type="checkbox" wire:model="options.echafaudage" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200">Échafaudage</span>
                        <x-help-icon text="Structure fixe pour accès complexe." />
                    </label>
                </div>
                <div class="mt-4">
                   <x-input-label for="frais_consommables_forfait" :value="__('Forfait Consom. (Gaz, etc.) €')" help="Estimation des coûts de gaz de soudage, disques, électrodes..." />
                   <x-text-input id="frais_consommables_forfait" type="number" wire:model.live.debounce.500ms="options.frais_consommables_forfait" class="mt-1 block w-full" />
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Pied de Page / Totaux Sticky -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow rounded-lg p-6 h-fit">
             <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Conditions Commerciales</h3>
             <div class="grid grid-cols-1 gap-4">
                 <div>
                     <x-input-label for="conditions_paiement" :value="__('Conditions de Paiement')" help="Ex: 30% à la commande, solde à réception - 30j fin de mois." />
                     <x-text-input id="conditions_paiement" type="text" wire:model="conditions_paiement" class="mt-1 block w-full" placeholder="Ex: 30 jours fin de mois" />
                 </div>
                 <div>
                     <x-input-label for="delais_execution" :value="__('Délai / Planning')" help="Estimation réaliste du délai d'intervention (semaines)." />
                     <x-text-input id="delais_execution" type="text" wire:model="delais_execution" class="mt-1 block w-full" placeholder="Ex: 4 à 6 semaines après commande" />
                 </div>
             </div>
        </div>

        <div class="bg-gray-50 dark:bg-gray-700 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-600">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6 border-b pb-2">Synthèse Financière</h3>

            <!-- Indicateur Marge (Interne) -->
            <div class="mb-6 p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded border border-yellow-200 dark:border-yellow-700">
                <p class="text-xs text-yellow-800 dark:text-yellow-200 font-bold uppercase">Indicateur Marge (Invisible Client)</p>
                <div class="flex justify-between items-end mt-1">
                    <span class="text-sm text-yellow-700 dark:text-yellow-300">{{ number_format($marge_pourcent, 1) }} %</span>
                    <span class="text-lg font-mono font-bold text-yellow-800 dark:text-yellow-100">{{ number_format($marge_globale, 2) }} €</span>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between text-gray-600 dark:text-gray-300">
                    <span>Total HT</span>
                    <span class="font-mono">{{ number_format($total_ht, 2) }} €</span>
                </div>
                <div class="flex justify-between text-gray-600 dark:text-gray-300 text-sm">
                    <span>TVA (20%)</span>
                    <span class="font-mono">{{ number_format($total_tva, 2) }} €</span>
                </div>
                <div class="pt-4 border-t border-gray-300 dark:border-gray-500 flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Net à payer</span>
                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400 font-mono">{{ number_format($total_ttc, 2) }} €</span>
                </div>
            </div>

            <div class="mt-8">
                <button wire:click="save" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded shadow transition transform hover:scale-105">
                    Enregistrer le Devis
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    // Fonction pour filtrer les désignations dans le modal
    function filterDesignations(sectionIndex, lineIndex) {
        const searchInput = document.getElementById(`search-designation-${sectionIndex}-${lineIndex}`);
        const searchTerm = searchInput.value.toLowerCase();
        const container = document.getElementById(`designations-container-${sectionIndex}-${lineIndex}`);
        const items = container.getElementsByClassName('designation-item');

        Array.from(items).forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Fonction pour sélectionner une désignation
    function selectDesignation(sectionIndex, lineIndex, designation) {
        // Utiliser Livewire pour mettre à jour la valeur
        @this.set(`sections.${sectionIndex}.lignes.${lineIndex}.designation`, designation);

        // Fermer le modal
        document.getElementById(`modal-designations-${sectionIndex}-${lineIndex}`).classList.add('hidden');

        // Réinitialiser la recherche
        const searchInput = document.getElementById(`search-designation-${sectionIndex}-${lineIndex}`);
        if (searchInput) {
            searchInput.value = '';
            filterDesignations(sectionIndex, lineIndex);
        }
    }

    // Fermer le modal en cliquant en dehors
    document.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('[id^="modal-designations-"]');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
