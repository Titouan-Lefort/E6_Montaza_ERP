<x-app-layout>
    @section('title', 'Validation - ' . $ddp->code)

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Demandes de prix</a>
                >>
                <a href="{{ route('ddp.show', $ddp->id) }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Créer une demande de prix') !!}</a>
                >> Validation
            </h2>
        </div>
        <a href="{{ route('ddp.annuler', $ddp->id) }}" class="btn">Annuler la ddp</a>
    </x-slot>
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <form action="{{ route('ddp.validate', $ddp->id) }}" method="POST">
            @csrf
            <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
                <div class="flex items-center mb-12">
                    <h1 class="text-3xl font-bold  text-left mr-2">{{ $ddp->nom }} - Récapitulatif</h1>
                    <div class="text-center w-fit px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                        style="background-color: {{ $ddp->statut->couleur }}; color: {{ $ddp->statut->couleur_texte }}">
                        {{ $ddp->statut->nom }}</div>
                </div>
                <div class="flex justify-between">
                    <div class="flex flex-col gap-4 m-4">
                        <div>
                            <x-input-label value="Dossier suivi par ?" />
                            <select name="dossier_suivi_par_id" required class="select w-fit min-w-96">
                                <option value="0" {{ old('dossier_suivi_par_id') == 0 ? 'selected' : '' }}>Non
                                    suivi</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('dossier_suivi_par_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dossier_suivi_par_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex gap-4">
                            <x-toggle :checked="old('afficher_destinataire', true)" :label="'Afficher le mail du destinataire dans le PDF ?'" id="afficher_destinataire"
                                name="afficher_destinataire" class="toggle-class" />
                            @error('afficher_destinataire')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <div class="flex gap-4">
                                <x-input-label value="Date de besoin matière" />
                                <small>(Optionnel)</small>
                            </div>
                            <x-date-input name="date_rendu" :value="old('date_rendu')" />
                            @error('date_rendu')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <img src="{{ asset($entite->logo) }}" alt="Logo"
                        class="w-1/4 h-1/4 mb-4 object-contain float-right">
                </div>
                @foreach ($societes as $societe)
                    <div class="mb-6">
                        <h2
                            class="text-xl font-semibold text-gray-700 dark:text-gray-200  border-b border-gray-300 dark:border-gray-700 pb-2">
                            {{ $societe->raison_sociale }}
                        </h2>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="bg-white dark:bg-gray-900 w-fit h-full rounded-md overflow-auto mt-2">
                                    <table class="min-w-0">
                                        <thead>
                                            <tr>
                                                <th class="py-2">Référence</th>
                                                <th class="py-2">Désignation</th>
                                                <th class="py-2">Quantité</th>
                                                <th class="py-2">Unité</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ddp->ddpLigne as $ddpLigne)
                                                @foreach ($ddpLigne->ddpLigneFournisseur as $ddpLigneFournisseur)
                                                    @if ($ddpLigneFournisseur->societe_id == $societe->id)
                                                        <tr>
                                                            <td
                                                                class="border border-gray-300 dark:border-gray-700 px-4 py-2">
                                                                {{ $ddpLigne->matiere->ref_interne }}</td>
                                                            <td
                                                                class="border border-gray-300 dark:border-gray-700 px-4 py-2">
                                                                {{ $ddpLigne->matiere->designation }}</td>
                                                            <td
                                                                class="border border-gray-300 dark:border-gray-700 px-4 py-2">
                                                                {{ formatNumber($ddpLigne->quantite) }}</td>
                                                            <td class="border border-gray-300 dark:border-gray-700 px-4 py-2"
                                                                title="{{ $ddpLigne->matiere->unite->full }}">
                                                                {{ $ddpLigne->matiere->unite->short }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                            @foreach ($ddp->ddpLigne as $ddpLigne)
                                                @if ($ddpLigne->ligne_autre_id != null)
                                                    <tr>
                                                        <td
                                                            class="border border-gray-300 dark:border-gray-700 px-4 py-2">
                                                            {{ $ddpLigne->case_ref }}</td>
                                                        <td
                                                            class="border border-gray-300 dark:border-gray-700 px-4 py-2">
                                                            {{ $ddpLigne->case_designation }}</td>
                                                        <td class="border border-gray-300 dark:border-gray-700 px-4 py-2"
                                                            colspan="2">
                                                            {{ $ddpLigne->case_quantite }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            <tr class="h-14"></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="flex flex-col p-4 gap-1">
                                <div class="flex flex-col mb-2">
                                    <x-input-label value="Établissement" />
                                    <select name="etablissement-{{ $societe->id }}" required
                                        id="etablissement-{{ $societe->id }}" class="select w-fit min-w-96"
                                        onchange="changeEtablissement({{ $societe->id }})">
                                        @php
                                            $selectedEtablissementId = old('etablissement-' . $societe->id);
                                            if (!$selectedEtablissementId) {
                                                $selectedEtablissementId = $ddp
                                                    ->ddpLigneFournisseur()
                                                    ->where('societe_id', $societe->id)
                                                    ->whereNotNull('societe_contact_id')
                                                    ->with('societeContact')
                                                    ->get()
                                                    ->pluck('societeContact.etablissement_id')
                                                    ->filter()
                                                    ->first();
                                            }
                                        @endphp
                                        @if ($societe->etablissements->count() == 1)
                                            <option value="{{ $societe->etablissements->first()->id }}" selected>
                                                {{ $societe->etablissements->first()->nom }}
                                            </option>
                                        @else
                                            <option value="" disabled
                                                {{ !$selectedEtablissementId ? 'selected' : '' }}>Choisir un
                                                établissement</option>
                                            @foreach ($societe->etablissements as $etablissement)
                                                <option value="{{ $etablissement->id }}"
                                                    {{ $selectedEtablissementId == $etablissement->id ? 'selected' : '' }}>
                                                    {{ $etablissement->nom }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('etablissement-' . $societe->id)
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex flex-col mb-2">
                                    <x-input-label value="Destinataire" />
                                    <select name="contact-{{ $societe->id }}" id="contact-{{ $societe->id }}"
                                        required class="select w-fit min-w-96">
                                        @php
                                            $selectedContactId = old('contact-' . $societe->id);
                                            if (!$selectedContactId) {
                                                $selectedContactId = $ddp
                                                    ->ddpLigneFournisseur()
                                                    ->where('societe_id', $societe->id)
                                                    ->whereNotNull('societe_contact_id')
                                                    ->pluck('societe_contact_id')
                                                    ->first();
                                            }
                                            $etablissementContacts = null;
                                            if ($selectedEtablissementId) {
                                                $etablissementContacts = $societe->etablissements
                                                    ->where('id', $selectedEtablissementId)
                                                    ->first()?->contacts;
                                            } elseif ($societe->etablissements->count() == 1) {
                                                $etablissementContacts = $societe->etablissements->first()->contacts;
                                            }
                                        @endphp
                                        @if ($etablissementContacts && $etablissementContacts->count() > 0)
                                            @if ($etablissementContacts->count() == 1)
                                                <option value="{{ $etablissementContacts->first()->id }}" selected>
                                                    {{ $etablissementContacts->first()->nom }}
                                                    {{ $etablissementContacts->first()->fonction }}
                                                    {{ $etablissementContacts->first()->email }}
                                                </option>
                                            @else
                                                <option value="" disabled
                                                    {{ !$selectedContactId ? 'selected' : '' }}>Choisir un destinataire
                                                </option>
                                                @foreach ($etablissementContacts as $contact)
                                                    <option value="{{ $contact->id }}"
                                                        {{ $selectedContactId == $contact->id ? 'selected' : '' }}>
                                                        {{ $contact->nom }} {{ $contact->fonction }}
                                                        {{ $contact->email }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @else
                                            <option value="" disabled selected>Aucun contact</option>
                                        @endif
                                    </select>
                                    @error('contact-' . $societe->id)
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                @error('contact-' . $societe->id)
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="flex justify-between">
                    <a href="{{ route('ddp.show', $ddp->id) }}" class="btn btn-secondary">{{ __('Annuler') }}</a>
                    <button type="submit" class="btn">{{ __('Valider') }}</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        function changeEtablissement(societeId) {
            let etablissementId = document.getElementById('etablissement-' + societeId).value;
            let select = document.getElementById('contact-' + societeId);
            select.innerHTML = '';
            let option = document.createElement('option');
            option.value = '';
            // console.log(`/societes/${societeId}/etablissements/${etablissementId}/contacts/json`);
            fetch(`/societes/${societeId}/etablissements/${etablissementId}/contacts/json`)
                .then(response => response.json())
                .then(data => {
                    if (data.length == 0) {
                        let option = document.createElement('option');
                        option.value = '';
                        option.text = 'Aucun contact';
                        option.disabled = true;
                        option.selected = true;
                        select.add(option);
                    } else if (data.length > 1) {
                        let option = document.createElement('option');
                        option.value = '';
                        option.text = 'Choisir un destinataire';
                        option.disabled = true;
                        option.selected = true;
                        select.add(option);
                    }
                    if (data.length == 1) {
                        let option = document.createElement('option');
                        option.value = data[0].id;
                        option.text = `${data[0].nom} ${data[0].fonction} ${data[0].email}`;
                        option.selected = true;
                        select.add(option);
                    } else {
                        data.forEach(contact => {
                            let option = document.createElement('option');
                            option.value = contact.id;
                            option.text = `${contact.nom} ${contact.fonction} ${contact.email}`;
                            select.add(option);
                        });
                    }

                })
                .catch(error => console.error('Error fetching contacts:', error));
        }
    </script>


</x-app-layout>
