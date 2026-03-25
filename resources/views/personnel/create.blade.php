<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('administration.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Administration') !!}</a>
                >>
                <a href="{{ route('personnel.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{{ __('Personnel') }}</a>
                >>
            </h2>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Ajouter un employé') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <form method="POST" action="{{ route('personnel.store') }}" class="space-y-6">
                        @csrf

                        <!-- Informations de base -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informations de base</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Matricule -->
                                <div>
                                    <x-input-label for="matricule" :value="__('Matricule')" />
                                    <x-text-input id="matricule" class="block mt-1 w-full" type="text" name="matricule"
                                        :value="old('matricule')" required autofocus />
                                    <x-input-error :messages="$errors->get('matricule')" class="mt-2" />
                                </div>

                                <!-- Statut -->
                                <div>
                                    <x-input-label for="statut" :value="__('Statut')" />
                                    <select id="statut" name="statut"
                                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs"
                                        required>
                                        <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                                        <option value="en_conge" disabled>En congé (géré automatiquement)</option>
                                        <option value="suspendu" {{ old('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                        <option value="parti" {{ old('statut') == 'parti' ? 'selected' : '' }}>Parti</option>
                                    </select>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Le statut "En congé" est automatiquement défini selon les congés validés</p>
                                    <x-input-error :messages="$errors->get('statut')" class="mt-2" />
                                </div>

                                <!-- Nom -->
                                <div>
                                    <x-input-label for="nom" :value="__('Nom')" />
                                    <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom"
                                        :value="old('nom')" required />
                                    <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                                </div>

                                <!-- Prénom -->
                                <div>
                                    <x-input-label for="prenom" :value="__('Prénom')" />
                                    <x-text-input id="prenom" class="block mt-1 w-full" type="text" name="prenom"
                                        :value="old('prenom')" required />
                                    <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        :value="old('email')" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Téléphone -->
                                <div>
                                    <x-input-label for="telephone" :value="__('Téléphone')" />
                                    <x-text-input id="telephone" class="block mt-1 w-full" type="text" name="telephone"
                                        :value="old('telephone')" />
                                    <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                                </div>

                                <!-- Téléphone mobile -->
                                <div>
                                    <x-input-label for="telephone_mobile" :value="__('Téléphone mobile')" />
                                    <x-text-input id="telephone_mobile" class="block mt-1 w-full" type="text"
                                        name="telephone_mobile" :value="old('telephone_mobile')" />
                                    <x-input-error :messages="$errors->get('telephone_mobile')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Informations professionnelles -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informations professionnelles</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Poste -->
                                <div>
                                    <x-input-label for="poste" :value="__('Poste')" />
                                    <x-text-input id="poste" class="block mt-1 w-full" type="text" name="poste"
                                        :value="old('poste')" />
                                    <x-input-error :messages="$errors->get('poste')" class="mt-2" />
                                </div>

                                <!-- Département -->
                                <div>
                                    <x-input-label for="departement" :value="__('Département')" />
                                    <x-text-input id="departement" class="block mt-1 w-full" type="text" name="departement"
                                        :value="old('departement')" />
                                    <x-input-error :messages="$errors->get('departement')" class="mt-2" />
                                </div>

                                <!-- Date d'embauche -->
                                <div>
                                    <x-input-label for="date_embauche" :value="__('Date d\'embauche')" />
                                    <x-text-input id="date_embauche" class="block mt-1 w-full" type="date"
                                        name="date_embauche" :value="old('date_embauche')" />
                                    <x-input-error :messages="$errors->get('date_embauche')" class="mt-2" />
                                </div>

                                <!-- Date de départ -->
                                <div>
                                    <x-input-label for="date_depart" :value="__('Date de départ')" />
                                    <x-text-input id="date_depart" class="block mt-1 w-full" type="date"
                                        name="date_depart" :value="old('date_depart')" />
                                    <x-input-error :messages="$errors->get('date_depart')" class="mt-2" />
                                </div>

                                <!-- Raison du départ -->
                                <div id="raison_depart_container" style="display: {{ old('statut') == 'parti' ? 'block' : 'none' }};">
                                    <x-input-label for="raison_depart" :value="__('Raison du départ')" />
                                    <select id="raison_depart" name="raison_depart"
                                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs">
                                        <option value="">Sélectionner...</option>
                                        <option value="demission" {{ old('raison_depart') == 'demission' ? 'selected' : '' }}>Démission</option>
                                        <option value="licenciement" {{ old('raison_depart') == 'licenciement' ? 'selected' : '' }}>Licenciement</option>
                                        <option value="retraite" {{ old('raison_depart') == 'retraite' ? 'selected' : '' }}>Retraite</option>
                                        <option value="fin_contrat" {{ old('raison_depart') == 'fin_contrat' ? 'selected' : '' }}>Fin de contrat</option>
                                        <option value="mutation" {{ old('raison_depart') == 'mutation' ? 'selected' : '' }}>Mutation</option>
                                        <option value="autre" {{ old('raison_depart') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('raison_depart')" class="mt-2" />
                                </div>

                                <!-- Motif du départ -->
                                <div id="motif_depart_container" class="md:col-span-2" style="display: {{ old('statut') == 'parti' ? 'block' : 'none' }};">
                                    <x-input-label for="motif_depart" :value="__('Motif du départ (détails)')" />
                                    <textarea id="motif_depart" name="motif_depart" rows="3"
                                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs"
                                        placeholder="Détails sur le départ (obligatoire en cas de licenciement)">{{ old('motif_depart') }}</textarea>
                                    <x-input-error :messages="$errors->get('motif_depart')" class="mt-2" />
                                </div>

                                <!-- Salaire -->
                                <div>
                                    <x-input-label for="salaire" :value="__('Salaire (€)')" />
                                    <x-text-input id="salaire" class="block mt-1 w-full" type="number" step="0.01"
                                        name="salaire" :value="old('salaire')" />
                                    <x-input-error :messages="$errors->get('salaire')" class="mt-2" />
                                </div>

                                <!-- Numéro de sécurité sociale -->
                                <div>
                                    <x-input-label for="numero_securite_sociale" :value="__('Numéro de sécurité sociale')" />
                                    <x-text-input id="numero_securite_sociale" class="block mt-1 w-full" type="text"
                                        name="numero_securite_sociale" :value="old('numero_securite_sociale')" />
                                    <x-input-error :messages="$errors->get('numero_securite_sociale')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Adresse -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Adresse</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Adresse -->
                                <div class="md:col-span-2">
                                    <x-input-label for="adresse" :value="__('Adresse')" />
                                    <x-text-input id="adresse" class="block mt-1 w-full" type="text" name="adresse"
                                        :value="old('adresse')" />
                                    <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
                                </div>

                                <!-- Ville -->
                                <div>
                                    <x-input-label for="ville" :value="__('Ville')" />
                                    <x-text-input id="ville" class="block mt-1 w-full" type="text" name="ville"
                                        :value="old('ville')" />
                                    <x-input-error :messages="$errors->get('ville')" class="mt-2" />
                                </div>

                                <!-- Code postal -->
                                <div>
                                    <x-input-label for="code_postal" :value="__('Code postal')" />
                                    <x-text-input id="code_postal" class="block mt-1 w-full" type="text"
                                        name="code_postal" :value="old('code_postal')" />
                                    <x-input-error :messages="$errors->get('code_postal')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="4"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <!-- Boutons -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('personnel.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-hidden focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Annuler') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Créer') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Afficher/masquer les champs de départ selon le statut
        document.getElementById('statut').addEventListener('change', function() {
            const statutValue = this.value;
            const raisonContainer = document.getElementById('raison_depart_container');
            const motifContainer = document.getElementById('motif_depart_container');

            if (statutValue === 'parti') {
                raisonContainer.style.display = 'block';
                motifContainer.style.display = 'block';
            } else {
                raisonContainer.style.display = 'none';
                motifContainer.style.display = 'none';
            }
        });
    </script>
</x-app-layout>
