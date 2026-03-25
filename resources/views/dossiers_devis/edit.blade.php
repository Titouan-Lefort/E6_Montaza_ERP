<x-app-layout>
    @section('title', 'Modifier le Dossier - ' . $dossierDevis->code)
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Modifier le Dossier de Devis
            </h2>
            <a href="{{ route('dossiers_devis.show', $dossierDevis) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Annuler
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('dossiers_devis.update', $dossierDevis) }}" class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div class="md:col-span-2">
                        <x-input-label for="nom" value="Nom du dossier" />
                        <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full" :value="old('nom', $dossierDevis->nom)" required autofocus />
                        @error('nom')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Affaire -->
                    <div>
                        <x-input-label for="affaire_id" value="Affaire (optionnel)" />
                        <select id="affaire_id" name="affaire_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring">
                            <option value="">-- Aucune affaire --</option>
                            @foreach($affaires as $affaire)
                                <option value="{{ $affaire->id }}" {{ old('affaire_id', $dossierDevis->affaire_id) == $affaire->id ? 'selected' : '' }}>
                                    {{ $affaire->code }} - {{ $affaire->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('affaire_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Client -->
                    <div>
                        <x-input-label for="societe_id" value="Client (optionnel)" />
                        <select id="societe_id" name="societe_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring">
                            <option value="">-- Aucun client --</option>
                            @foreach($societes as $societe)
                                <option value="{{ $societe->id }}" {{ old('societe_id', $dossierDevis->societe_id) == $societe->id ? 'selected' : '' }}>
                                    {{ $societe->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('societe_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Référence projet -->
                    <div>
                        <x-input-label for="reference_projet" value="Référence projet (optionnel)" />
                        <x-text-input id="reference_projet" name="reference_projet" type="text" class="mt-1 block w-full" :value="old('reference_projet', $dossierDevis->reference_projet)" />
                        @error('reference_projet')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lieu d'intervention -->
                    <div>
                        <x-input-label for="lieu_intervention" value="Lieu d'intervention (optionnel)" />
                        <x-text-input id="lieu_intervention" name="lieu_intervention" type="text" class="mt-1 block w-full" :value="old('lieu_intervention', $dossierDevis->lieu_intervention)" />
                        @error('lieu_intervention')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Statut -->
                    <div class="md:col-span-2">
                        <x-input-label for="statut" value="Statut" />
                        <select id="statut" name="statut" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring">
                            <option value="quantitatif" {{ old('statut', $dossierDevis->statut) == 'quantitatif' ? 'selected' : '' }}>Quantitatif</option>
                            <option value="en_devis" {{ old('statut', $dossierDevis->statut) == 'en_devis' ? 'selected' : '' }}>En devis</option>
                            <option value="valide" {{ old('statut', $dossierDevis->statut) == 'valide' ? 'selected' : '' }}>Validé</option>
                            <option value="archive" {{ old('statut', $dossierDevis->statut) == 'archive' ? 'selected' : '' }}>Archivé</option>
                        </select>
                        @error('statut')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <x-input-label for="description" value="Description (optionnel)" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring">{{ old('description', $dossierDevis->description) }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('dossiers_devis.show', $dossierDevis) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                        Annuler
                    </a>
                    <x-primary-button>
                        Enregistrer
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
