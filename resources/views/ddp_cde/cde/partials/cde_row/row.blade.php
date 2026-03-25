<tr class="hover:bg-green-50/50 dark:hover:bg-green-900/10 cursor-pointer transition-colors duration-150 group"
    onclick="window.location='{{ route('cde.show', $cde) }}'">
    @php
        $limit = $isSmall ? 25 : 75;
        $limit = $isMid ? 35 : $limit;
    @endphp
    <!-- Code -->
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
             @include('ddp_cde.cde.partials.cde_row.code_cell', compact('cde', 'isSmall'))
        </div>
    </td>

    <!-- Date de création -->
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
        <div class="flex flex-col">
            <span class="font-medium">{{ $cde->created_at->format('d/m/Y') }}</span>
            @if (!$isSmall)
                <span class="text-xs text-gray-400">{{ $cde->updated_at->format('H:i') }}</span>
            @endif
        </div>
    </td>

    <!-- Nom -->
    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100">
        @if (Str::length($cde->nom) > $limit)
            <x-tooltip position="top">
                <x-slot name="slot_item">{{ Str::limit($cde->nom == $cde->code ? '' : $cde->nom, $limit) }}</x-slot>
                <x-slot name="slot_tooltip">{{ $cde->nom == $cde->code ? '' : $cde->nom }}</x-slot>
            </x-tooltip>
        @else
            {{ $cde->nom == $cde->code ? '' : $cde->nom }}
        @endif
    </td>

    @if (!$isSmall && !$isMid)
        <!-- Créé par -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            <div class="flex items-center">
                 <div class="h-6 w-6 rounded-full bg-green-100 dark:bg-green-800 flex items-center justify-center text-xs font-bold text-green-700 dark:text-green-300 mr-2">
                    {{ substr($cde->user->first_name, 0, 1) . substr($cde->user->last_name, 0, 1) }}
                </div>
                {{ $cde->user->first_name }} {{ $cde->user->last_name }}
            </div>
        </td>
    @endif

    @if (!$isSmall)
        <!-- Destinataire -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
            <div class="flex items-center gap-1">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                </svg>
                <x-tooltip position="left">
                    <x-slot name="slot_item">
                        <span class="font-medium text-gray-700 dark:text-gray-200">
                             {{ Str::limit($cde->societe?->raison_sociale, $limit - 10) }}
                        </span>
                    </x-slot>
                    <x-slot name="slot_tooltip">
                        <div class="flex flex-col">
                            <h3 class="font-bold border-b border-gray-300 dark:border-gray-600 pb-2 mb-2">
                                {{ $cde->societe?->raison_sociale }}
                            </h3>
                            @if($cde->societeContacts->count() > 0)
                                <h3 class="font-bold">
                                    Destinataire{{ $cde->societeContacts->count() > 1 ? 's' : '' }} :
                                </h3>
                                @foreach ($cde->societeContacts as $contact)
                                    <span>
                                        {{ $contact->nom }}
                                        <small class="text-gray-400">{{ $contact->email }}</small>
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </x-slot>
                </x-tooltip>
            </div>
        </td>
    @endif

    <!-- Statut -->
    <td class="px-6 py-4 whitespace-nowrap text-center">
         @php
            $statutColor = $cde->statut->couleur ?? '#CBD5E0';
            $statutText = $cde->statut->couleur_texte ?? '#2D3748';
            $statutNom = $cde->statut->nom ?? 'Inconnu';
        @endphp
        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm"
              style="background-color: {{ $statutColor }}20; color: {{ $statutColor }}; border: 1px solid {{ $statutColor }}40;">
            {{ $statutNom }}
        </span>
    </td>
</tr>
