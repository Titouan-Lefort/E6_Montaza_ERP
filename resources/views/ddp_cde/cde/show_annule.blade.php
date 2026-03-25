<x-app-layout>
    @section('title', 'Commande annulée - ' . $cde->code)
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('cde.index') }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Commandes</a>
                    >>
                    <a href="{{ route('cde.show', $cde->id) }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Commande') !!}</a>
                    >> Commande annulée
                </h2>
            </div>
            <a href="{{ route('cde.pdfs.download', $cde) }}" class="btn">Télécharger le PDF</a>
            <a href="{{ route('cde.pdfs.pdfdownload_sans_prix', $cde) }}" class="btn">Télécharger le PDF sans prix</a>
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <!-- Bannière d'annulation -->
        <div
            class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200 p-4 mb-6 rounded shadow-md">
            <div class="flex items-center">
                <x-icon type="error_icon" size="6" class="text-red-500 mr-3" />
                <div>
                    <p class="font-bold text-xl">Cette commande a été annulée</p>
                    <p>Date d'annulation:
                        {{ $cde->updated_at ? Carbon\Carbon::parse($cde->updated_at)->format('d/m/Y H:i') : 'Non spécifiée' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md opacity-80">
            <div class="flex items-center mb-12">
                <h1 class="text-3xl font-bold text-left mr-2">{{ $cde->nom }} - Récapitulatif</h1>
                <div class="text-center w-fit px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                    style="background-color: {{ $cde->statut->couleur }}; color: {{ $cde->statut->couleur_texte }}">
                    {{ $cde->statut->nom }}
                </div>
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
                                <th colspan="2" class="p-2 text-center">Matière</th>
                                <th colspan="1" class="p-2 text-center">Quantité</th>
                                <th colspan="1" class="p-2 text-center">PU HT</th>
                                <th colspan="1" class="p-2 text-center">Montant HT</th>
                                <th colspan="1" class="p-2 text-center">Type d'expedition</th>
                                <th colspan="1" class="p-2 text-center">Date de livraison</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($cde->cdeLignes->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center p-4 text-gray-500">
                                        Aucune ligne de commande trouvée.
                                    </td>
                                </tr>
                            @endif
                            @foreach ($cde->cdeLignes as $ligne)
                                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
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
                                    <td class="p-2 text-left line-through">{{ $ligne->designation }}
                                        @if ($ligne->sous_ligne != null)
                                            <br />
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $ligne->sous_ligne }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-2 text-right line-through whitespace-nowrap"
                                        title="{{ formatNumber($ligne->quantite) }} {{ $ligne->matiere ? $ligne->matiere->unite->full : '' }}">
                                        {{ formatNumber($ligne->quantite) }}
                                        {{ $ligne->matiere ? $ligne->matiere->unite->short : '' }}
                                    </td>
                                    <td class="p-2 text-center line-through whitespace-nowrap">
                                        {{ formatNumberArgent($ligne->prix_unitaire) }}
                                    </td>
                                    <td class="p-2 text-center line-through whitespace-nowrap">
                                        {{ formatNumberArgent($ligne->prix) }}
                                    </td>
                                    <td class="p-2 text-center line-through">{{ $ligne->typeExpedition->short }}</td>
                                    <td class="p-2 text-center line-through">
                                        {{ $ligne->date_livraison ? \Carbon\Carbon::parse($ligne->date_livraison)->format('d/m/Y') : 'Non définie' }}
                                    </td>
                                </tr>
                            @endforeach

                            <tr class="border-t-2 border-gray-200 dark:border-gray-700">
                                <td class="p-2" colspan="400">
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

            {{-- Affichage des commentaires --}}
            <div class="mt-4 w-full md:w-5/6 relative">
                <div class="absolute inset-0 bg-transparent z-10"></div>
                @include('ddp_cde.cde.partials.commentaire')
            </div>

            {{-- Bouton pour reprendre la commande --}}
            <div class="flex justify-center mt-10">
                <a href="{{ route('cde.reprendre', $cde->id) }}" class="btn">
                    <x-icons.refresh size="2" class="text-white" />
                    <span class="text-lg font-bold">Reprendre cette commande</span>
                </a>
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('cde.index') }}" class="btn">Retour à la liste</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <livewire:media-sidebar :model="'cde'" :model-id="$cde->id" />
    </div>
</x-app-layout>
