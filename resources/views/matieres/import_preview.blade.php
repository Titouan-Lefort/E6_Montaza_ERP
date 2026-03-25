<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Prévisualisation de l'import de matières
        </h2>
    </x-slot>
    @php
        // Calcul des stats et tri
        $total = count($rows);
        $incorrect = 0;
        $correct = 0;
        $rowStates = [];
        foreach ($rows as $i => $row) {
            $isValid = true;
            // Seules les colonnes critiques bloquent la validité
            $critCols = ['ref_interne', 'designation', 'unite', 'sous_famille'];
            foreach ($critCols as $col) {
                if (isset($preview[$i][$col]['error']) && $preview[$i][$col]['error']) {
                    $isValid = false;
                    break;
                }
            }
            $rowStates[$i] = $isValid;
            if ($isValid) {
                $correct++;
            } else {
                $incorrect++;
            }
        }
        // Tri : erreurs d'abord
        $sortedIndexes = collect($rows)
            ->keys()
            ->sort(function ($a, $b) use ($rowStates) {
                return $rowStates[$b] <=> $rowStates[$a] ?: $a <=> $b;
            })
            ->values();
    @endphp
    <div class="max-w-8xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded shadow text-gray-800 dark:text-gray-200">
        <div class="mb-6 flex flex-wrap gap-8 items-center justify-between">
            <div class="flex gap-8">
                <div class="bg-gray-100 dark:bg-gray-900 rounded-lg p-4 shadow flex flex-col min-w-[140px]">
                    <span class="text-gray-700 dark:text-gray-200 text-xs uppercase tracking-wide mb-1">Total lignes</span>
                    <span class="font-bold text-2xl text-gray-900 dark:text-gray-100">{{ $total }}</span>
                </div>
                <div class="bg-green-50 dark:bg-green-900 rounded-lg p-4 shadow flex flex-col min-w-[140px]">
                    <span class="text-green-800 dark:text-green-200 text-xs uppercase tracking-wide mb-1">Correctes</span>
                    <span class="font-bold text-2xl text-green-800 dark:text-green-200">{{ $correct }}</span>
                </div>
                <div class="bg-red-50 dark:bg-red-900 rounded-lg p-4 shadow flex flex-col min-w-[140px]">
                    <span class="text-red-800 dark:text-red-200 text-xs uppercase tracking-wide mb-1">Incorrectes</span>
                    <span class="font-bold text-2xl text-red-800 dark:text-red-200">{{ $incorrect }}</span>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900 rounded-lg p-4 shadow flex flex-col min-w-[140px]">
                    <span class="text-yellow-800 dark:text-yellow-200 text-xs uppercase tracking-wide mb-1">Total erreurs</span>
                    <span class="font-bold text-2xl text-yellow-800 dark:text-yellow-200">
                        {{ collect($preview)->flatMap(fn($row) => collect($row)->pluck('error')->filter())->count() }}
                    </span>
                </div>
            </div>
            <div>
                <a href="{{ route('matieres.import.form') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
        <form id="import-form" action="{{ route('matieres.import.store') }}" method="POST">
            @method('POST')
            @csrf
            <input type="hidden" name="rows" value='@json($rows)'>
            <input type="hidden" name="fournisseur_id" value="{{ $fournisseur_id }}">
            <input type="hidden" name="prix_fournisseur" value="{{ $prix_fournisseur }}">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 mb-6">
                    <thead>
                        <tr>
                            <th class="px-2 py-2 border">#</th>
                            @foreach ($headers as $col)
                                <th class="px-4 py-2 border">{{ $col }}</th>
                            @endforeach
                            <th class="px-4 py-2 border">Valide</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sortedIndexes as $i)
                            @php
                                $row = $rows[$i];
                                // Critères de validité : ref_interne, designation, unite, sous_famille obligatoires et valides
                                $rowValid = true;
                                foreach (['ref_interne', 'designation', 'unite', 'sous_famille'] as $col) {
                                    if (isset($preview[$i][$col]['error']) && $preview[$i][$col]['error']) {
                                        $rowValid = false;
                                        break;
                                    }
                                }
                            @endphp
                            <tr @if (!$rowValid) class="bg-gray-50 dark:bg-gray-900" @endif>
                                <td class="px-2 py-2 border text-xs text-gray-500">{{ $i + 2 }}</td>
                                @foreach ($headers as $col)
                                    <td class=" p-1 border align-top">
                                        <input type="text" name="edit[{{ $i }}][{{ $col }}]"
                                            value="{{ $row[$col] ?? '' }}"
                                            class=" bg-transparent border-none focus:ring-0 @if (isset($preview[$i][$col]['error'])) bg-red-100 dark:bg-red-900 @endif">
                                        @if (isset($preview[$i][$col]['error']) && $preview[$i][$col]['error'])
                                            <span class="text-xs text-red-600 dark:text-red-400">&#10060;
                                                {{ $preview[$i][$col]['error'] }}</span>
                                        @elseif(isset($preview[$i][$col]['id']) && $preview[$i][$col]['id'])
                                            <span class="text-xs text-green-700 dark:text-green-400">&#10003;</span>
                                        @endif
                                        @if (isset($preview[$i][$col]['label']) && $preview[$i][$col]['label'])
                                            <span
                                                class="text-xs text-green-700 dark:text-green-400">{{ $preview[$i][$col]['label'] }}</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-4 py-2 border text-center align-middle">
                                    @if ($rowValid)
                                        <span class="text-green-600 text-xl">&#10003;</span>
                                    @else
                                        <span class="text-red-600 text-xl">&#10060;</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($incorrect > 0)
                <x-modals.attention-modal buttonText="Importer uniquement les lignes correctes"
                    title="Des erreurs sont présentes dans le fichier"
                    message="Certaines lignes comportent des erreurs et ne seront pas importées. Voulez-vous importer uniquement les lignes valides ?"
                    confirmText="Oui, importer les lignes valides" cancelText="Annuler" confirmAction="submit" />
            @else
                <button type="submit" class="btn">Importer en base</button>
            @endif
            <a href="{{ route('matieres.import.form') }}" class="btn btn-secondary ml-2">Annuler</a>
        </form>
    </div>
    <button id="back-to-top" class="fixed bottom-4 right-4 bg-gray-700 hover:bg-gray-900 dark:bg-gray-600 dark:hover:bg-gray-800 text-white p-3 rounded-full shadow-lg z-30 hidden" title="Retour en haut">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
        </svg>
    </button>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const backToTop = document.getElementById('back-to-top');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTop.classList.remove('hidden');
                } else {
                    backToTop.classList.add('hidden');
                }
            });
            backToTop.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function resizeInput(input) {
                // Ajuste la largeur selon le nombre de caractères (ex: 1ch par caractère + un peu de marge)
                const length = input.value.length || 1;
                input.style.width = (length + 3) + 'ch';
            }

            // Select all editable inputs in the table
            const inputs = document.querySelectorAll('table input[type="text"]');
            inputs.forEach(input => {
                resizeInput(input);
                input.addEventListener('input', function () {
                    resizeInput(input);
                });
            });
        });
    </script>
</x-app-layout>
