<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier une société') }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 ">
                    <form method="POST" action="{{ route('societes.update', $societe->id) }}"
                        class="flex flex-col w-full grid-cols-3 gap-6 sm:grid">
                        @csrf
                        @method('PATCH')
                        <div class="col-span-3">
                            <x-input-label for="raison_sociale" :value="__('Raison Sociale')" />
                            <x-text-input id="raison_sociale" class="block mt-1 w-1/3" type="text"
                                placeholder="Atlantis Montaza" name="raison_sociale"
                                value="{{ old('raison_sociale', $societe->raison_sociale) }}" required autofocus />
                            @error('raison_sociale')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="societe_type_id" :value="__('Type de Société')" />
                            <select id="societe_type_id" name="societe_type_id" class="block mt-1 w-full select"
                                required>
                                <option value="" disabled
                                    {{ old('societe_type_id', $societe->societe_type_id) == '' ? 'selected' : '' }}>--
                                    Choisir un type de société --</option>
                                @foreach ($societeTypes as $societeType)
                                    <option value="{{ $societeType->id }}"
                                        {{ old('societe_type_id', $societe->societe_type_id) == $societeType->id ? 'selected' : '' }}>
                                        {{ $societeType->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('societe_type_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="forme_juridique_id" :value="__('Forme Juridique')" />
                            <select id="forme_juridique_id" name="forme_juridique_id" class="block mt-1 w-full select"
                                required>
                                <option value="" disabled
                                    {{ old('forme_juridique_id', $societe->forme_juridique_id) == '' ? 'selected' : '' }}>
                                    -- Choisir une forme juridique --</option>
                                @foreach ($formeJuridiques as $formeJuridique)
                                    <option value="{{ $formeJuridique->id }}"
                                        {{ old('forme_juridique_id', $societe->forme_juridique_id) == $formeJuridique->id ? 'selected' : '' }}>
                                        {{ $formeJuridique->code }} {{ $formeJuridique->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('forme_juridique_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="code_ape_id" :value="__('Code APE')" />
                            <select id="code_ape_id" name="code_ape_id" class="block mt-1 w-full select">
                                <option value="" disabled
                                    {{ old('code_ape_id', $societe->code_ape_id) == '' ? 'selected' : '' }}>-- Choisir
                                    un code APE --</option>
                                @foreach ($codeApes as $codeApe)
                                    <option value="{{ $codeApe->id }}"
                                        {{ old('code_ape_id', $societe->code_ape_id) == $codeApe->id ? 'selected' : '' }}>
                                        {{ $codeApe->code }} {{ $codeApe->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_ape_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>



                        <div class="col-span-1">
                            <x-input-label for="telephone" :value="__('Téléphone')" optionnel />
                            <x-text-input id="telephone" class="block mt-1 w-full" type="text" name="telephone"
                                maxlength="30" placeholder="+33 XX XX XX XX XX"
                                value="{{ old('telephone', $societe->telephone) }}" />
                            @error('telephone')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="email" :value="__('Email')" optionnel />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                placeholder="info@atlantismontaza.fr" value="{{ old('email', $societe->email) }}" />
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-input-label for="site_web" :value="__('Site Web')" optionnel />
                            <x-text-input id="site_web" class="block mt-1 w-full" type="text" name="site_web"
                                placeholder="https://www.exemple.com"
                                value="{{ old('site_web', $societe->site_web) }}" />
                            @error('site_web')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <x-tooltip
                            slot_tooltip="Le SIREN est obligatoire pour les clients, mais optionnel pour les fournisseurs"
                            position="top" class="text-gray-500">
                            <x-slot name="slot_item">

                                <div class="col-span-1">
                                    <div class="flex items-center justify-between">
                                        <x-input-label for="siren" :value="__('SIREN')" />
                                        <small>
                                            <a href="#" id="verify-siren" class="text-blue-500">Vérifier le
                                                SIREN</a>
                                            <script>
                                                document.getElementById('verify-siren').addEventListener('click', function(event) {
                                                    event.preventDefault();
                                                    var siren = document.getElementById('siren').value;
                                                    var url = 'https://www.infogreffe.fr/entreprise-societe/' + siren;
                                                    window.open(url, '_blank');
                                                });
                                            </script>
                                        </small>
                                    </div>
                                    <x-text-input id="siren" class="block mt-1 w-full" type="text" name="siren"
                                        minlength="9" placeholder="XXXXXXXXX"
                                        value="{{ old('siren', $societe->siren) }}" required />
                                    @error('siren')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror

                                </div>
                            </x-slot>
                        </x-tooltip>

                        <x-tooltip
                            slot_tooltip="Le numéro TVA est obligatoire pour les clients, mais optionnel pour les fournisseurs"
                            position="top" class="text-gray-500">
                            <x-slot name="slot_item">
                                <div class="col-span-1">
                                    <x-input-label for="numero_tva" :value="__('Numéro TVA')" />
                                    <x-text-input id="numero_tva" class="block mt-1 w-full" type="text"
                                        name="numero_tva" placeholder="FRXX XXXX XXXX"
                                        value="{{ old('numero_tva', $societe->numero_tva) }}" required />
                                    @error('numero_tva')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </x-slot>
                        </x-tooltip>

                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <x-input-label value="Conditions de paiement" />
                                <div>
                                    <select name="condition_paiement_id" required class="block mt-1 w-full select"
                                        onchange="changeConditionPaiement()">

                                        @foreach ($conditionsPaiement as $conditionPaiement)
                                            <option value="{{ $conditionPaiement->id }}"
                                                {{ old('condition_paiement_id', $societe->condition_paiement_id) == $conditionPaiement->id ? 'selected' : '' }}>
                                                {{ $conditionPaiement->nom }}
                                            </option>
                                        @endforeach
                                        <option value="0">Autre</option>
                                    </select>
                                    <x-text-input name="condition_paiement_text" :value="old('condition_paiement_text')"
                                        class="w-full rounded-t-none border-0 pt-2 -mt-2 hidden focus:border-t-0 focus:ring-0" />
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
                        <div class="col-span-3">
                            <x-input-label for="commentaire" :value="__('Commentaire')" />
                            <textarea rows="3" id="commentaire" name="commentaire"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-900 dark:text-gray-100"
                                placeholder="(Optionnel) Votre commentaire ici">{{ old('commentaire', $societe->commentaire) }}</textarea>
                            @error('commentaire')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end col-span-3 mt-4">
                            <button type="submit" class="btn ml-4">
                                {{ __('Modifier') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('siren').addEventListener('input', function() {
            var sirenError = document.getElementById('siren-error');
            if (!sirenError) {
                sirenError = document.createElement('small');
                sirenError.id = 'siren-error';
                sirenError.className = 'text-red-500 text-sm';
                sirenError.innerHTML = `ATTENTION :<br>
                 - Le SIRET des établissements associés ne sera pas modifié automatiquement.`;
                this.parentNode.appendChild(sirenError);
            }
        });
    </script>
    <script>
        document.getElementById('societe_type_id').addEventListener('change', function() {
            const sirenField = document.getElementById('siren');
            const selectedType = parseInt(this.value, 10);

            const sirenLabel = document.querySelector('label[for="siren"]');
            const tvaField = document.getElementById('numero_tva');
            const tvaLabel = document.querySelector('label[for="numero_tva"]');

            const apeField = document.getElementById('code_ape_id');
            const apeLabel = document.querySelector('label[for="code_ape_id"]');

            if (selectedType === 2) {
                sirenField.required = false;
                sirenLabel.querySelector('small')?.remove(); // Remove the "(Optionnel)" label if it exists
                const optionalSirenLabel = document.createElement('small');
                optionalSirenLabel.textContent = '(Optionnel)';
                sirenLabel.appendChild(optionalSirenLabel);

                tvaField.required = false;
                tvaLabel.querySelector('small')?.remove(); // Remove the "(Optionnel)" label if it exists
                const optionalTvaLabel = document.createElement('small');
                optionalTvaLabel.textContent = '(Optionnel)';
                tvaLabel.appendChild(optionalTvaLabel);

                apeField.required = false;
                apeLabel.querySelector('small')?.remove(); // Remove the "(Optionnel)" label if it exists
                const optionalApeLabel = document.createElement('small');
                optionalApeLabel.textContent = '(Optionnel)';
                apeLabel.appendChild(optionalApeLabel);

            } else {
                sirenField.required = true;
                sirenLabel.querySelector('small')?.remove(); // Remove the "(Optionnel)" label if it exists
                tvaField.required = true;
                tvaLabel.querySelector('small')?.remove(); // Remove the "(Optionnel)" label if it exists
                apeField.required = true;
                apeLabel.querySelector('small')?.remove(); // Remove the "(Optionnel)" label if it exists
            }
        });
        // Trigger the change event on page load to set the initial state
        document.getElementById('societe_type_id').dispatchEvent(new Event('change'));
    </script>
</x-app-layout>
