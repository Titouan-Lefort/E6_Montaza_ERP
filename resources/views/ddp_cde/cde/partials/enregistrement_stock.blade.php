<div class="fixed top-1/2 right-0 transform -translate-y-1/2" x-data>
    <button @click="$dispatch('open-volet', 'changements-stock')" id="btn-open-enregistrement_stock"
        class="btn-select-left flex items-center px-2 py-8 bg-gray-200 dark:bg-gray-800 shadow-lg hover:bg-gray-300 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-700">
        <x-icon :size="1" type="arrow_back" />
        <span class=" whitespace-nowrap font-medium transform -rotate-90 inline-block w-1 mt-30 -mb-7">Changements
            stock</span>
    </button>

</div>

<x-volet-modal name="changements-stock" direction="right" x-init="$dispatch('open-volet', 'changements-stock')"
    x-on:close="$dispatch('close-volet', 'changements-stock')">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
                <h2 class="text-lg font-semibold">Stock</h2>
                <x-tooltip position="top" class="ml-2">
                    <x-slot name="slot_item">
                        <x-icons.question class="icons" size="1" />
                    </x-slot>
                    <x-slot name="slot_tooltip">
                        <p class="text-sm font-bold">Gestion des entrées en stock</p>
                        <p class="text-sm">Ce volet permet gérer les quantités reçues pour chaque matière
                            commandée et de les ajouter au stock.</p>
                        <p class="text-sm">Vous pouvez ajuster les quantités avant validation.</p>
                        <p class="text-sm">Vous pouvez annuler les mouvements de stock.</p>
                    </x-slot>
                </x-tooltip>
            </div>
            <button @click="$dispatch('close')" id="btn-close-enregistrement_stock">
                <x-icons.close class="icons" size="1.5" unfocus />
            </button>
        </div>
        @if ($cde->cdeLignes->where('ddpCdeStatut.nom', '!=', 'Annulée')->where('date_livraison_reelle', '!=', null)->whereNull('is_stocke')->whereNull('ligne_autre_id')->count() > 0)

            <h2 class="text-md font-semibold">À ajouter au stock</h2>


            <form id="stock-form" method="POST" action="{{ route('cde.stock.store', $cde->id) }}"
                onsubmit="document.getElementById('btn-close-enregistrement_stock').click();">
                @csrf
                <table class="w-full border-collapse border-0 rounded-md">

                    <tbody>
                        @foreach ($cde->cdeLignes->where('ddpCdeStatut.nom', '!=', 'Annulée')->where('date_livraison_reelle', '!=', null)->whereNull('is_stocke')->whereNull('ligne_autre_id') as $ligne)
                            @php
                                $ligne->load('matiere.unite');
                            @endphp
                            <tr class="">
                                {{-- <td class="text-center border border-gray-300 dark:border-gray-700 px-1">
                                {{ $ligne->poste }}
                            </td> --}}
                                <td class="">
                                    <button id="button-{{ $ligne->poste }}" type="button"
                                        class="flex justify-between items-center p-3 w-full text-left transition-colors rounded dark:hover:bg-gray-700 hover:bg-gray-200 bg-gray-100 dark:bg-gray-900">
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="font-medium text-gray-800 dark:text-gray-200 inline-block min-w-8 px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded-md text-center">
                                                {{ $ligne->poste }}
                                            </span>
                                            <div class="flex gap-2">
                                                <x-ref-tooltip :matiere="$ligne->matiere">
                                                    <x-slot:slot_item>
                                                        <div class="flex flex-col">
                                                            <span
                                                                class="font-medium text-sm">{{ $ligne->ref_interne }}</span>
                                                            <span
                                                                class="font-medium text-sm">{{ $ligne->ref_fournisseur }}</span>
                                                        </div>
                                                    </x-slot:slot_item>
                                                </x-ref-tooltip>
                                                <span
                                                    class="text-gray-700 dark:text-gray-400 text-sm line-clamp-1">{{ $ligne->matiere->designation }}</span>
                                            </div>
                                        </div>
                                        <x-icon type="arrow_back" :size="1" class="transition-transform"
                                            id="arrow-{{ $ligne->poste }}" />
                                    </button>

                                    <div id="stock-{{ $ligne->poste }}"
                                        class="hidden p-3 bg-gray-50 dark:bg-gray-850 rounded-b border border-gray-300 dark:border-gray-700">
                                        <div class="flex flex-col">
                                            <div class="flex justify-between items-center
                                            ">

                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-4">
                                                    Quantité
                                                    commandée :
                                                    {{ formatNumber($ligne->quantite) }}
                                                    {{ $ligne->matiere->unite->short }}
                                                </p>
                                                <button type="button" class="btn-sm -mt-1"
                                                    onclick="storeStockLigne({{ $ligne->id }})">Enregistrer la ligne</button>
                                            </div>
                                            <p class="text-sm text-gray-800 dark:text-gray-200">Quantité à ajouter au stock :</p>

                                            @php
                                                $rows = [];
                                                $valeur_unitaire = 1;
                                                $is_packaged = false;
                                                $unites = 0;
                                                $reste = 0;

                                                if ($ligne->conditionnement != 0) {
                                                    $valeur_unitaire = $ligne->conditionnement;
                                                    $is_packaged = true;
                                                } elseif ($ligne->matiere->ref_valeur_unitaire != null && $ligne->matiere->ref_valeur_unitaire != 0) {
                                                    $valeur_unitaire = $ligne->matiere->ref_valeur_unitaire;
                                                    $is_packaged = true;
                                                }

                                                if ($is_packaged) {
                                                    $unites = floor($ligne->quantite / $valeur_unitaire);
                                                    $reste = $ligne->quantite - ($unites * $valeur_unitaire);

                                                    if ($unites > 0) {
                                                        $rows[] = ['qty' => $unites, 'val' => $valeur_unitaire];
                                                    }
                                                    if ($reste > 0) {
                                                        $rows[] = ['qty' => 1, 'val' => $reste];
                                                    }
                                                } else {
                                                    $rows[] = ['qty' => $ligne->quantite, 'val' => 1];
                                                }

                                                if (empty($rows)) {
                                                     $rows[] = ['qty' => $ligne->quantite, 'val' => 1];
                                                }
                                            @endphp

                                            <div class="flex w-full justify-end">
                                                @if($ligne->matiere->ref_valeur_unitaire)
                                                <p class="text-sm text-gray-800 dark:text-gray-200 -mt-9 md:-mt-5">
                                                    Valeur unitaire :
                                                    {{ $ligne->matiere->ref_valeur_unitaire }}
                                                    {{ $ligne->matiere->unite->short }}</p>
                                                @endif
                                            </div>

                                            <table class="w-full border-collapse border border-gray-400 dark:border-gray-700 mt-2 mb-2">
                                                <thead>
                                                    <tr>
                                                        <th class="p-1 text-sm bg-gray-200 dark:bg-gray-750 text-gray-800 dark:text-gray-200 border border-gray-400 dark:border-gray-700 border-r-0">
                                                            Quantité</th>
                                                        <th class="w-1 p-0 text-gray-800 bg-gray-200 dark:bg-gray-750 border-y border-gray-400 dark:border-gray-700">
                                                        </th>
                                                        <th class="p-1 text-sm border border-gray-400 dark:border-gray-700 bg-gray-200 dark:bg-gray-750 border-r-0 text-gray-800 dark:text-gray-200"
                                                            title="Valeur unitaire ({{ $ligne->matiere->unite->full }})">
                                                            Valeur unitaire ({{ $ligne->matiere->unite->short }})
                                                        </th>
                                                        <th class="w-1 p-0 bg-gray-200 dark:bg-gray-750 border-y border-gray-400 dark:border-gray-700">
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-{{ $ligne->poste }}">
                                                    @foreach($rows as $index => $row)
                                                    <tr class="border-b border-gray-300 dark:border-gray-700" id="stock-{{ $ligne->poste }}-row-{{ $index }}">
                                                        <td class="p-1">
                                                            <x-text-input type="number"
                                                                name="stock[{{ $ligne->poste }}][rows][{{ $index }}][quantity]"
                                                                id="stock-{{ $ligne->poste }}-row-{{ $index }}-quantity"
                                                                class="w-full border-0 focus:ring-0 p-1"
                                                                min="0" step="0.01"
                                                                value="{{ formatNumber($row['qty']) }}" />
                                                        </td>
                                                        <td class="w-1">X</td>
                                                        <td class="p-1">
                                                            <x-text-input type="number"
                                                                name="stock[{{ $ligne->poste }}][rows][{{ $index }}][unit_value]"
                                                                id="stock-{{ $ligne->poste }}-row-{{ $index }}-unit-value"
                                                                class="w-full border-0 focus:ring-0 p-1"
                                                                min="0" step="0.01"
                                                                value="{{ formatNumber($row['val']) }}" />
                                                        </td>
                                                        <td class="flex w-fit justify-center items-center pt-1">
                                                            <button type="button"
                                                                class="delete-row-button focus:outline-none"
                                                                title="Supprimer cette ligne"
                                                                data-row-id="stock-{{ $ligne->poste }}-row-{{ $index }}"
                                                                onclick="deleteStockRow('stock-{{ $ligne->poste }}-row-{{ $index }}')">
                                                                <x-icons.close size="2" class="icons" />
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach

                                                    <tr class="relative" id="add-row-container-{{ $ligne->poste }}">
                                                        <td class="p-1 text-center" colspan="4">
                                                            <button type="button"
                                                                id="add-row-button-{{ $ligne->poste }}"
                                                                class="text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 font-medium cursor-pointer">
                                                                <span class="flex items-center justify-center">
                                                                    Ajouter une ligne
                                                                </span>
                                                            </button>
                                                            <div class="absolute right-0 top-0 mr-2 mt-1">
                                                                Total : <span id="total-{{ $ligne->poste }}">{{ formatNumber($ligne->quantite) }}</span>{{ $ligne->matiere->unite->short }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <script>
                                                function deleteStockRow(rowId) {
                                                    const row = document.getElementById(rowId);
                                                    if (row) {
                                                        row.remove();
                                                    }
                                                }
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    const poste = '{{ $ligne->poste }}';
                                                    const addButton = document.getElementById(`add-row-button-${poste}`);

                                                    if (!window.rowCounters) {
                                                        window.rowCounters = {};
                                                    }
                                                    window.rowCounters[poste] = {{ count($rows) }};

                                                    addButton.addEventListener('click', function() {
                                                        const rowIndex = window.rowCounters[poste];
                                                        window.rowCounters[poste]++;

                                                        const container = document.getElementById(`add-row-container-${poste}`);
                                                        const newRow = document.createElement('tr');
                                                        newRow.className = 'border-b border-gray-300 dark:border-gray-700';
                                                        newRow.id = `stock-${poste}-row-${rowIndex}`;

                                                        newRow.innerHTML = `
                                                            <td class="p-1">
                                                                <x-text-input type="number"
                                                                    name="stock[${poste}][rows][${rowIndex}][quantity]"
                                                                    id="stock-${poste}-row-${rowIndex}-quantity"
                                                                    class="w-full border-0 focus:ring-0 p-1"
                                                                    min="0" step="0.01"
                                                                    value="1" />
                                                            </td>
                                                            <td class="w-1">X</td>
                                                            <td class="p-1">
                                                                <x-text-input type="number"
                                                                    name="stock[${poste}][rows][${rowIndex}][unit_value]"
                                                                    id="stock-${poste}-row-${rowIndex}-unit-value"
                                                                    class="w-full border-0 focus:ring-0 p-1"
                                                                    min="0" step="0.01"
                                                                    value="1" />
                                                            </td>
                                                            <td class="flex w-fit justify-center items-center pt-1">
                                                                <button type="button" class="delete-row-button focus:outline-none" title="Supprimer cette ligne" onclick="deleteStockRow('stock-${poste}-row-${rowIndex}')">
                                                                    <x-icons.close size="2" class="icons" />
                                                                </button>
                                                            </td>
                                                        `;

                                                        container.parentNode.insertBefore(newRow, container);
                                                    });
                                                });
                                            </script>
                                    </div>
                                </td>
                            </tr>
                            <tr class="h-4"></tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 flex justify-between">
                    <button class="bg-red-500 hover:bg-red-600 btn hover:cursor-pointer text-white dark:text-gray-100"
                        x-data x-on:click="$dispatch('open-modal', 'delete-stock-modal')" type="button">Ne pas
                        enregistrer</button>
                    <button type="submit" class="btn">Enregistrer dans le stock</button>

                </div>

            </form>
            <x-modal name="delete-stock-modal" title="Ne pas enregistrer" max-width="5xl">
                <div class="flex flex-col gap-4 p-4 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center">
                        <h1 class="text-xl font-semibold">Ne pas enregistrer les changements</h1>
                        <a x-on:click="$dispatch('close')">
                            <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                        </a>
                    </div>
                    <p class="text-gray-700 dark:text-gray-400">Voulez-vous vraiment ne pas enregistrer cette commande
                        dans
                        le stock ? Cette action est irréversible.</p>
                    <div class="flex justify-end gap-4">
                        <button class="text-white px-4 py-2 rounded-sm btn"
                            x-on:click="$dispatch('close')">Annuler</button>
                        <form action="{{ route('cde.stock.no', ['cde' => $cde->id]) }}" method="POST">
                            @csrf
                            @method('GET')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-sm hover:cursor-pointer">Ne
                                pas enregistrer</button>
                        </form>
                    </div>
                </div>
            </x-modal>
            <script>

                function storeStockLigne(ligneId) {
                    // Find the ligne to get the poste
                    const ligneElement = document.querySelector(`[onclick="storeStockLigne(${ligneId})"]`);
                    if (!ligneElement) {
                        showFlashMessageFromJs('Erreur: ligne non trouvée', 3000, 'error');
                        return;
                    }

                    // Extract poste from the closest stock container
                    const stockContainer = ligneElement.closest('[id^="stock-"]');
                    if (!stockContainer) {
                        showFlashMessageFromJs('Erreur: conteneur stock non trouvé', 3000, 'error');
                        return;
                    }

                    const poste = stockContainer.id.replace('stock-', '');
                    const form = document.getElementById('stock-form');
                    const inputs = form.querySelectorAll(`[name^="stock[${poste}]"]`);

                    const stockData = {};

                    inputs.forEach(input => {
                        const name = input.name;
                        const value = input.value;

                        // Parse different input patterns
                        if (name.includes('[entree]')) {
                            // Simple entry: stock[poste][entree]
                            stockData.entree = value;
                        } else if (name.includes('[rows]')) {
                            // Row-based entry: stock[poste][rows][index][field]
                            const rowMatch = name.match(/stock\[\d+\]\[rows\]\[(\d+)\]\[(\w+)\]/);
                            if (rowMatch) {
                                const rowIndex = rowMatch[1];
                                const fieldName = rowMatch[2];

                                if (!stockData.rows) stockData.rows = [];
                                if (!stockData.rows[rowIndex]) stockData.rows[rowIndex] = {};
                                stockData.rows[rowIndex][fieldName] = value;
                            }
                        }
                    });

                    // Validate that we have data to send
                    if (!stockData.entree && (!stockData.rows || stockData.rows.length === 0)) {
                        showFlashMessageFromJs('Aucune donnée à enregistrer', 3000, 'warning');
                        return;
                    }

                    const url = `{{ route('cde.stock.ligne.store', ['cde' => $cde->id, 'ligne' => 'LIGNE_ID']) }}`.replace('LIGNE_ID', ligneId);

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ stock: stockData })
                    })
                    .then(response => response.json())
                    .then(json => {
                        if (json.success) {
                            showFlashMessageFromJs(json.message || 'Mouvement de stock enregistré avec succès.', 3000, 'success');

                            // Find and remove the ligne's row from the form
                            const buttonElement = document.getElementById(`button-${poste}`);
                            if (buttonElement) {
                                const ligneRow = buttonElement.closest('tr');
                                const spacingRow = ligneRow.nextElementSibling; // The spacing row

                                if (ligneRow) {
                                    ligneRow.remove();
                                }
                                if (spacingRow && spacingRow.classList.contains('h-4')) {
                                    spacingRow.remove();
                                }
                            }
                        } else {
                            showFlashMessageFromJs(json.error || 'Erreur lors de l\'enregistrement du mouvement de stock.', 3000, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showFlashMessageFromJs('Erreur de communication avec le serveur', 3000, 'error');
                    });
                }
                document.addEventListener('DOMContentLoaded', function() {
                    // Get all the postes from the page
                    const postes = Array.from(document.querySelectorAll('[id^="button-"]')).map(el => el.id.split('-')[1]);

                    // Initialize total calculation for each poste
                    postes.forEach(poste => {
                        calculateTotal(poste);

                        // Add event listeners to existing inputs
                        const inputs = document.querySelectorAll(
                            `input[id^="stock-${poste}-row-"][id$="-quantity"], input[id^="stock-${poste}-row-"][id$="-unit-value"]`
                        );
                        inputs.forEach(input => {
                            input.addEventListener('input', () => calculateTotal(poste));
                        });
                    });

                    // Add mutation observer to detect when new rows are added
                    const observer = new MutationObserver(mutations => {
                        mutations.forEach(mutation => {
                            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                                mutation.addedNodes.forEach(node => {
                                    if (node.nodeType === 1 && node.id && node.id.startsWith(
                                            'stock-') && node.id.includes('-row-')) {
                                        const poste = node.id.split('-')[1];

                                        // Add event listeners to new inputs
                                        const newInputs = node.querySelectorAll(
                                            'input[id$="-quantity"], input[id$="-unit-value"]');
                                        newInputs.forEach(input => {
                                            input.addEventListener('input', () =>
                                                calculateTotal(poste));
                                        });

                                        // Calculate the new total
                                        calculateTotal(poste);
                                    }
                                });
                            }
                        });
                    });

                    // Observe the entire document for changes
                    observer.observe(document.body, {
                        childList: true,
                        subtree: true
                    });

                    // Override the deleteStockRow function to recalculate totals
                    window.deleteStockRowOriginal = window.deleteStockRow;
                    window.deleteStockRow = function(rowId) {
                        const poste = rowId.split('-')[1];
                        window.deleteStockRowOriginal(rowId);
                        calculateTotal(poste);
                    };
                });

                function calculateTotal(poste) {
                    // Find all rows for this poste
                    const rows = document.querySelectorAll(`tr[id^="stock-${poste}-row-"]`);
                    let total = 0;

                    // Calculate sum of quantity × unit value for each row
                    rows.forEach(row => {
                        const quantityInput = row.querySelector(`input[id$="-quantity"]`);
                        const unitValueInput = row.querySelector(`input[id$="-unit-value"]`);

                        if (quantityInput && unitValueInput) {
                            const quantity = parseFloat(quantityInput.value) || 0;
                            const unitValue = parseFloat(unitValueInput.value) || 0;
                            total += quantity * unitValue;
                        }
                    });

                    // Format total to 2 decimal places
                    total = parseFloat(total.toFixed(2));

                    // Update the total display
                    const totalDisplay = document.getElementById(`total-${poste}`);
                    if (totalDisplay) {
                        totalDisplay.textContent = total.toString().replace('.', ',');
                    }

                    return total;
                }
            </script>
        @endif
        {{--
##     ##  #######  ##     ## ##     ## ######## ##     ## ######## ##    ## ########  ######## #### ##    ## ####
###   ### ##     ## ##     ## ##     ## ##       ###   ### ##       ###   ##    ##     ##        ##  ###   ##  ##
#### #### ##     ## ##     ## ##     ## ##       #### #### ##       ####  ##    ##     ##        ##  ####  ##  ##
## ### ## ##     ## ##     ## ##     ## ######   ## ### ## ######   ## ## ##    ##     ######    ##  ## ## ##  ##
##     ## ##     ## ##     ##  ##   ##  ##       ##     ## ##       ##  ####    ##     ##        ##  ##  ####  ##
##     ## ##     ## ##     ##   ## ##   ##       ##     ## ##       ##   ###    ##     ##        ##  ##   ###  ##
##     ##  #######   #######     ###    ######## ##     ## ######## ##    ##    ##     ##       #### ##    ## ####
--}}
        <div class="flex-1 p-4 overflow-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th
                            class="text-left px-4 py-2 bg-gray-200 dark:bg-gray-800 font-medium text-gray-800 dark:text-gray-200 text-sm">
                            Matière</th>
                        <th
                            class="text-left px-4 py-2 bg-gray-200 dark:bg-gray-800 font-medium text-gray-800 dark:text-gray-200 text-sm">
                            Mouvement</th>
                        <th
                            class="text-left px-4 py-2 bg-gray-200 dark:bg-gray-800 font-medium text-gray-800 dark:text-gray-200 text-sm">
                            Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($changements_stock as $ligne)
                        <tr class="bg-gray-100 dark:bg-gray-750 ligne-mouvement-{{ $ligne->id }}"
                            id="mouvement-{{ $ligne->id }}">
                            <td colspan="3" class="px-4 py-2 font-medium">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <button type="button" id="history-button-{{ $ligne->id }}" class="mr-2 focus:outline-none">
                                            <x-icons.chevron-right id="history-arrow-{{ $ligne->id }}" class="transition-transform duration-200 text-gray-500" size="1" />
                                        </button>
                                        <x-ref-tooltip :matiere="$ligne->matiere">
                                            <x-slot:slot_item>
                                                <span class="font-bold text-gray-600 dark:text-gray-300">
                                                    {{ $ligne->ref_interne }}</span> {{ $ligne->matiere->designation }}
                                            </x-slot:slot_item>
                                        </x-ref-tooltip>
                                    </div>

                                    <button type="button" class="text-red-500 hover:text-red-700" x-data
                                        title="annuler"
                                        x-on:click="$dispatch('open-modal', 'delete-stock-movement-{{ $ligne->id }}')">
                                        <x-icons.delete-forever class="icons" size="1" />
                                    </button>

                                    <x-modal name="delete-stock-movement-{{ $ligne->id }}"
                                        title="Supprimer le mouvement">
                                        <div class="flex flex-col gap-4 p-4 text-gray-900 dark:text-gray-100">
                                            <div class="flex justify-between items-center">
                                                <h1 class="text-xl font-semibold">Supprimer le mouvement de stock</h1>
                                                <a x-on:click="$dispatch('close')">
                                                    <x-icons.close class="float-right mb-1 icons" size="1.5"
                                                        unfocus />
                                                </a>
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400">
                                                Êtes-vous sûr de vouloir supprimer ce mouvement de stock ?
                                                Cette action supprimera du stock de «
                                                {{ $ligne->matiere->designation }} »
                                                la quantité ajoutée par cette commande.
                                            </p>
                                            <div class="flex justify-end gap-4">
                                                <button class="text-white px-4 py-2 rounded-sm btn"
                                                    id="mouvement-cancel-button-{{ $ligne->id }}"
                                                    x-on:click="$dispatch('close')" type="button">Annuler</button>
                                                <button type="button"
                                                    onclick="deleteStockMovement({{ $ligne->id }})"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-sm hover:cursor-pointer">Supprimer</button>
                                            </div>
                                        </div>
                                    </x-modal>
                                </div>
                            </td>
                        </tr>

                        <tr class="ligne-mouvement-{{ $ligne->id }} details-mouvement-{{ $ligne->id }} hidden bg-white dark:bg-gray-800">
                            <td colspan="3" class="px-4 py-2">
                                @if($ligne->matiere->ref_valeur_unitaire)
                                <div class="flex w-full justify-end mb-2">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">
                                        Valeur unitaire :
                                        {{ $ligne->matiere->ref_valeur_unitaire }}
                                        {{ $ligne->matiere->unite->short }}</p>
                                </div>
                                @endif

                                <table class="w-full border-collapse border border-gray-400 dark:border-gray-700" id="history-table-{{ $ligne->id }}">
                                    <thead>
                                        <tr>
                                            <th class="p-1 text-sm bg-gray-200 dark:bg-gray-750 text-gray-800 dark:text-gray-200 border border-gray-400 dark:border-gray-700 border-r-0">
                                                Quantité
                                            </th>
                                            <th class="w-1 p-0 text-gray-800 bg-gray-200 dark:bg-gray-750 border-y border-gray-400 dark:border-gray-700">
                                            </th>
                                            <th class="p-1 text-sm border border-gray-400 dark:border-gray-700 bg-gray-200 dark:bg-gray-750 border-r-0 text-gray-800 dark:text-gray-200">
                                                Valeur unitaire ({{ $ligne->matiere->unite->short }})
                                            </th>
                                            <th class="w-1 p-0 bg-gray-200 dark:bg-gray-750 border-y border-gray-400 dark:border-gray-700">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ligne->mouvementsStock as $mouvement)
                                            <tr class="border-b border-gray-300 dark:border-gray-700" id="mouvement-row-{{ $mouvement->id }}">
                                                <td class="p-1 text-center">
                                                    <x-text-input type="number"
                                                        class="w-full border-0 focus:ring-0 p-1 text-center bg-transparent"
                                                        min="0" step="0.01"
                                                        value="{{ $mouvement->quantite }}"
                                                        onchange="updateMouvement({{ $mouvement->id }}, 'quantity', this)" />
                                                </td>
                                                <td class="w-1 text-center">X</td>
                                                <td class="p-1 text-center">
                                                    <x-text-input type="number"
                                                        class="w-full border-0 focus:ring-0 p-1 text-center bg-transparent"
                                                        min="0" step="0.01"
                                                        value="{{ $mouvement->valeur_unitaire }}"
                                                        onchange="updateMouvement({{ $mouvement->id }}, 'unit_value', this)" />
                                                </td>
                                                <td class="flex w-fit justify-center items-center pt-1">
                                                    <button type="button"
                                                        class="focus:outline-none"
                                                        title="Supprimer cette ligne"
                                                        onclick="deleteSingleMouvement({{ $mouvement->id }}, this)">
                                                        <x-icons.close size="2" class="icons" />
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-2 text-center">
                                    <button type="button" onclick="addHistoryRow({{ $ligne->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Ajouter une ligne
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @if ($ligne->is_stocke == false)
                            <tr class="ligne-mouvement-{{ $ligne->id }}">
                                <td class="px-4 py-2 pl-8 text-right">
                                    <x-icons.turn-left
                                        class="inline-block mr-2 -rotate-180 fill-gray-700 dark:fill-gray-400"
                                        size="1.5" />
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap" colspan="2">
                                    <span class="text-orange-500 italic">Non stocké</span>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @if ($changements_stock->isEmpty())
                        <tr>
                            <td colspan="3" class="py-6 text-center text-gray-700 dark:text-gray-400">
                                Aucun mouvement de stock trouvé.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-volet-modal>
