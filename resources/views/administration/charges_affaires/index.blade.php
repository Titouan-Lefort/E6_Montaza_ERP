<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestion des Chargés d\'affaires') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        trimestre: 'T1-2026',
        sortOrder: 'desc',
        chargesAffaires: {
            'T1-2026': [
                { nom: 'Jean Dupont', total: 96350 },
                { nom: 'Marie Curie', total: 182250 }
            ],
            'T2-2026': [
                { nom: 'Jean Dupont', total: 89700 },
                { nom: 'Marie Curie', total: 157000 }
            ],
            'T3-2026': [
                { nom: 'Jean Dupont', total: 0 },
                { nom: 'Marie Curie', total: 74200 }
            ],
            'T1-2025': [
                { nom: 'Jean Dupont', total: 12200 },
                { nom: 'Marie Curie', total: 74200 }
            ],
            'T2-2025': [
                { nom: 'Jean Dupont', total: 12200 },
                { nom: 'Marie Curie', total: 74200 }
            ],
            'T3-2025': [
                { nom: 'Jean Dupont', total: 12200 },
                { nom: 'Marie Curie', total: 74200 }
            ]
        },
        get sortedChargesAffaires() {
            const data = [...this.chargesAffaires[this.trimestre]];
            return data.sort((a, b) => {
                return this.sortOrder === 'desc' ? b.total - a.total : a.total - b.total;
            });
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-8">

            <!-- Sélecteurs -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Sélecteur de trimestre -->
                        <div>
                            <label for="trimestre" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                Sélectionner le trimestre
                            </label>
                            <select
                                id="trimestre"
                                x-model="trimestre"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="T1-2026">T1 2026 (Jan - Avr)</option>
                                <option value="T2-2026">T2 2026 (Mai - Août)</option>
                                <option value="T3-2026">T3 2026 (Sept - Déc)</option>
                                <option value="T1-2025">T1 2025 (Jan - Avr)</option>
                                <option value="T2-2025">T2 2025 (Mai - Août)</option>
                                <option value="T3-2025">T3 2025 (Sept - Déc)</option>
                            </select>
                        </div>

                        <!-- Sélecteur d'ordre de tri -->
                        <div>
                            <label for="sortOrder" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                Ordre de tri par montant
                            </label>
                            <select
                                id="sortOrder"
                                x-model="sortOrder"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="desc">Plus élevé → Moins élevé</option>
                                <option value="asc">Moins élevé → Plus élevé</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chargé d'affaire 1: Jean Dupont -->
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                :style="`order: ${(() => {
                    const jean = chargesAffaires[trimestre].find(c => c.nom === 'Jean Dupont');
                    const marie = chargesAffaires[trimestre].find(c => c.nom === 'Marie Curie');
                    if (sortOrder === 'desc') {
                        return jean.total >= marie.total ? 1 : 2;
                    } else {
                        return jean.total <= marie.total ? 1 : 2;
                    }
                })()}`"
            >
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Jean Dupont</h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400" x-text="trimestre"></span>
                    </div>

                    <!-- Données T1-2026 -->
                    <div x-show="trimestre === 'T1-2026'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-5 border border-green-200 dark:border-green-800">
                            <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Devis validés</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li>• Devis #2026-001 - 15 000 €</li>
                                <li>• Devis #2026-003 - 22 500 €</li>
                                <li>• Devis #2026-007 - 8 750 €</li>
                            </ul>
                            <p class="mt-4 text-xs text-green-700 dark:text-green-300 font-medium">3 devis</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-5 border border-blue-200 dark:border-blue-800">
                            <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Devis en cours</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li>• Devis #2026-012 - 18 900 €</li>
                                <li>• Devis #2026-015 - 31 200 €</li>
                            </ul>
                            <p class="mt-4 text-xs text-blue-700 dark:text-blue-300 font-medium">2 devis</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-5 border border-purple-200 dark:border-purple-800">
                            <h4 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-3">Total rapporté</h4>
                            <p class="text-4xl font-bold text-purple-900 dark:text-purple-100">96 350 €</p>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Sur 5 devis au total</p>
                        </div>
                    </div>

                    <!-- Données T2-2026 -->
                    <div x-show="trimestre === 'T2-2026'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-5 border border-green-200 dark:border-green-800">
                            <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Devis validés</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li>• Devis #2026-021 - 28 000 €</li>
                                <li>• Devis #2026-024 - 16 400 €</li>
                            </ul>
                            <p class="mt-4 text-xs text-green-700 dark:text-green-300 font-medium">2 devis</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-5 border border-blue-200 dark:border-blue-800">
                            <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Devis en cours</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li>• Devis #2026-028 - 45 300 €</li>
                            </ul>
                            <p class="mt-4 text-xs text-blue-700 dark:text-blue-300 font-medium">1 devis</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-5 border border-purple-200 dark:border-purple-800">
                            <h4 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-3">Total rapporté</h4>
                            <p class="text-4xl font-bold text-purple-900 dark:text-purple-100">89 700 €</p>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Sur 3 devis au total</p>
                        </div>
                    </div>

                    <!-- Données T3-2026 -->
                    <div x-show="trimestre === 'T3-2026'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-5 border border-green-200 dark:border-green-800">
                            <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Devis validés</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Aucun devis validé</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-5 border border-blue-200 dark:border-blue-800">
                            <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Devis en cours</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Aucun devis en cours</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-5 border border-purple-200 dark:border-purple-800">
                            <h4 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-3">Total rapporté</h4>
                            <p class="text-4xl font-bold text-purple-900 dark:text-purple-100">0 €</p>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Sur 0 devis au total</p>
                        </div>
                    </div>

                    <!-- Données autres trimestres -->
                    <div x-show="!['T1-2026', 'T2-2026', 'T3-2026'].includes(trimestre)" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-5 border border-green-200 dark:border-green-800">
                            <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Devis validés</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li>• Devis #2025-089 - 12 200 €</li>
                            </ul>
                            <p class="mt-4 text-xs text-green-700 dark:text-green-300 font-medium">1 devis</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-5 border border-blue-200 dark:border-blue-800">
                            <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Devis en cours</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Aucun devis en cours</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-5 border border-purple-200 dark:border-purple-800">
                            <h4 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-3">Total rapporté</h4>
                            <p class="text-4xl font-bold text-purple-900 dark:text-purple-100">12 200 €</p>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Sur 1 devis au total</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chargé d'affaire 2: Marie Curie -->
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                :style="`order: ${(() => {
                    const jean = chargesAffaires[trimestre].find(c => c.nom === 'Jean Dupont');
                    const marie = chargesAffaires[trimestre].find(c => c.nom === 'Marie Curie');
                    if (sortOrder === 'desc') {
                        return marie.total >= jean.total ? 1 : 2;
                    } else {
                        return marie.total <= jean.total ? 1 : 2;
                    }
                })()}`"
            >
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Marie Curie</h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400" x-text="trimestre"></span>
                    </div>

                    <!-- Données T1-2026 -->
                    <div x-show="trimestre === 'T1-2026'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-5 border border-green-200 dark:border-green-800">
                            <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Devis validés</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li>• Devis #2026-002 - 42 000 €</li>
                                <li>• Devis #2026-005 - 19 800 €</li>
                                <li>• Devis #2026-009 - 27 300 €</li>
                                <li>• Devis #2026-011 - 12 450 €</li>
                            </ul>
                            <p class="mt-4 text-xs text-green-700 dark:text-green-300 font-medium">4 devis</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-5 border border-blue-200 dark:border-blue-800">
                            <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Devis en cours</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li>• Devis #2026-014 - 25 600 €</li>
                                <li>• Devis #2026-016 - 38 900 €</li>
                                <li>• Devis #2026-018 - 16 200 €</li>
                            </ul>
                            <p class="mt-4 text-xs text-blue-700 dark:text-blue-300 font-medium">3 devis</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-5 border border-purple-200 dark:border-purple-800">
                            <h4 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-3">Total rapporté</h4>
                            <p class="text-4xl font-bold text-purple-900 dark:text-purple-100">182 250 €</p>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Sur 7 devis au total</p>
                        </div>
                    </div>

                    <!-- Données T2-2026 -->
                    <div x-show="trimestre === 'T2-2026'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-5 border border-green-200 dark:border-green-800">
                            <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Devis validés</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li>• Devis #2026-032 - 38 500 €</li>
                                <li>• Devis #2026-035 - 21 700 €</li>
                                <li>• Devis #2026-037 - 14 900 €</li>
                            </ul>
                            <p class="mt-4 text-xs text-green-700 dark:text-green-300 font-medium">3 devis</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-5 border border-blue-200 dark:border-blue-800">
                            <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Devis en cours</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li>• Devis #2026-041 - 52 800 €</li>
                                <li>• Devis #2026-043 - 29 100 €</li>
                            </ul>
                            <p class="mt-4 text-xs text-blue-700 dark:text-blue-300 font-medium">2 devis</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-5 border border-purple-200 dark:border-purple-800">
                            <h4 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-3">Total rapporté</h4>
                            <p class="text-4xl font-bold text-purple-900 dark:text-purple-100">157 000 €</p>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Sur 5 devis au total</p>
                        </div>
                    </div>

                    <!-- Données T3-2026 et autres trimestres -->
                    <div x-show="!['T1-2026', 'T2-2026'].includes(trimestre)" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-5 border border-green-200 dark:border-green-800">
                            <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3">Devis validés</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li>• Devis #2025-078 - 18 200 €</li>
                                <li>• Devis #2025-082 - 24 600 €</li>
                            </ul>
                            <p class="mt-4 text-xs text-green-700 dark:text-green-300 font-medium">2 devis</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-5 border border-blue-200 dark:border-blue-800">
                            <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Devis en cours</h4>
                            <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <li>• Devis #2025-085 - 31 400 €</li>
                            </ul>
                            <p class="mt-4 text-xs text-blue-700 dark:text-blue-300 font-medium">1 devis</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-5 border border-purple-200 dark:border-purple-800">
                            <h4 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-3">Total rapporté</h4>
                            <p class="text-4xl font-bold text-purple-900 dark:text-purple-100">74 200 €</p>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Sur 3 devis au total</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
