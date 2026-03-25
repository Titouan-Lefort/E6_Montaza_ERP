<x-app-layout>
    @section('title', 'Commande - ' . $cde->code)
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('cde.index') }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Commandes</a>
                    >>
                    <a href="{{ route('cde.show', $cde->id) }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Créer une commande') !!}</a>
                    >>
                    <a href="{{ route('cde.annuler_terminer', $cde->id) }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Retours') !!}</a>
                    >> Récapitulatif
                </h2>

            </div>
            <a href="{{ route('cde.pdfs.download', $cde) }}" class="btn">Télécharger le PDF</a>
            <a href="{{ route('cde.pdfs.pdfdownload_sans_prix', $cde) }}" class="btn">Télécharger le PDF sans
                prix</a>
            <a href="{{ route('cde.annuler', $cde->id) }}" class="btn">Annuler la commande</a>
        </div>
    </x-slot>
    {{--
##     ##  #######  ##       ######## ########       ######  ########  #######   ######  ##     ##
##     ## ##     ## ##       ##          ##         ##    ##    ##    ##     ## ##    ## ##    ##
##     ## ##     ## ##       ##          ##         ##          ##    ##     ## ##       ##   ##
##     ## ##     ## ##       ######      ##          ######     ##    ##     ## ##       #####
 ##   ##  ##     ## ##       ##          ##               ##    ##    ##     ## ##       ##   ##
  ## ##   ##     ## ##       ##          ##         ##    ##    ##    ##     ## ##    ## ##    ##
   ###     #######  ######## ########    ##          ######     ##     #######   ######  ##     ##

 --}}


    @include('ddp_cde.cde.partials.enregistrement_stock')

    {{-- @else
            <x-changements-stock :changements_stock="$changements_stock" />
    @endif --}}
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <!-- Section Devis Fournisseurs PDF -->
        @php
            $devisPdfs = $cde->media()->where('mime_type', 'application/pdf')->get();
        @endphp

        @if($devisPdfs->count() > 0)
            <div class="text-gray-900 dark:text-gray-100 p-6 rounded-md mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Devis Fournisseurs ({{ $devisPdfs->count() }})
                    </h2>
                    <button x-data @click="$dispatch('open-volet', 'media-manager')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter un devis
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($devisPdfs as $pdf)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden hover:shadow-lg transition bg-gray-100 dark:bg-gray-800">
                            <div class="bg-gray-200 dark:bg-gray-700 p-3">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center space-x-2 flex-1 min-w-0">
                                        <svg class="w-8 h-8 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate" title="{{ $pdf->original_filename }}">
                                                {{ $pdf->original_filename }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ number_format($pdf->size / 1024, 2) }} Ko
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 bg-gray-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('media.show', $pdf->id) }}" target="_blank" class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition w-full">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Voir le PDF
                                    </a>
                                    <a href="{{ route('media.download', $pdf->id) }}" class="inline-flex items-center justify-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition w-full">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Télécharger
                                    </a>
                                    @if($pdf->user)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center mt-2">
                                            Ajouté par {{ $pdf->user->prenom }} {{ $pdf->user->nom }}
                                            <br>
                                            le {{ $pdf->created_at->format('d/m/Y à H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-blue-100/50 dark:bg-blue-900/20 border border-blue-300 dark:border-blue-800 text-blue-900 dark:text-blue-200 p-4 rounded-md mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium">Aucun devis fournisseur n'a encore été ajouté</p>
                            <p class="text-sm opacity-75">Cliquez sur le bouton pour ajouter les devis PDF des fournisseurs</p>
                        </div>
                    </div>
                    <button x-data @click="$dispatch('open-volet', 'media-manager')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter un devis
                    </button>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md ">
            <div class="flex items-center mb-12">
                <h1 class="text-3xl font-bold  text-left mr-2">{{ $cde->nom }} - Récapitulatif</h1>
                <div class="text-center w-fit px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                    style="background-color: {{ $cde->statut->couleur }}; color: {{ $cde->statut->couleur_texte }}">
                    {{ $cde->statut->nom }}</div>
            </div>
            <div class="flex flex-col gap-1 mr-8 -mt-10">
                @if ($cde->societeContacts->isNotEmpty() && $cde->societeContacts->first()->societe)
                    <div class="text-sm font-semibold">
                        {{ $cde->societeContacts->first()->societe->raison_sociale }}
                    </div>
                @endif
            </div>
            <div class="overflow-x-auto overflow-y-visible">
                <div class="float-left">
                    <table class="w-auto table-auto bg-white dark:bg-gray-900 min-w-0">
                        <thead class="">
                            <tr
                                class="bg-gray-200 dark:bg-gray-700 border-r-2 border-r-gray-200 dark:border-r-gray-700">
                                <th style="width: 5px; padding: 0%;padding-top:5px;">
                                    <div class="poste">Poste</div>
                                </th>
                                <th colspan="2" class=" p-2 text-center">
                                    Matière</th>
                                <th colspan="1" class=" p-2 text-center"> Quantité</th>
                                <th colspan="1" class=" p-2 text-center"> PU HT</th>
                                <th colspan="1" class=" p-2 text-center"> Montant HT</th>
                                <th colspan="1" class=" p-2 text-center"> Type d&#39;expedition</th>
                                <th colspan="1" class=" p-2 text-center"> Date de livraison</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cde->cdeLignes as $ligne)
                                @if ($ligne->ddpCdeStatut->nom == 'Annulée')
                                    <tr class="bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400">
                                        <td class="text-center ml-1 p-2">
                                            {{ $ligne->poste }}
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

                                        <td class="p-2 text-left"><span
                                                class="text-red-500 dark:text-red-400 font-bold">Annulée </span>
                                            <span class="line-through">{{ $ligne->designation }}
                                                @if ($ligne->sous_ligne != null)
                                                    <br />
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $ligne->sous_ligne }}
                                                    </span>
                                                @endif
                                            </span>

                                        </td>
                                        <td class="p-2 text-right line-through whitespace-nowrap"
                                            title="{{ formatNumber($ligne->quantite) }} {{ $ligne->matiere->unite->full }}">
                                            {{ formatNumber($ligne->quantite) }} {{ $ligne->matiere->unite->short }}
                                        </td>
                                        <td class="p-2 text-center line-through whitespace-nowrap">
                                            {{ formatNumberArgent($ligne->prix_unitaire) }}
                                        </td>
                                        <td class="p-2 text-center line-through whitespace-nowrap">
                                            {{ formatNumberArgent($ligne->prix) }}</td>
                                        <td class="p-2 text-center line-through">{{ $ligne->typeExpedition->short }}
                                        </td>
                                        <td class="p-2">
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-center ml-1 p-2">
                                            {{ $ligne->poste }}
                                        </td>
                                        @if ($ligne->ligne_autre_id == null)
                                            <td class="text-left ml-1 p-2">
                                                <x-ref-tooltip :matiere="$ligne->matiere">
                                                    <x-slot:slot_item>
                                                        <div class="flex flex-col {{ $showRefFournisseur ? '' : 'hidden' }}"
                                                            id="refs-{{ $ligne->matiere_id }}">
                                                            <div class="flex flex-col">
                                                                <span class="text-xs">Réf. Interne</span>
                                                                <span
                                                                    class="font-bold">{{ $ligne->ref_interne ?? '-' }}</span>
                                                            </div>
                                                            <div class="flex flex-col">
                                                                <span class="text-xs">Réf. Fournisseur</span>
                                                                <span
                                                                    class="font-bold">{{ $ligne->ref_fournisseur ?? '-' }}</span>

                                                            </div>
                                                        </div>
                                                        <div class="flex flex-col {{ $showRefFournisseur ? 'hidden' : '' }}"
                                                            id="ref-{{ $ligne->matiere_id }}">
                                                            <div class="flex flex-col">
                                                                <span class="text-xs">Réf. Interne</span>
                                                                <span
                                                                    class="font-bold">{{ $ligne->ref_interne ?? '-' }}</span>
                                                            </div>
                                                        </div>
                                                    </x-slot:slot_item>
                                                </x-ref-tooltip>

                                            </td>
                                        @else
                                            <td class="text-left ml-1 p-2">
                                                <div class="flex flex-col {{ $showRefFournisseur ? '' : 'hidden' }}"
                                                    id="refs-{{ $ligne->ligne_autre_id }}">
                                                    <div class="flex flex-col">
                                                        <span class="text-xs">Réf. Interne</span>
                                                        <span class="font-bold">{{ $ligne->ref_interne ?? '-' }}</span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-xs">Réf. Fournisseur</span>
                                                        <span
                                                            class="font-bold">{{ $ligne->ref_fournisseur ?? '-' }}</span>

                                                    </div>
                                                </div>
                                                <div class="flex flex-col {{ $showRefFournisseur ? 'hidden' : '' }}"
                                                    id="ref-{{ $ligne->ligne_autre_id }}">
                                                    <div class="flex flex-col">
                                                        <span class="text-xs">Réf. Interne</span>
                                                        <span class="font-bold">{{ $ligne->ref_interne ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                        <td class="p-2 text-left">{{ $ligne->designation }}
                                            @if ($ligne->sous_ligne != null)
                                                <br />
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $ligne->sous_ligne }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-2 text-right"
                                            title="{{ formatNumber($ligne->quantite) }} {{ $ligne->matiere ? $ligne->matiere->unite->full : '' }}">
                                            {{ formatNumber($ligne->quantite) }}
                                            {{ $ligne->matiere ? $ligne->matiere->unite->short : '' }}</td>
                                        <td class="p-2 text-center whitespace-nowrap">
                                            {{ formatNumberArgent($ligne->prix_unitaire) }}
                                        </td>
                                        <td class="p-2 text-center whitespace-nowrap">
                                            {{ formatNumberArgent($ligne->prix) }}</td>
                                        <td class="p-2 text-center">{{ $ligne->typeExpedition->short }}</td>
                                        <td class="p-2 text-center">
                                            {{ $ligne->date_livraison_reelle ? \Carbon\Carbon::parse($ligne->date_livraison_reelle)->format('d/m/Y') : 'Non livré' }}
                                        </td>
                                    </tr>
                                @endif
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
                                                        {{ formatNumberArgent($cde->total_ht - $cde->frais_de_port - $cde->frais_divers) }}
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
                                                        {{ formatNumberArgent($cde->total_ht) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="pr-4" id="tva_container">
                                                        TVA ({{ $cde->tva }}%) :
                                                    </td>
                                                    <td id="total_tva_plus">
                                                        {{ formatNumberArgent(($cde->total_ht * $cde->tva) / 100) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="pr-4">
                                                        Total TTC :
                                                    </td>
                                                    <td id="total_ttc">
                                                        {{ formatNumberArgent($cde->total_ttc) }}

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


            </div>
            {{-- Affichage des changements de livraison --}}
            <div class="mt-4 ml-2">
                @include('ddp_cde.cde.partials.changement_livraison')
            </div>
            {{-- Affichage des commentaires --}}
            <div class="mt-4 w-full md:w-5/6">
                @include('ddp_cde.cde.partials.commentaire')
            </div>
            <div class="flex justify-between items-center mt-6">
                @if ($cde->statut->id == 3)
                    <x-modals.attention-modal buttonText="Annuler terminé"
                        title="Voulez-vous vraiment annuler cette commande ?"
                        message="Cette action retournera la commande à l'étape de livraison et retirera les Matière ajoutées au stock. Êtes-vous sûr de vouloir continuer ?"
                        confirmText="Annuler terminé" cancelText="Annuler"
                        confirmAction="{{ route('cde.annuler_terminer', $cde->id) }}" />

                    <a href="{{ route('cde.terminer_controler', $cde->id) }}" class="btn float-right">Terminer et
                        controlé</a>
                @elseif ($cde->statut->id == 5)
                    <a href="{{ route('cde.annuler_terminer_controler', $cde->id) }}" class="btn float-right">Annuler
                        controlé</a>
                @endif

            </div>

        </div>
    </div>
    <div class="col-md-4">
        <livewire:media-sidebar :model="'cde'" :model-id="$cde->id" />
    </div>


</x-app-layout>