<script>
    document.querySelectorAll('[id^="button-"]').forEach(function(element) {
        element.addEventListener('click', function() {
            const poste = element.id.split('-')[1];
            const arrowElement = document.getElementById('arrow-' + poste);
            const stockElement = document.getElementById('stock-' + poste);
            if (stockElement.classList.contains('hidden')) {
                stockElement.classList.remove('hidden');
                arrowElement.classList.add('rotate-90');
            } else {
                stockElement.classList.add('hidden');
                arrowElement.classList.remove('rotate-90');
            }
        });
    });

    document.querySelectorAll('[id^="history-button-"]').forEach(function(element) {
        element.addEventListener('click', function() {
            const id = element.id.split('-')[2];
            const arrowElement = document.getElementById('history-arrow-' + id);
            const detailsRows = document.querySelectorAll('.details-mouvement-' + id);

            detailsRows.forEach(row => {
                if (row.classList.contains('hidden')) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });

            if (arrowElement.classList.contains('rotate-90')) {
                arrowElement.classList.remove('rotate-90');
            } else {
                arrowElement.classList.add('rotate-90');
            }
        });
    });

    function updateMouvement(oldId, field, inputElement) {
        const value = inputElement.value;
        const route = '{{ route('cde.stock.mouvement.update', ['mouvement' => 'MOUVEMENT_ID']) }}'.replace('MOUVEMENT_ID', oldId);

        const data = {};
        data[field] = value;

        fetch(route, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showFlashMessageFromJs('Mouvement mis à jour', 2000, 'success');
                const newId = data.new_id;

                // Update the row ID and inputs
                const row = inputElement.closest('tr');
                const inputs = row.querySelectorAll('input');
                inputs.forEach(input => {
                    const oldOnchange = input.getAttribute('onchange');
                    const newOnchange = oldOnchange.replace(oldId, newId);
                    input.setAttribute('onchange', newOnchange);
                });
            } else {
                showFlashMessageFromJs(data.error || 'Erreur lors de la mise à jour', 3000, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFlashMessageFromJs('Erreur réseau', 3000, 'error');
        });
    }

    function addHistoryRow(ligneId) {
        const table = document.getElementById(`history-table-${ligneId}`);
        const tbody = table.querySelector('tbody');
        const newRow = document.createElement('tr');
        newRow.className = 'border-b border-gray-300 dark:border-gray-700';

        const closeIconSvg = `<svg xmlns="http://www.w3.org/2000/svg" height="2rem" viewBox="0 -960 960 960" width="2rem" fill="currentColor" class="icons"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>`;

        newRow.innerHTML = `
            <td class="p-1 text-center">
                <input type="number"
                    class="w-full border-0 focus:ring-0 p-1 text-center bg-transparent border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    min="0" step="0.01"
                    placeholder="Qté"
                    onchange="createMouvementIfReady(this, ${ligneId})" />
            </td>
            <td class="w-1 text-center">X</td>
            <td class="p-1 text-center">
                <input type="number"
                    class="w-full border-0 focus:ring-0 p-1 text-center bg-transparent border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    min="0" step="0.01"
                    placeholder="Val."
                    onchange="createMouvementIfReady(this, ${ligneId})" />
            </td>
            <td class="flex w-fit justify-center items-center pt-1">
                <button type="button"
                    class="focus:outline-none"
                    title="Supprimer cette ligne"
                    onclick="this.closest('tr').remove()">
                    ${closeIconSvg}
                </button>
            </td>
        `;
        tbody.appendChild(newRow);
    }

    function createMouvementIfReady(inputElement, ligneId) {
        const row = inputElement.closest('tr');
        const inputs = row.querySelectorAll('input');
        const quantityInput = inputs[0];
        const unitValueInput = inputs[1];

        const quantity = parseFloat(quantityInput.value);
        const unitValue = parseFloat(unitValueInput.value);

        if (quantity > 0 && unitValue >= 0) {
            const route = '{{ route('cde.stock.mouvement.store', ['cde' => $cde->id, 'ligne' => 'LIGNE_ID']) }}'.replace('LIGNE_ID', ligneId);

            fetch(route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    quantity: quantity,
                    unit_value: unitValue
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showFlashMessageFromJs('Mouvement ajouté', 2000, 'success');
                    const newId = data.mouvement_id;

                    quantityInput.setAttribute('onchange', `updateMouvement(${newId}, 'quantity', this)`);
                    unitValueInput.setAttribute('onchange', `updateMouvement(${newId}, 'unit_value', this)`);

                    const deleteBtn = row.querySelector('button');
                    deleteBtn.setAttribute('onclick', `deleteSingleMouvement(${newId}, this)`);
                } else {
                    showFlashMessageFromJs(data.error || 'Erreur lors de l\'ajout', 3000, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showFlashMessageFromJs('Erreur réseau', 3000, 'error');
            });
        }
    }

    function deleteSingleMouvement(mouvementId, buttonElement) {
        if (!confirm('Voulez-vous vraiment supprimer cette ligne ?')) return;

        const route = '{{ route('cde.stock.mouvement.destroy_single', ['mouvement' => 'MOUVEMENT_ID']) }}'.replace('MOUVEMENT_ID', mouvementId);

        fetch(route, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showFlashMessageFromJs('Mouvement supprimé', 2000, 'success');
                buttonElement.closest('tr').remove();
            } else {
                showFlashMessageFromJs(data.error || 'Erreur lors de la suppression', 3000, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFlashMessageFromJs('Erreur réseau', 3000, 'error');
        });
    }

    function deleteStockMovement(ligneId) {
        route = '{{ route('cde.stock.mouvement.destroy', ['cde' => $cde, 'ligne' => 'A_REMPLACER']) }}';
        route = route.replace('A_REMPLACER', ligneId);
        fetch(route, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Remove the rows from the table
                    const rows = document.querySelectorAll(`.ligne-mouvement-${ligneId}`);
                    rows.forEach(row => {
                        row.remove();
                    });
                    // Close the modal
                    const cancelButton = document.getElementById(`mouvement-cancel-button-${ligneId}`);
                    if (cancelButton) {
                        cancelButton.click();
                    }

                    // Show success flash message
                    showFlashMessageFromJs('Mouvement de stock supprimé avec succès.', 3000, 'success');
                    // Close the modal
                } else {
                    console.error('Erreur lors de la suppression');
                    // Show error flash message

                    response.json().then(json => {
                        let message = json.error || json.message || response.statusText;
                        showFlashMessageFromJs(message, 3000, 'error');
                    }).catch(() => {
                        response.text().then(text => {
                            showFlashMessageFromJs(text || response.statusText, 3000, 'error');
                        });
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showFlashMessageFromJs(error, 3000, 'error');
            });
    }
    document.addEventListener('DOMContentLoaded', function() {
        var show =
            {{ $cde->cdeLignes->where('ddpCdeStatut.nom', '!=', 'Annulée')->where('date_livraison_reelle', '!=', null)->whereNull('is_stocke')->whereNull('ligne_autre_id')->count() > 0 || $show_stock ? 1 : 0 }};
        if (show) {

            setTimeout(function() {
                document.getElementById('btn-open-enregistrement_stock').click();
            }, 100);

        }
    });
</script>
