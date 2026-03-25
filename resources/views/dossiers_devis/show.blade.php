<x-app-layout>
    @section('title', 'Dossier de Devis - ' . $dossierDevis->code)
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $dossierDevis->code }} - {{ $dossierDevis->nom }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Statut : <span class="font-medium">{{ ucfirst($dossierDevis->statut) }}</span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('dossiers_devis.edit', $dossierDevis) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Modifier
                </a>
                <a href="{{ route('dossiers_devis.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Informations générales -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informations Générales</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @if($dossierDevis->affaire)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Affaire</p>
                            <a href="{{ route('affaires.show', $dossierDevis->affaire) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                {{ $dossierDevis->affaire->code }} - {{ $dossierDevis->affaire->nom }}
                            </a>
                        </div>
                    @endif

                    @if($dossierDevis->societe)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Client</p>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $dossierDevis->societe->nom }}</p>
                        </div>
                    @endif

                    @if($dossierDevis->reference_projet)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Référence Projet</p>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $dossierDevis->reference_projet }}</p>
                        </div>
                    @endif

                    @if($dossierDevis->lieu_intervention)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Lieu d'intervention</p>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $dossierDevis->lieu_intervention }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de création</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $dossierDevis->date_creation?->format('d/m/Y') }}</p>
                    </div>

                    @if($dossierDevis->createur)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Créé par</p>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $dossierDevis->createur->prenom }} {{ $dossierDevis->createur->nom }}</p>
                        </div>
                    @endif
                </div>

                @if($dossierDevis->description)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Description</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $dossierDevis->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Quantitatif -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Quantitatif des Besoins</h3>
                    <button x-data @click="$dispatch('open-modal', 'add-quantitatif-modal')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajouter un élément
                    </button>
                </div>

                @if($dossierDevis->quantitatifs->isEmpty())
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <p class="text-gray-500 dark:text-gray-400">Le quantitatif est vide. Commencez par ajouter les besoins du projet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catégorie</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Désignation</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Matière</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Référence</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantité</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Unité</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prix Achat</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prix Unit.</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($dossierDevis->quantitatifs as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $item->categorie ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $item->type == 'fourniture' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                {{ $item->type == 'main_d_oeuvre' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                {{ $item->type == 'sous_traitance' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                                {{ $item->type == 'consommable' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : '' }}
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $item->type ?? 'fourniture')) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->designation }}</td>
                                        <td class="px-4 py-3 text-sm text-blue-600 dark:text-blue-400">
                                            @if($item->matiere)
                                                <span class="inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    {{ Str::limit($item->matiere->designation, 30) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $item->reference ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-center font-semibold text-gray-900 dark:text-gray-100">{{ $item->quantite }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $item->unite }}</td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-gray-100">
                                            {{ $item->prix_achat ? number_format($item->prix_achat, 2, ',', ' ') . ' €' : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $item->prix_unitaire ? number_format($item->prix_unitaire, 2, ',', ' ') . ' €' : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm">
                                            <button x-data @click="
                                                $dispatch('open-modal', 'edit-quantitatif-modal-{{ $item->id }}')
                                            " class="text-amber-600 hover:text-amber-900 dark:text-amber-400 mr-3">
                                                Modifier
                                            </button>
                                            <form method="POST" action="{{ route('dossiers_devis.delete_quantitatif', $item) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Supprimer cet élément ?')" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal de modification pour chaque élément -->
                                    <x-modal name="edit-quantitatif-modal-{{ $item->id }}" :show="false" maxWidth="2xl">
                                        <form method="POST" action="{{ route('dossiers_devis.update_quantitatif', $item) }}" class="p-6" id="edit-quantitatif-form-{{ $item->id }}">
                                            @csrf
                                            @method('PATCH')
                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Modifier l'élément</h2>

                                            <div class="grid grid-cols-1 gap-4">
                                                <!-- Recherche de matière pour édition -->
                                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                                    <x-input-label for="matiere_search_edit_{{ $item->id }}" value="🔍 Changer de matière (optionnel)" class="font-semibold text-blue-900 dark:text-blue-100" />
                                                    <p class="text-xs text-blue-700 dark:text-blue-300 mb-2">Recherchez et sélectionnez une autre matière pour remplacer les informations actuelles.</p>
                                                    <input
                                                        type="text"
                                                        id="matiere_search_edit_{{ $item->id }}"
                                                        list="matieres-list-edit-{{ $item->id }}"
                                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring"
                                                        placeholder="Rechercher une matière..."
                                                        autocomplete="off"
                                                        value="{{ $item->matiere ? $item->matiere->designation : '' }}"
                                                    />
                                                    <input type="hidden" id="matiere_id_edit_{{ $item->id }}" name="matiere_id" value="{{ $item->matiere_id }}" />
                                                    <datalist id="matieres-list-edit-{{ $item->id }}">
                                                        @foreach($matieres as $matiere)
                                                            <option
                                                                value="{{ $matiere['designation'] }}"
                                                                data-id="{{ $matiere['id'] }}"
                                                                data-reference="{{ $matiere['ref_interne'] }}"
                                                                data-unite="{{ $matiere['unite'] }}"
                                                            >
                                                                {{ $matiere['ref_interne'] ? $matiere['ref_interne'] . ' - ' : '' }}{{ $matiere['designation'] }}
                                                            </option>
                                                        @endforeach
                                                    </datalist>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <x-input-label for="edit_categorie_{{ $item->id }}" value="Catégorie" />
                                                        <select id="edit_categorie_{{ $item->id }}" name="categorie" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                                            <option value="">-- Aucune --</option>
                                                            <option value="Fourniture" {{ $item->categorie == 'Fourniture' ? 'selected' : '' }}>Fourniture</option>
                                                            <option value="Main d'œuvre" {{ $item->categorie == "Main d'œuvre" ? 'selected' : '' }}>Main d'œuvre</option>
                                                            <option value="Matériel" {{ $item->categorie == 'Matériel' ? 'selected' : '' }}>Matériel</option>
                                                            <option value="Sous-traitance" {{ $item->categorie == 'Sous-traitance' ? 'selected' : '' }}>Sous-traitance</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <x-input-label for="edit_type_{{ $item->id }}" value="Type *" />
                                                        <select id="edit_type_{{ $item->id }}" name="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                                                            <option value="fourniture" {{ $item->type == 'fourniture' ? 'selected' : '' }}>Fourniture</option>
                                                            <option value="main_d_oeuvre" {{ $item->type == 'main_d_oeuvre' ? 'selected' : '' }}>Main d'œuvre</option>
                                                            <option value="sous_traitance" {{ $item->type == 'sous_traitance' ? 'selected' : '' }}>Sous-traitance</option>
                                                            <option value="consommable" {{ $item->type == 'consommable' ? 'selected' : '' }}>Consommable</option>
                                                        </select>
                                                    </div>

                                                    <div class="md:col-span-2">
                                                        <x-input-label for="edit_designation_{{ $item->id }}" value="Désignation *" />
                                                        <x-text-input id="edit_designation_{{ $item->id }}" name="designation" type="text" class="mt-1 block w-full" :value="$item->designation" required />
                                                    </div>

                                                    <div class="md:col-span-2">
                                                        <x-input-label for="edit_description_technique_{{ $item->id }}" value="Description technique" />
                                                        <div class="relative mt-1">
                                                            <input
                                                                id="edit_description_technique_{{ $item->id }}"
                                                                name="description_technique"
                                                                type="text"
                                                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring pr-10"
                                                                list="designations-list-{{ $item->id }}"
                                                                value="{{ $item->description_technique }}"
                                                                placeholder="Sélectionner une description technique..."
                                                                autocomplete="off">

                                                            <datalist id="designations-list-{{ $item->id }}">
                                                                @foreach($designations_standards as $designation)
                                                                    <option value="{{ $designation }}">
                                                                @endforeach
                                                            </datalist>

                                                            <button
                                                                type="button"
                                                                onclick="openDesignationModal('edit_{{ $item->id }}')"
                                                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                                                title="Voir toutes les descriptions techniques">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <x-input-label for="edit_reference_{{ $item->id }}" value="Référence" />
                                                        <x-text-input id="edit_reference_{{ $item->id }}" name="reference" type="text" class="mt-1 block w-full" :value="$item->reference" />
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-2">
                                                        <div>
                                                            <x-input-label for="edit_quantite_{{ $item->id }}" value="Quantité *" />
                                                            <x-text-input id="edit_quantite_{{ $item->id }}" name="quantite" type="number" step="0.01" class="mt-1 block w-full" :value="$item->quantite" required />
                                                        </div>
                                                        <div>
                                                            <x-input-label for="edit_unite_{{ $item->id }}" value="Unité *" />
                                                            <select id="edit_unite_{{ $item->id }}" name="unite" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                                                <option value="u" {{ $item->unite == 'u' ? 'selected' : '' }}>u (unité)</option>
                                                                <option value="m" {{ $item->unite == 'm' ? 'selected' : '' }}>m (mètre)</option>
                                                                <option value="m²" {{ $item->unite == 'm²' ? 'selected' : '' }}>m² (mètre carré)</option>
                                                                <option value="kg" {{ $item->unite == 'kg' ? 'selected' : '' }}>kg (kilogramme)</option>
                                                                <option value="h" {{ $item->unite == 'h' ? 'selected' : '' }}>h (heure)</option>
                                                                <option value="j" {{ $item->unite == 'j' ? 'selected' : '' }}>j (jour)</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-2">
                                                        <div>
                                                            <x-input-label for="edit_quantite_matiere_unitaire_{{ $item->id }}" value="Qté Matière Unitaire" />
                                                            <x-text-input id="edit_quantite_matiere_unitaire_{{ $item->id }}" name="quantite_matiere_unitaire" type="number" step="0.000001" class="mt-1 block w-full" :value="$item->quantite_matiere_unitaire" placeholder="Ex: 2.5" />
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Quantité de matière pour 1 élément</p>
                                                        </div>
                                                        <div>
                                                            <x-input-label for="edit_unite_matiere_{{ $item->id }}" value="Unité Matière" />
                                                            <x-text-input id="edit_unite_matiere_{{ $item->id }}" name="unite_matiere" type="text" class="mt-1 block w-full" :value="$item->unite_matiere" placeholder="Ex: ml, kg" />
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <x-input-label for="edit_prix_achat_{{ $item->id }}" value="Prix d'Achat (€)" />
                                                        <x-text-input id="edit_prix_achat_{{ $item->id }}" name="prix_achat" type="number" step="0.01" class="mt-1 block w-full" :value="$item->prix_achat" placeholder="0.00" />
                                                    </div>

                                                    <div>
                                                        <x-input-label for="edit_prix_unitaire_{{ $item->id }}" value="Prix Unitaire (€)" />
                                                        <x-text-input id="edit_prix_unitaire_{{ $item->id }}" name="prix_unitaire" type="number" step="0.01" class="mt-1 block w-full" :value="$item->prix_unitaire" placeholder="0.00" />
                                                    </div>

                                                    <div class="md:col-span-2">
                                                        <x-input-label for="edit_remarques_{{ $item->id }}" value="Remarques" />
                                                        <textarea id="edit_remarques_{{ $item->id }}" name="remarques" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">{{ $item->remarques }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-6 flex justify-end gap-3">
                                                <x-secondary-button type="button" x-on:click="$dispatch('close')">
                                                    Annuler
                                                </x-secondary-button>
                                                <x-primary-button>
                                                    Enregistrer
                                                </x-primary-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Devis générés -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Devis Générés</h3>
                    <a href="{{ route('dossiers_devis.preparer_devis', $dossierDevis) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 {{ $dossierDevis->quantitatifs->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $dossierDevis->quantitatifs->isEmpty() ? 'onclick="event.preventDefault();"' : '' }}>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Générer un Devis
                    </a>
                </div>

                @if($dossierDevis->devisTuyauteries->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">Aucun devis généré à partir de ce dossier.</p>
                @else
                    <div class="space-y-2">
                        @foreach($dossierDevis->devisTuyauteries as $devis)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $devis->reference_projet ?? "Devis #".$devis->id }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Créé le {{ $devis->created_at->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('devis_tuyauterie.show', $devis) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm font-medium">
                                            Voir →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal d'ajout d'élément quantitatif -->
    <x-modal name="add-quantitatif-modal" :show="false" maxWidth="2xl">
        <form method="POST" action="{{ route('dossiers_devis.ajouter_quantitatif', $dossierDevis) }}" class="p-6" id="add-quantitatif-form">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ajouter un élément au quantitatif</h2>

            <div class="grid grid-cols-1 gap-4">
                <!-- Recherche de matière -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <x-input-label for="matiere_search" value="🔍 Rechercher une matière (optionnel)" class="font-semibold text-blue-900 dark:text-blue-100" />
                    <p class="text-xs text-blue-700 dark:text-blue-300 mb-2">Tapez pour rechercher et sélectionner une matière. Les champs seront automatiquement remplis.</p>
                    <input
                        type="text"
                        id="matiere_search"
                        list="matieres-list"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring"
                        placeholder="Rechercher une matière..."
                        autocomplete="off"
                    />
                    <input type="hidden" id="matiere_id" name="matiere_id" />
                    <datalist id="matieres-list">
                        @foreach($matieres as $matiere)
                            <option
                                value="{{ $matiere['designation'] }}"
                                data-id="{{ $matiere['id'] }}"
                                data-reference="{{ $matiere['ref_interne'] }}"
                                data-unite="{{ $matiere['unite'] }}"
                            >
                                {{ $matiere['ref_interne'] ? $matiere['ref_interne'] . ' - ' : '' }}{{ $matiere['designation'] }}
                            </option>
                        @endforeach
                    </datalist>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="categorie" value="Catégorie" />
                            <select id="categorie" name="categorie" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring">
                                <option value="">-- Aucune --</option>
                                <option value="Fourniture">Fourniture</option>
                                <option value="Main d'œuvre">Main d'œuvre</option>
                                <option value="Matériel">Matériel</option>
                                <option value="Sous-traitance">Sous-traitance</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="type" value="Type *" />
                            <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring" required>
                                <option value="fourniture">Fourniture</option>
                                <option value="main_d_oeuvre">Main d'œuvre</option>
                                <option value="sous_traitance">Sous-traitance</option>
                                <option value="consommable">Consommable</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="designation" value="Désignation *" />
                            <x-text-input id="designation" name="designation" type="text" class="mt-1 block w-full" required />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="description_technique" value="Description technique" />
                            <div class="relative mt-1">
                                <input
                                    id="description_technique"
                                    name="description_technique"
                                    type="text"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring pr-10"
                                    list="designations-list-add"
                                    placeholder="Sélectionner une description technique..."
                                    autocomplete="off">

                                <datalist id="designations-list-add">
                                    @foreach($designations_standards as $designation)
                                        <option value="{{ $designation }}">
                                    @endforeach
                                </datalist>

                                <button
                                    type="button"
                                    onclick="openDesignationModal('add')"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                    title="Voir toutes les descriptions techniques">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="reference" value="Référence" />
                            <x-text-input id="reference" name="reference" type="text" class="mt-1 block w-full" />
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <x-input-label for="quantite" value="Quantité *" />
                                <x-text-input id="quantite" name="quantite" type="number" step="0.01" class="mt-1 block w-full" required />
                            </div>
                            <div>
                                <x-input-label for="unite" value="Unité *" />
                                <select id="unite" name="unite" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring">
                                    <option value="u">u (unité)</option>
                                    <option value="m">m (mètre)</option>
                                    <option value="m²">m² (mètre carré)</option>
                                    <option value="kg">kg (kilogramme)</option>
                                    <option value="h">h (heure)</option>
                                    <option value="j">j (jour)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <x-input-label for="quantite_matiere_unitaire" value="Qté Matière Unitaire" />
                                <x-text-input id="quantite_matiere_unitaire" name="quantite_matiere_unitaire" type="number" step="0.000001" class="mt-1 block w-full" placeholder="Ex: 2.5" />
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Quantité de matière pour 1 élément</p>
                            </div>
                            <div>
                                <x-input-label for="unite_matiere" value="Unité Matière" />
                                <x-text-input id="unite_matiere" name="unite_matiere" type="text" class="mt-1 block w-full" placeholder="Ex: ml, kg" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="prix_achat" value="Prix d'Achat (€)" />
                            <x-text-input id="prix_achat" name="prix_achat" type="number" step="0.01" class="mt-1 block w-full" placeholder="0.00" />
                        </div>

                        <div>
                            <x-input-label for="prix_unitaire" value="Prix Unitaire (€)" />
                            <x-text-input id="prix_unitaire" name="prix_unitaire" type="number" step="0.01" class="mt-1 block w-full" placeholder="0.00" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="remarques" value="Remarques" />
                            <textarea id="remarques" name="remarques" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">
                    Annuler
                </x-secondary-button>
                <x-primary-button>
                    Ajouter
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Script pour l'auto-remplissage depuis la matière -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction générique pour gérer l'auto-remplissage
            function setupMatiereAutofill(searchInputId, datalistId, matiereIdInputId, designationInputId, referenceInputId, uniteSelectId, categorieSelectId) {
                const searchInput = document.getElementById(searchInputId);
                const datalist = document.getElementById(datalistId);

                if (!searchInput || !datalist) return;

                searchInput.addEventListener('input', function(e) {
                    const selectedValue = e.target.value;

                    // Rechercher la matière correspondante dans le datalist
                    const options = datalist.querySelectorAll('option');
                    let selectedOption = null;

                    options.forEach(option => {
                        if (option.value === selectedValue) {
                            selectedOption = option;
                        }
                    });

                    if (selectedOption) {
                        // Remplir automatiquement les champs
                        const matiereIdInput = document.getElementById(matiereIdInputId);
                        const designationInput = document.getElementById(designationInputId);
                        const referenceInput = document.getElementById(referenceInputId);
                        const uniteSelect = document.getElementById(uniteSelectId);
                        const categorieSelect = document.getElementById(categorieSelectId);

                        if (matiereIdInput) matiereIdInput.value = selectedOption.dataset.id;
                        if (designationInput) designationInput.value = selectedOption.value;
                        if (referenceInput) referenceInput.value = selectedOption.dataset.reference || '';

                        // Sélectionner l'unité correspondante
                        if (uniteSelect) {
                            const uniteValue = selectedOption.dataset.unite;

                            // Essayer de trouver l'unité exacte ou l'ajouter si nécessaire
                            let unitFound = false;
                            for (let i = 0; i < uniteSelect.options.length; i++) {
                                if (uniteSelect.options[i].value === uniteValue) {
                                    uniteSelect.selectedIndex = i;
                                    unitFound = true;
                                    break;
                                }
                            }

                            // Si l'unité n'est pas dans la liste, l'ajouter
                            if (!unitFound && uniteValue) {
                                const newOption = new Option(uniteValue, uniteValue, true, true);
                                uniteSelect.add(newOption);
                            }
                        }

                        // Mettre la catégorie à "Fourniture" par défaut pour une matière
                        if (categorieSelect) {
                            categorieSelect.value = 'Fourniture';
                        }
                    }
                });
            }

            // Configuration pour le modal d'ajout
            setupMatiereAutofill(
                'matiere_search',
                'matieres-list',
                'matiere_id',
                'designation',
                'reference',
                'unite',
                'categorie'
            );

            // Configuration pour les modals d'édition
            @foreach($dossierDevis->quantitatifs as $item)
                setupMatiereAutofill(
                    'matiere_search_edit_{{ $item->id }}',
                    'matieres-list-edit-{{ $item->id }}',
                    'matiere_id_edit_{{ $item->id }}',
                    'edit_designation_{{ $item->id }}',
                    'edit_reference_{{ $item->id }}',
                    'edit_unite_{{ $item->id }}',
                    'edit_categorie_{{ $item->id }}'
                );
            @endforeach

            // Réinitialiser le formulaire d'ajout quand le modal se ferme
            document.addEventListener('close', function() {
                const form = document.getElementById('add-quantitatif-form');
                if (form) {
                    form.reset();
                    const matiereIdInput = document.getElementById('matiere_id');
                    if (matiereIdInput) matiereIdInput.value = '';
                }
            });
        });

        // === Gestion du modal de descriptions techniques ===
        let currentDescriptionField = null;

        function openDesignationModal(fieldId) {
            currentDescriptionField = fieldId;
            document.getElementById('modal-designations').classList.remove('hidden');
            document.getElementById('search-designation').value = '';
            filterDesignations();
        }

        function closeDesignationModal() {
            document.getElementById('modal-designations').classList.add('hidden');
            currentDescriptionField = null;
        }

        function filterDesignations() {
            const searchInput = document.getElementById('search-designation');
            const searchTerm = searchInput.value.toLowerCase();
            const container = document.getElementById('designations-container');
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

        function selectDesignation(designation) {
            if (currentDescriptionField) {
                const fieldId = currentDescriptionField === 'add'
                    ? 'description_technique'
                    : 'edit_description_technique_' + currentDescriptionField.replace('edit_', '');
                const field = document.getElementById(fieldId);
                if (field) {
                    field.value = designation;
                }
            }
            closeDesignationModal();
        }

        // Fermer le modal en cliquant en dehors
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('modal-designations');
            if (event.target === modal) {
                closeDesignationModal();
            }
        });
    </script>

    <!-- Modal global de recherche des descriptions techniques -->
    <div id="modal-designations" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Descriptions techniques standards</h3>
                <button
                    type="button"
                    onclick="closeDesignationModal()"
                    class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <input
                type="text"
                id="search-designation"
                placeholder="🔍 Rechercher..."
                class="w-full mb-3 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                onkeyup="filterDesignations()">

            <div class="max-h-96 overflow-y-auto">
                <div id="designations-container" class="space-y-1">
                    @foreach($designations_standards as $designation)
                        <button
                            type="button"
                            class="designation-item w-full text-left px-3 py-2 text-sm hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded cursor-pointer text-gray-700 dark:text-gray-300"
                            onclick="selectDesignation('{{ addslashes($designation) }}')">
                            {{ $designation }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

