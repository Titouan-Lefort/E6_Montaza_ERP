<x-app-layout>
    @section('title', isset($facture) ? 'Modifier la facture' : 'Ajouter la facture')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ isset($facture) ? 'Modifier la facture' : 'Ajouter la facture' }}
            </h2>
            <a href="{{ route('reparation.facture.index') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ isset($facture) ? route('reparation.facture.update', $facture->id) : route('reparation.facture.store') }}">
                        @csrf
                        @if(isset($facture))
                            @method('PATCH')
                        @endif

                        <!-- Numéro de facture -->
                        <div class="mb-6">
                            <label for="numero_facture" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Numero de facture <span class="text-red-500">*</span></label>
                            <input type="text" name="numero_facture" id="numero_facture" value="{{ old('numero_facture', $facture->numero_facture ?? '') }}" class="w-full px-4 py-2 border @error('numero_facture') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                            @error('numero_facture') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Date d'émission -->
                        <div class="mb-6">
                            <label for="date_emission" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date d'émission <span class="text-red-500">*</span></label>
                            <input type="date" name="date_emission" id="date_emission" value="{{ old('date_emission', isset($facture) && $facture->date_emission ? $facture->date_emission->format('Y-m-d') : now()->format('Y-m-d')) }}" class="w-full px-4 py-2 border @error('date_emission') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @error('date_emission') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Montant total -->
                        <div class="mb-6">
                            <label for="montant_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Montant total (€) <span class="text-red-500">*</span></label>
                            <input type="number" name="montant_total" id="montant_total" step="0.01" min="0" value="{{ old('montant_total', isset($facture) ? $facture->montant_total : '0.00') }}" class="w-full px-4 py-2 border @error('montant_total') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @error('montant_total') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Réparation associée (recherche) -->
                        <div class="mb-8">
                            <label for="reparation_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Réparation associée <span class="text-red-500">*</span></label>

                            <!-- Barre de recherche -->
                            <div class="mb-2">
                                <input type="text" id="reparation_search" placeholder="Rechercher une réparation (réf matériel, id...)" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-blue-500">
                            </div>

                            <select name="reparation_id" id="reparation_id" class="w-full px-4 py-2 border @error('reparation_id') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">-- Sélectionner une réparation --</option>
                                @if(isset($reparations))
                                    @foreach($reparations as $rep)
                                        @php
                                            $label = 'Réparation #' . $rep->id;
                                            if($rep->materiel) {
                                                $label .= ' - ' . $rep->materiel->reference . ($rep->materiel->designation ? ' (' . $rep->materiel->designation . ')' : '');
                                            }
                                            $statusRaw = $rep->status ?? 'pending';
                                            $statusLabel = ucfirst(str_replace('_', ' ', $statusRaw));
                                            // Couleurs simples pour les options (couleur du texte)
                                            $statusColor = match($statusRaw) {
                                                'pending' => '#D97706',
                                                'in_progress' => '#2563EB',
                                                'completed' => '#16A34A',
                                                'archived' => '#10B981',
                                                'closed' => '#6B7280',
                                                'inactif' => '#DC2626',
                                                'actif' => '#16A34A',
                                                'maintenance' => '#F59E0B',
                                                default => '#6B7280',
                                            };
                                        @endphp
                                        <option value="{{ $rep->id }}"
                                                data-status="{{ $statusRaw }}"
                                                data-status-label="{{ $statusLabel }}"
                                                {{ (string)old('reparation_id', isset($facture) ? $facture->reparation_id : '') === (string)$rep->id ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>

                            <!-- Badge couleur indiquant le statut de la réparation sélectionnée -->
                            <div id="reparation_status_badge" class="mt-2"></div>

                            @error('reparation_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex justify-between items-center space-x-4">
                            <a href="{{ route('reparation.facture.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">Annuler</a>
                            <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                {{ isset($facture) ? 'Enregistrer les modifications' : 'Ajouter la facture' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Filtre simple de recherche pour le select de réparations
    (function(){
        const input = document.getElementById('reparation_search');
        const select = document.getElementById('reparation_id');
        const badge = document.getElementById('reparation_status_badge');
        if (!input || !select || !badge) return;

        input.addEventListener('input', function(){
            const q = this.value.trim().toLowerCase();
            Array.from(select.options).forEach(function(opt){
                // Ne pas cacher l'option vide
                if(opt.value === '') { opt.hidden = false; return; }
                const text = (opt.text || '').toLowerCase();
                opt.hidden = q !== '' && text.indexOf(q) === -1;
            });
            // Si l'option sélectionnée est maintenant cachée, la réinitialiser
            const sel = select.selectedOptions[0];
            if (sel && sel.hidden) {
                select.value = '';
            }
            updateBadge();
        });

        select.addEventListener('change', updateBadge);

        function updateBadge() {
            const opt = select.selectedOptions[0];
            if (!opt || !opt.value) { badge.innerHTML = ''; return; }
            const status = opt.dataset.status || '';
            const label = opt.dataset.statusLabel || '';
            if (!status) { badge.innerHTML = ''; return; }

            const classes = {
                'pending': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800',
                'in_progress': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800',
                'completed': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800',
                'archived': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800',
                'closed': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800',
                'inactif': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800',
                'actif': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800',
                'maintenance': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800',
                'en_attente': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800',
                'en_cours': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800',
                'termine': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800',
                'terminee': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800',
                'archive': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800',
                'archivee': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800',
                'fermee': 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800'
            };
            const cls = classes[status] || 'inline-block px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
            badge.innerHTML = `<span class="${cls}">${label}</span>`;
        }

        document.addEventListener('DOMContentLoaded', updateBadge);
        updateBadge();
    })();
</script>
