<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Prévisualisation de l'import de base de données XLSX
        </h2>
    </x-slot>
    @php
        // Calcul des stats et tri
        $total = count($rows);
        $incorrect = 0;
        $correct = 0;
        $rowStates = [];
        
        if ($total === 0) {
            // Aucune donnée à afficher
            $sortedIndexes = collect([]);
        } else {
            foreach ($rows as $i => $row) {
                $isValid = true;
                // Les colonnes critiques bloquent la validité
                $critCols = ['ref_interne', 'designation', 'unite', 'famille'];
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
        }
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
            </div>
            <div>
                <a href="{{ route('matieres.import.database.form') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </div>

        <!-- Légende -->
        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
            <h3 class="font-semibold mb-2 text-blue-800 dark:text-blue-200">Légende de validation :</h3>
            <div class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                <div><span class="text-green-600 text-lg">&#10003;</span> Champ valide et trouvé dans la base</div>
                <div><span class="text-red-600 text-lg">&#10060;</span> Erreur bloquante (la ligne ne sera pas importée)</div>
                <div class="mt-2 font-semibold">Champs obligatoires : Référence interne, Désignation, Unité, Famille</div>
                <div class="text-xs">Champs optionnels : Matériau, Standard, Fournisseur, DN, Épaisseur, Longueur, Prix</div>
            </div>
        </div>

        @if ($total > 0)
            <!-- Info colonnes détectées -->
            <details class="mb-6">
                <summary class="cursor-pointer p-3 bg-gray-100 dark:bg-gray-700 rounded hover:bg-gray-200 dark:hover:bg-gray-600 font-medium">
                    🔍 Colonnes détectées dans le fichier (cliquer pour voir)
                </summary>
                <div class="mt-2 p-4 bg-gray-50 dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-600">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                        @foreach($headers as $col)
                            @php
                                $hasData = false;
                                foreach($rows as $row) {
                                    if (!empty($row[$col])) {
                                        $hasData = true;
                                        break;
                                    }
                                }
                            @endphp
                            <div class="flex items-center">
                                @if($hasData)
                                    <span class="text-green-600 mr-2">✓</span>
                                @else
                                    <span class="text-gray-400 mr-2">○</span>
                                @endif
                                <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $col)) }}</span>
                                <span class="text-xs text-gray-500 ml-2">({{ $hasData ? 'données présentes' : 'vide' }})</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </details>
        @endif

        <form id="import-form" action="{{ route('matieres.import.database.store') }}" method="POST">
            @method('POST')
            @csrf
            <input type="hidden" name="rows" value='@json($rows)'>
            
            @if ($total === 0)
                <div class="mb-6 p-6 bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-500 rounded">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <h3 class="font-bold text-yellow-800 dark:text-yellow-200 mb-1">Aucune donnée détectée</h3>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                Le fichier ne contient pas de données valides ou les en-têtes ne sont pas reconnus.
                                Veuillez vérifier le format du fichier et réessayer.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 mb-6">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="px-2 py-2 border text-xs">#</th>
                            @foreach ($headers as $col)
                                <th class="px-4 py-2 border text-sm">
                                    {{ ucfirst(str_replace('_', ' ', $col)) }}
                                    @if (in_array($col, ['ref_interne', 'designation', 'unite', 'famille']))
                                        <span class="text-red-500 text-xs">*</span>
                                    @endif
                                </th>
                            @endforeach
                            <th class="px-4 py-2 border text-sm">Valide</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sortedIndexes as $i)
                            @php
                                $row = $rows[$i];
                                $rowValid = $rowStates[$i];
                            @endphp
                            <tr @if (!$rowValid) class="bg-red-50 dark:bg-red-950" @endif>
                                <td class="px-2 py-2 border text-xs text-gray-500">{{ $i + 2 }}</td>
                                @foreach ($headers as $col)
                                    <td class="p-1 border align-top">
                                        <div class="flex flex-col">
                                            <span class="text-sm @if (isset($preview[$i][$col]['error'])) text-red-700 dark:text-red-300 font-medium @endif">
                                                {{ $row[$col] ?? '' }}
                                            </span>
                                            @if (isset($preview[$i][$col]['error']) && $preview[$i][$col]['error'])
                                                <span class="text-xs text-red-600 dark:text-red-400 mt-1">
                                                    &#10060; {{ $preview[$i][$col]['error'] }}
                                                </span>
                                            @elseif(isset($preview[$i][$col]['label']) && $preview[$i][$col]['label'])
                                                <span class="text-xs text-green-700 dark:text-green-400 mt-1">
                                                    &#10003; {{ $preview[$i][$col]['label'] }}
                                                </span>
                                            @elseif(isset($preview[$i][$col]['id']) && $preview[$i][$col]['id'] === true)
                                                <span class="text-xs text-green-700 dark:text-green-400 mt-1">
                                                    &#10003;
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                @endforeach
                                <td class="px-4 py-2 border text-center align-middle">
                                    @if ($rowValid)
                                        <span class="text-green-600 text-2xl">&#10003;</span>
                                    @else
                                        <span class="text-red-600 text-2xl">&#10060;</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="flex items-center gap-4">
                @if ($incorrect > 0)
                    <x-modals.attention-modal 
                        buttonText="Importer uniquement les lignes correctes ({{ $correct }})"
                        title="Des erreurs sont présentes dans le fichier"
                        message="Certaines lignes comportent des erreurs et ne seront pas importées. Voulez-vous importer uniquement les {{ $correct }} lignes valides ?"
                        confirmText="Oui, importer les lignes valides" 
                        cancelText="Annuler" 
                        confirmAction="submit" />
                @else
                    <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                        Importer {{ $total }} matière(s) en base
                    </button>
                @endif
                <a href="{{ route('matieres.import.database.form') }}" class="btn btn-secondary">Annuler</a>
            </div>
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
</x-app-layout>
