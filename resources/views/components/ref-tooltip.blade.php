@props(['matiere', 'slot_item' => null])



<x-tooltip :position="'right'" :class="'group'">
    <x-slot:slot_item>
        <!-- Affichage de la référence interne de la matière -->
        @if ($slot_item)
            {{ $slot_item }}
        @else
            <span class="cursor-pointer underline">
                {{ $matiere->ref_interne }}
            </span>
        @endif
    </x-slot:slot_item>
    <x-slot:slot_tooltip>
        <div
            class="p-3 max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl 2xl:max-w-2xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700 rounded shadow-lg"
            <!-- Lien vers la page de la matière -->
            <div class="mb-3">
                <a href="{{ route('matieres.show', $matiere->id) }}" target="_blank"
                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold text-sm block">
                    {{ $matiere->designation }}
                </a>
            </div>
            <!-- date de création -->
            <div class="mb-3">
                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Créé le</span>
                <div class="font-mono text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $matiere->created_at->format('d/m/Y H:i') }}
                </div>
            </div>

            <!-- Référence interne -->
            <div class="mb-3 pb-2 border-b border-gray-200 dark:border-gray-700">
                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Référence</span>
                <div class="font-mono text-sm font-medium text-gray-900 dark:text-gray-100">
                    <x-copiable_text :text="$matiere->ref_interne" />
                </div>
            </div>

            <!-- Liste des fournisseurs -->
            <div>
                <span
                    class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide block mb-2">Fournisseurs</span>
                @if ($matiere->fournisseurs && $matiere->fournisseurs->count() > 0)
                    <div class="space-y-1 w-full max-h-66 overflow-y-auto">
                        @foreach ($matiere->fournisseurs as $fournisseur)
                            @php
                                $ref_externe = $matiere->societeMatiere($fournisseur->id)->ref_externe ?? null;
                            @endphp
                            <span class="flex items-center gap-2">
                                <span class="font-semibold text-gray-700 dark:text-gray-200 ">
                                    {{ $fournisseur->raison_sociale }}
                                </span>
                                <span class="text-gray-500 dark:text-gray-400">:</span>
                                @if ($ref_externe)
                                    <x-copiable_text text="{{ $ref_externe }}" class="text-gray-900 dark:text-gray-100" />
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 italic">N/A</span>
                                @endif
                            </span>
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="text-sm text-gray-400 dark:text-gray-500 italic">
                        Aucun fournisseur associé
                    </div>
                @endif
            </div>
        </div>
    </x-slot:slot_tooltip>
</x-tooltip>
