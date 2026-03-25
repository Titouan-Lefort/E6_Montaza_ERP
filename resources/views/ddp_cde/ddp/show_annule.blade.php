<x-app-layout>
    @section('title', 'Demande de prix annulée - ' . $ddp->code)
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('ddp.index') }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Demandes de prix</a>
                    >>
                    <a href="{{ route('ddp.show', $ddp->id) }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Demande de prix') !!}</a>
                    >> Demande de prix annulée
                </h2>
            </div>
            <a href="{{ route('ddp.pdfs.download', $ddp) }}" class="btn">Télécharger tous les PDF</a>
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <!-- Bannière d'annulation -->
        <div
            class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200 p-4 mb-6 rounded shadow-md">
            <div class="flex items-center">
                <x-icon type="error_icon" size="6" class="text-red-500 mr-3" />
                <div>
                    <p class="font-bold text-xl">Cette demande de prix a été annulée</p>
                    <p>Date d'annulation:
                        {{ $ddp->updated_at ? Carbon\Carbon::parse($ddp->updated_at)->format('d/m/Y H:i') : 'Non spécifiée' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md opacity-80">
            <div class="flex items-center mb-12">
                <h1 class="text-3xl font-bold text-left mr-2">{{ $ddp->nom }} - Récapitulatif</h1>
                <div class="text-center w-fit px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                    style="background-color: {{ $ddp->statut->couleur }}; color: {{ $ddp->statut->couleur_texte }}">
                    {{ $ddp->statut->nom }}</div>
            </div>
            <div class="overflow-x-auto overflow-y-visible">
                <table class="w-auto table-auto bg-white dark:bg-gray-900 min-w-0">
                    <thead class="">
                        <tr class="bg-gray-200 dark:bg-gray-700 border-r-2 border-r-gray-200 dark:border-r-gray-700">
                            <th class=" p-2 text-center"></th>
                            <th class=" p-2 text-center"></th>
                            @foreach ($ddp_societes as $societe)
                                <th colspan="3"
                                    class=" p-2 text-center border-l-2 border-l-gray-500 dark:border-l-gray-300">
                                    {{ $societe->raison_sociale }}</th>
                            @endforeach
                        </tr>
                        <tr class="bg-gray-200 dark:bg-gray-700 border-r-2 border-r-gray-200 dark:border-r-gray-700">
                            <th colspan="1" class=" p-2 text-center">
                                Matière</th>
                            <th colspan="1" class=" p-2 text-center">
                                quantité</th>
                            @foreach ($ddp_societes as $societe)
                                <th class=" p-2 text-center border-l-2 border-l-gray-500 dark:border-l-gray-300">
                                    Prix unitaire</th>
                                <th class=" p-2 text-center">
                                    Montant</th>
                                <th class=" p-2 text-center">
                                    Délai</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="text-gray-500 dark:text-gray-400">
                        @if (empty($data))
                            <tr>
                                <td colspan="{{ 2 + 3 * count($ddp_societes) }}"
                                    class="text-center p-4 text-gray-500 dark:text-gray-400">
                                    Aucune donnée disponible
                                </td>
                            </tr>
                        @else
                            @php
                                $lastindex = count($data) - 1;
                                $total_quantite = 0;
                                foreach ($ddplignes as $ddpligne) {
                                    $total_quantite += $ddpligne->quantite;
                                }
                            @endphp
                            @foreach ($data as $index => $ligne)
                                <tr>
                                    @if ($index == $lastindex)
                                        <td
                                            class="text-center border border-gray-300 dark:border-gray-700 line-through">
                                            TOTAL</td>
                                        <td
                                            class="text-center border border-gray-300 dark:border-gray-700 line-through">
                                            {{ $total_quantite }}</td>
                                    @else
                                        <td
                                            class="border border-gray-300 dark:border-gray-700 pl-2 line-through
                                            {{ $index % 2 == 1 ? 'bg-gray-50 dark:bg-gray-800' : '' }}
                                    ">
                                            <x-tooltip position="top">
                                                <x-slot name="slot_tooltip">
                                                    <a href="{{ route('matieres.show', $ddplignes[$index]->matiere->id) }}"
                                                        target="_blank"
                                                        class="lien">{{ $ddplignes[$index]->matiere->designation }}</a>
                                                </x-slot>
                                                <x-slot name="slot_item">
                                                    {{ $ddplignes[$index]->matiere->ref_interne . ' ' . Str::limit($ddplignes[$index]->matiere->designation, 30, '...') }}
                                                </x-slot>
                                            </x-tooltip>
                                        </td>
                                        <td
                                            class="text-center border border-gray-300 dark:border-gray-700 line-through
                                            {{ $index % 2 == 1 ? 'bg-gray-50 dark:bg-gray-800' : '' }}
                                    ">
                                            {{ formatNumber($ddplignes[$index]->quantite) }}</td>
                                    @endif

                                    @foreach ($ligne as $key => $value)
                                        @if ($value == 'UNDEFINED')
                                            <td
                                                class="border border-gray-300 dark:border-gray-700 p-2 bg-gray-200 dark:bg-gray-1000 {{ $key % 3 == 0 ? 'border-l-2 border-l-gray-500 dark:border-l-gray-300' : '' }}">
                                            </td>
                                        @else
                                            <td
                                                class="border border-gray-300 dark:border-gray-700 p-2 line-through {{ $key % 3 == 0 ? 'border-l-2 border-l-gray-500 dark:border-l-gray-300' : '' }} {{ $index % 2 == 1 ? 'bg-gray-50 dark:bg-gray-800' : '' }} whitespace-nowrap
                                        ">
                                                {{ $value }}
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-4 w-full md:w-5/6 relative">
                <div class="absolute inset-0 bg-transparent z-10"></div>
                @include('ddp_cde.ddp.commentaire')
            </div>

            {{-- Bouton pour reprendre la demande de prix --}}
            <div class="flex justify-center mt-10">
                <a href="{{ route('ddp.reprendre', $ddp->id) }}" class="btn">
                    <x-icons.refresh size="2" class="text-white" />
                    <span class="text-lg font-bold">Reprendre cette demande de prix</span>
                </a>
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('ddp.index') }}" class="btn">Retour à la liste</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <livewire:media-sidebar :model="'ddp'" :model-id="$ddp->id" />
    </div>
</x-app-layout>
