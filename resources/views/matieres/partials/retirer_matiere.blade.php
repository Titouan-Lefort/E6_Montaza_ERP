<div
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-red-100 dark:bg-red-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Retirer matière</h2>
                </div>

                <!-- Sélecteur de mode -->
                <div class="mb-6">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-1 grid grid-cols-2 gap-1">
                        <button type="button" id="mode-standard-btn"
                            class="mode-selector flex-1 py-2 rounded-md text-center font-medium mode-active w-full">
                            Mode standard
                        </button>
                        @if ($matiere->typeAffichageStock() == 2)
                        <button type="button" id="mode-adjustment-btn"
                            class="mode-selector flex-1 py-2 rounded-md text-center font-medium">
                            Mode ajustement
                        </button>
                        @else
                        <x-tooltip  position="top" class="w-full" >
                            <x-slot name="slot_item"  >
                                <div class="w-full">
                                <button type="button" id="mode-adjustment-btn" disabled
                            class="mode-selector flex-1 py-2 rounded-md text-center font-medium w-full opacity-60">
                            Mode ajustement
                        </button>
                        </div>
                            </x-slot>
                            <x-slot name="slot_tooltip" >
                                Ce mode n'est pas utilisable pour les matières de ce type de stockage
                            </x-slot>
                        </x-tooltip>
                        @endif
                    </div>
                </div>

                <!-- Mode standard (formulaire existant) -->
                <div id="mode-standard-form">
                    <form action="{{ route('matieres.retirer', $matiere->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('POST')

                        <div class="flex flex-col sm:flex-row items-end gap-3">
                            <div class="w-full sm:w-auto">
                                <x-input-label value="Quantité à retirer" class="text-sm font-medium mb-1" />
                                <div class="relative rounded-md shadow-sm">
                                    <x-text-input type="number" name="quantite" id="retirer_quantite_standard"
                                        class="w-full pr-12 focus:ring-red-500 focus:border-red-500"
                                        value="{{ old('quantite') }}" placeholder="Quantité" step="{{ $matiere->typeAffichageStock() == 2 ? '1' : '0.01' }}"
                                        min="0" required />
                                    @if ($matiere->typeAffichageStock() !== 2)
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none bg-gray-100 dark:bg-gray-700 rounded-r-md border-l border-gray-300 dark:border-gray-600">
                                            <span
                                                class="text-gray-500 dark:text-gray-400 text-sm">{{ $matiere->unite->short }}</span>
                                        </div>
                                    @endif
                                </div>
                                @error('quantite')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            @if ($matiere->typeAffichageStock() == 2)
                                <p class="mb-2">X</p>
                                <div class="w-full sm:w-auto">
                                    <x-input-label value="Valeur unitaire" class="text-sm font-medium mb-1" />
                                    <select name="valeur_unitaire" id="valeur_unitaire_select_standard"
                                        class="rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-red-500 focus:border-red-500 block w-full">
                                        @foreach ($matiere->stock->where("quantite",'!=',0) as $stock)
                                            @if ($stock->valeur_unitaire > 0)
                                                <option value="{{ $stock->valeur_unitaire }}">
                                                    {{ formatNumber($stock->valeur_unitaire) }}
                                                    {{ $matiere->unite->short }} ({{ formatNumber($stock->quantite) }} disponible)</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('valeur_unitaire')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const quantityInput = document.getElementById('retirer_quantite_standard');
                                        const unitValueSelect = document.getElementById('valeur_unitaire_select_standard');
                                        const totalValueDiv = document.getElementById('total-value');
                                        const totalValueAmount = document.getElementById('total-value-amount');

                                        function updateTotalValue() {
                                            const quantity = parseFloat(quantityInput.value) || 0;
                                            const unitValue = parseFloat(unitValueSelect.value) || 0;
                                            const totalValue = quantity * unitValue;

                                            if (quantity > 0 && unitValue > 0) {
                                                totalValueDiv.classList.remove('hidden');
                                                totalValueAmount.textContent = formatNumber(totalValue) + ' {{ $matiere->unite->short }}';
                                            } else {
                                                totalValueDiv.classList.add('hidden');
                                            }
                                        }

                                        function formatNumber(num) {
                                            return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
                                        }

                                        quantityInput.addEventListener('input', updateTotalValue);
                                        unitValueSelect.addEventListener('change', updateTotalValue);
                                    });
                                </script>
                            @endif

                            <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white rounded-md transition-colors duration-200 flex items-center gap-2 h-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                                Retirer
                            </button>
                            <div id="total-value"
                                    class="text-sm text-gray-500 dark:text-gray-400 mt-1 hidden">
                                    total: <span id="total-value-amount"></span>

                                </div>
                        </div>

                        <div class="mt-3">
                            <x-input-label value="Motif du retrait" class="text-sm font-medium mb-1" />
                            <x-text-input type="text" name="motif"
                                class="w-full focus:ring-red-500 focus:border-red-500"
                                value="{{ old('motif') }}" placeholder="Indiquez le motif du retrait"
                                maxlength="50" required />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maximum 50 caractères</p>
                            @error('motif')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </form>
                </div>

                <!-- Mode ajustement (nouveau formulaire) -->
                <div id="mode-adjustment-form" class="hidden">
                    <form action="{{ route('matieres.ajuster', $matiere->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('POST')

                        <div class="mb-3">
                            <x-input-label value="Entrée de stock à ajuster" class="text-sm font-medium mb-1" />
                            <select name="stock_id" id="stock-select" class="rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-red-500 focus:border-red-500 block w-full mb-2">
                                <option value="">Sélectionnez une entrée de stock</option>
                                @foreach ($matiere->stock->where("quantite",'!=',0) as $stock)
                                    @if ($stock->valeur_unitaire > 0)
                                        <option value="{{ $stock->id }}" data-value="{{ $stock->valeur_unitaire }}" data-qty="{{ $stock->quantite }}">
                                            {{ formatNumber($stock->valeur_unitaire) }} {{ $matiere->unite->short }}
                                            ({{ formatNumber($stock->quantite) }} disponible)
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="stock-info" class="text-sm text-gray-500 dark:text-gray-400 hidden">
                                <p>Valeur unitaire actuelle: <span id="current-value" class="font-medium"></span></p>
                                <p>Quantité disponible: <span id="available-qty" class="font-medium"></span></p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <x-input-label value="Quantité à ajuster" class="text-sm font-medium mb-1" />
                            <div class="relative rounded-md shadow-sm">
                                <x-text-input type="number" name="quantite_ajuster" id="qty-to-adjust"
                                    class="w-full pr-12 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Quantité à ajuster" step="0.01"
                                    min="0" required />
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none bg-gray-100 dark:bg-gray-700 rounded-r-md border-l border-gray-300 dark:border-gray-600">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $matiere->unite->short }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Quantité du stock à ajuster (doit être inférieure ou égale à la quantité disponible)
                            </p>
                        </div>

                        <div class="mb-3">
                            <x-input-label value="Nouvelle valeur unitaire" class="text-sm font-medium mb-1" />
                            <div class="relative rounded-md shadow-sm">
                                <x-text-input type="number" name="nouvelle_valeur" id="new-value-input"
                                    class="w-full pr-12 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Nouvelle valeur unitaire" step="0.01"
                                    min="0" required />
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none bg-gray-100 dark:bg-gray-700 rounded-r-md border-l border-gray-300 dark:border-gray-600">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $matiere->unite->short }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                La nouvelle valeur doit être inférieure à la valeur actuelle
                            </p>
                        </div>

                        <div class="mb-3">
                            <x-input-label value="Motif de l'ajustement" class="text-sm font-medium mb-1" />
                            <x-text-input type="text" name="motif"
                                class="w-full focus:ring-red-500 focus:border-red-500"
                                placeholder="Indiquez le motif de l'ajustement" maxlength="50" required />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Maximum 50 caractères
                            </p>
                        </div>

                        <div>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white rounded-md transition-colors duration-200 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                                Ajuster la valeur unitaire
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Éléments de l'interface
                    const modeStandardBtn = document.getElementById('mode-standard-btn');
                    const modeAdjustmentBtn = document.getElementById('mode-adjustment-btn');
                    const modeStandardForm = document.getElementById('mode-standard-form');
                    const modeAdjustmentForm = document.getElementById('mode-adjustment-form');

                    // Gestion du mode standard
                    modeStandardBtn.addEventListener('click', function() {
                        modeStandardBtn.classList.add('mode-active');
                        modeAdjustmentBtn.classList.remove('mode-active');
                        modeStandardForm.classList.remove('hidden');
                        modeAdjustmentForm.classList.add('hidden');
                    });

                    // Gestion du mode ajustement
                    modeAdjustmentBtn.addEventListener('click', function() {
                        modeAdjustmentBtn.classList.add('mode-active');
                        modeStandardBtn.classList.remove('mode-active');
                        modeAdjustmentForm.classList.remove('hidden');
                        modeStandardForm.classList.add('hidden');
                    });

                    // Gestion du sélecteur de stock pour le mode ajustement
                    const stockSelect = document.getElementById('stock-select');
                    const stockInfo = document.getElementById('stock-info');
                    const currentValue = document.getElementById('current-value');
                    const availableQty = document.getElementById('available-qty');
                    const newValueInput = document.getElementById('new-value-input');
                    const qtyToAdjust = document.getElementById('qty-to-adjust');

                    stockSelect.addEventListener('change', function() {
                        if (this.value) {
                            const selectedOption = this.options[this.selectedIndex];
                            const value = selectedOption.dataset.value;
                            const qty = selectedOption.dataset.qty;

                            currentValue.textContent = value + ' {{ $matiere->unite->short }}';
                            availableQty.textContent = qty;
                            stockInfo.classList.remove('hidden');

                            // Pré-remplir avec les valeurs pour faciliter l'ajustement
                            newValueInput.value = value;
                            newValueInput.max = value;
                            qtyToAdjust.value = qty; // Pré-remplir avec la quantité disponible
                            qtyToAdjust.max = qty;   // Limiter la quantité à ajuster
                        } else {
                            stockInfo.classList.add('hidden');
                            newValueInput.value = '';
                            qtyToAdjust.value = '';
                        }
                    });

                    // Validation supplémentaire pour la quantité à ajuster
                    qtyToAdjust.addEventListener('input', function() {
                        const max = parseFloat(this.max);
                        const value = parseFloat(this.value);

                        if (value > max) {
                            this.value = max;
                        }
                    });
                });
            </script>

            <style>
                .mode-selector {
                    transition: all 0.2s ease-in-out;
                }
                .mode-active {
                    background-color: rgba(239, 68, 68, 0.2);
                    color: rgb(239, 68, 68);
                    font-weight: 600;
                }
                .dark .mode-active {
                    background-color: rgba(239, 68, 68, 0.3);
                    color: rgb(248, 113, 113);
                }
            </style>
