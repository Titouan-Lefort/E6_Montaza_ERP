<tbody>
    @foreach ($ddpsGrouped as $entiteName => $ddps)
        <!-- En-tête de groupe d'entité -->
        <tr>
            <td colspan="5" class="pt-4">
            <div class="flex items-center justify-between border-b-2 border-gray-300 dark:border-gray-600 pb-2 mb-2">
                <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-800">
                    <x-icons.group class="w-4 h-4 text-gray-600 dark:text-gray-300" />
                </span>
                <span class="text-base font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide">
                    {{ $entiteName }}
                </span>
                </div>
                <span class="text-xs font-semibold text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700 px-3 py-1 rounded-full shadow-sm">
                {{ $ddps->count() }} demande{{ $ddps->count() > 1 ? 's' : '' }} de prix
                </span>
            </div>
            </td>
        </tr>
        <!-- Lignes de demandes de prix pour cette entité -->
        @foreach ($ddps as $ddp)
            <tr class="border-b border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 dark:border-gray-700 cursor-pointer"
                onclick="window.location='{{ route('ddp.show', $ddp) }}'">
                <!-- Code -->
                <td class="min-w-2 text-sm font-bold">
                    {{ $ddp->code }}
                </td>

                <!-- Date de création -->
                <td class="pl-2 text-xs leading-5">
                    <span class="text-nowrap">
                        <span class="pr-1 leading-5">{{ $ddp->created_at->format('d/m/Y') }}</span>
                        <small>{{ $ddp->updated_at->format('H:i') }}</small>
                    </span>
                </td>

                <!-- Nom -->
                <td>
                    {{ $ddp->nom }}
                </td>

                <!-- Utilisateur -->
                <td>
                    {{ $ddp->user->first_name }} {{ $ddp->user->last_name }}
                </td>

                <!-- Statut avec couleur dynamique -->
                <td class="">
                    <div class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                        style="background-color: {{ $ddp->ddpCdeStatut->couleur }}; color: {{ $ddp->ddpCdeStatut->couleur_texte }}">
                        {{ $ddp->ddpCdeStatut->nom }}
                    </div>
                </td>
            </tr>
        @endforeach
    @endforeach
</tbody>
