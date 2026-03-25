<div
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-green-100 dark:bg-green-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Ajouter matière</h2>
                </div>
                <form action="{{ route('matieres.ajouter', $matiere->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('POST')

                    <div class="flex flex-col sm:flex-row items-end gap-3">
                        <div class="w-full sm:w-auto">
                            <x-input-label value="Quantité à ajouter" class="text-sm font-medium mb-1" />
                            <div class="relative rounded-md shadow-sm">
                                <x-text-input type="number" name="quantite" id="quantite_ajouter"
                                    class="w-full pr-12 focus:ring-green-500 focus:border-green-500"
                                    value="{{ old('quantite') }}" placeholder="Quantité" step="0.01"
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

                        @if ($matiere->typeAffichageStock() !== 1)
                            <p class="mb-2">X</p>
                            <div class="w-full sm:w-auto">
                                <x-input-label value="Valeur unitaire" class="text-sm font-medium mb-1" />
                                <div class="relative rounded-md shadow-sm">
                                    <x-text-input type="number" name="valeur_unitaire" id="valeur_unitaire_ajouter"
                                        class="w-full focus:ring-green-500 focus:border-green-500"
                                        value="{{ old('valeur_unitaire', $matiere->ref_valeur_unitaire) }}" placeholder="Valeur unitaire" step="0.01"
                                        min="0" required />
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none bg-gray-100 dark:bg-gray-700 rounded-r-md border-l border-gray-300 dark:border-gray-600">
                                        <span
                                            class="text-gray-500 dark:text-gray-400 text-sm">{{ $matiere->unite->short }}</span>
                                    </div>
                                </div>
                                @error('valeur_unitaire')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const quantityInput = document.getElementById('quantite_ajouter');
                                        const unitValueSelect = document.getElementById('valeur_unitaire_ajouter');
                                        const totalValueDiv = document.getElementById('total-value-ajout');
                                        const totalValueAmount = document.getElementById('total-value-amount-ajout');

                                        function updateTotalValueAjout() {
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

                                        quantityInput.addEventListener('input', updateTotalValueAjout);
                                        unitValueSelect.addEventListener('change', updateTotalValueAjout);
                                    });
                                </script>
                        @endif
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white rounded-md transition-colors duration-200 flex items-center gap-2 h-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Ajouter
                        </button>
                        <div id="total-value-ajout"
                                    class="text-sm text-gray-500 dark:text-gray-400 mt-1 hidden">
                                    total: <span id="total-value-amount-ajout"></span>

                                </div>
                    </div>

                    <div class="mt-3">
                        <x-input-label value="Motif de l'ajout" class="text-sm font-medium mb-1" />
                        <x-text-input type="text" name="motif"
                            class="w-full focus:ring-green-500 focus:border-green-500"
                            value="{{ old('motif') }}" placeholder="Indiquez le motif de l'ajout"
                            maxlength="50" required />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maximum 50 caractères</p>
                        @error('motif')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </form>
            </div>
