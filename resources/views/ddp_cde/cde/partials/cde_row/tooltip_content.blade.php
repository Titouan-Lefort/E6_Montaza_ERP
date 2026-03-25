<div class="flex flex-col max-w-md">
    <h3 class="text-gray-900 dark:text-gray-100 font-bold mb-2">Contenu de la commande</h3>

    @if ($cde->ddp_cde_statut_id == 4)
        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200 p-4 mb-6 rounded shadow-md">
            <div class="flex items-center">
                <x-icon type="error_icon" size="2" class="text-red-500 mr-3" />
                <div>
                    <p class="font-bold text-lg">Cette commande a été annulée</p>
                    <p>Date d'annulation: {{ $cde->updated_at ? Carbon\Carbon::parse($cde->updated_at)->format('d/m/Y H:i') : 'Non spécifiée' }}</p>
                </div>
            </div>
        </div>
    @endif

    @if ($cde->cdeLignes->count() > 0)
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">
                    <th class="px-2 py-1 text-xs">Poste</th>
                    <th class="px-2 py-1 text-xs">Désignation</th>
                    <th class="px-2 py-1 text-xs">Qté</th>
                    <th class="px-2 py-1 text-xs">Prix</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cde->cdeLignes as $ligne)
                    <tr class="border-b dark:border-gray-600 {{ $cde->ddp_cde_statut_id == 4 || $ligne->ddp_cde_statut_id == 4 || ($ligne->date_livraison_reelle == null && $cde->ddp_cde_statut_id != 1) ? 'line-through' : '' }}">
                        <td class="px-2 py-1 text-xs">{{ $ligne->poste }}</td>
                        <td class="px-2 py-1 text-xs">
                            {{ $ligne->designation }}
                            @if ($ligne->conditionnement != 0)
                                <br />
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    <x-icons.turn-left class="inline-block mr-2 -rotate-180 fill-gray-700 dark:fill-gray-400" size="1.5" />
                                    Par conditionnement de {{ formatNumber($ligne->conditionnement) }}
                                    {{ $ligne->matiere ? $ligne->matiere->unite->short : '' }}
                                </span>
                            @endif
                        </td>
                        <td class="px-2 py-1 text-xs text-right whitespace-nowrap">
                            {{ formatNumber($ligne->quantite) }}
                            {{ $ligne->matiere ? $ligne->matiere->unite->short : '' }}
                        </td>
                        <td class="px-2 py-1 text-xs text-right whitespace-nowrap">
                            {{ formatNumberArgent($ligne->prix) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-bold bg-gray-50 dark:bg-gray-800">
                    <td colspan="3" class="px-2 py-1 text-xs text-right">Total:</td>
                    <td class="px-2 py-1 text-xs text-right whitespace-nowrap">{{ formatNumberArgent($cde->total_ht) }}</td>
                </tr>
                <tr class="font-bold bg-gray-50 dark:bg-gray-800">
                    <td colspan="3" class="px-2 py-1 text-xs text-right">Total TTC:</td>
                    <td class="px-2 py-1 text-xs text-right whitespace-nowrap">{{ formatNumberArgent($cde->total_ttc) }}</td>
                </tr>
            </tfoot>
        </table>
    @else
        <p class="text-gray-600 dark:text-gray-300">Aucune ligne dans cette commande</p>
    @endif
</div>
