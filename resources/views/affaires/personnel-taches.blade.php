<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Tâches de {{ $personnel->prenom }} {{ $personnel->nom }} - {{ $affaire->code }}
            </h2>
            <a href="{{ route('affaires.show', $affaire) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                Retour à l'affaire
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Messages de succès et d'erreur -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-900 dark:border-green-700 dark:text-green-200" role="alert">
                    <strong class="font-bold">Succès !</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative dark:bg-red-900 dark:border-red-700 dark:text-red-200" role="alert">
                    <strong class="font-bold">Erreur !</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Informations sur l'assignation -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informations d'assignation</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Rôle</label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $pivotRecord->role ?? 'Non défini' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Période</label>
                            <p class="text-gray-900 dark:text-gray-100">
                                {{ $pivotRecord->date_debut ? $pivotRecord->date_debut->format('d/m/Y') : '?' }}
                                →
                                {{ $pivotRecord->date_fin ? $pivotRecord->date_fin->format('d/m/Y') : '...' }}
                            </p>
                        </div>
                        @if($pivotRecord->notes)
                            <div class="md:col-span-3">
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</label>
                                <p class="text-gray-900 dark:text-gray-100 italic">{{ $pivotRecord->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bouton Ajouter une tâche -->
            <div class="mb-6">
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-tache-modal')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter une tâche
                </button>
            </div>

            <!-- Liste des tâches -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Liste des tâches</h3>

                    @if($taches->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune tâche définie.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($taches as $tache)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start gap-3 flex-1">
                                            <!-- Checkbox pour marquer comme terminé -->
                                            <div class="pt-1">
                                                @if($tache->statut == 'termine')
                                                    <form method="POST" action="{{ route('affaires.personnel.taches.reopen', [$affaire, $personnel, $tache]) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" title="Réouvrir la tâche" class="w-6 h-6 rounded border-2 border-green-600 bg-green-600 text-white flex items-center justify-center hover:bg-green-700 transition">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('affaires.personnel.taches.complete', [$affaire, $personnel, $tache]) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" title="Marquer comme terminé" class="w-6 h-6 rounded border-2 border-gray-300 dark:border-gray-600 hover:border-green-600 dark:hover:border-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 transition">
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>

                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <h4 class="text-base font-medium text-gray-900 dark:text-gray-100 {{ $tache->statut == 'termine' ? 'line-through opacity-60' : '' }}">{{ $tache->titre }}</h4>

                                                    @if($tache->statut == 'termine')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Terminé</span>
                                                    @elseif($tache->statut == 'en_cours')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">En cours</span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">À faire</span>
                                                    @endif

                                                    @if($tache->priorite == 'haute')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Haute priorité</span>
                                                    @elseif($tache->priorite == 'basse')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Basse priorité</span>
                                                    @endif
                                                </div>

                                                @if($tache->description)
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 {{ $tache->statut == 'termine' ? 'opacity-60' : '' }}">{{ $tache->description }}</p>
                                                @endif

                                                <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                                    <span>📅 {{ $tache->date_debut->format('d/m/Y') }} ({{ ($tache->creneau_debut ?? 'matin') == 'matin' ? 'Matin' : 'Après-midi' }}) → {{ $tache->date_fin->format('d/m/Y') }} ({{ ($tache->creneau_fin ?? 'apres_midi') == 'matin' ? 'Matin' : 'Après-midi' }})</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex gap-2 ml-4">
                                            <button onclick="openEditTacheModal({{ $tache->id }}, '{{ $tache->titre }}', '{{ $tache->description }}', '{{ $tache->date_debut->format('Y-m-d') }}', '{{ $tache->creneau_debut ?? 'matin' }}', '{{ $tache->date_fin->format('Y-m-d') }}', '{{ $tache->creneau_fin ?? 'apres_midi' }}', '{{ $tache->statut }}', '{{ $tache->priorite }}')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Modifier
                                            </button>
                                            <form method="POST" action="{{ route('affaires.personnel.taches.delete', [$affaire, $personnel, $tache]) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter une tâche -->
    <x-modal name="add-tache-modal" :show="false" maxWidth="2xl">
        <form method="POST" action="{{ route('affaires.personnel.taches.store', [$affaire, $personnel]) }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Ajouter une tâche
            </h2>

            <div class="space-y-4">
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Titre *</label>
                    <input type="text" name="titre" id="titre" value="{{ old('titre') }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <x-input-error :messages="$errors->get('titre')" class="mt-2" />
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de début *</label>
                        <input type="date" name="date_debut" id="date_debut" required value="{{ old('date_debut', $pivotRecord->date_debut ? $pivotRecord->date_debut->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('date_debut')" class="mt-2" />
                    </div>
                    <div>
                        <label for="creneau_debut" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Créneau début *</label>
                        <select name="creneau_debut" id="creneau_debut" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="matin" {{ old('creneau_debut', 'matin') == 'matin' ? 'selected' : '' }}>Matin (8h-11h)</option>
                            <option value="apres_midi" {{ old('creneau_debut') == 'apres_midi' ? 'selected' : '' }}>Après-midi (13h-16h)</option>
                        </select>
                        <x-input-error :messages="$errors->get('creneau_debut')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de fin *</label>
                        <input type="date" name="date_fin" id="date_fin" required value="{{ old('date_fin', $pivotRecord->date_fin ? $pivotRecord->date_fin->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('date_fin')" class="mt-2" />
                    </div>
                    <div>
                        <label for="creneau_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Créneau fin *</label>
                        <select name="creneau_fin" id="creneau_fin" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="matin" {{ old('creneau_fin') == 'matin' ? 'selected' : '' }}>Matin (8h-11h)</option>
                            <option value="apres_midi" {{ old('creneau_fin', 'apres_midi') == 'apres_midi' ? 'selected' : '' }}>Après-midi (13h-16h)</option>
                        </select>
                        <x-input-error :messages="$errors->get('creneau_fin')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut</label>
                        <select name="statut" id="statut" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="a_faire">À faire</option>
                            <option value="en_cours">En cours</option>
                            <option value="termine">Terminé</option>
                        </select>
                    </div>
                    <div>
                        <label for="priorite" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priorité</label>
                        <select name="priorite" id="priorite" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="basse">Basse</option>
                            <option value="normale" selected>Normale</option>
                            <option value="haute">Haute</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Annuler
                </x-secondary-button>
                <x-primary-button>
                    Ajouter la tâche
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Modifier une tâche -->
    <x-modal name="edit-tache-modal" :show="false" maxWidth="2xl">
        <form id="edit-tache-form" method="POST" class="p-6">
            @csrf
            @method('PATCH')
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Modifier la tâche
            </h2>

            <div class="space-y-4">
                <div>
                    <label for="edit_titre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Titre *</label>
                    <input type="text" name="titre" id="edit_titre" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <x-input-error :messages="$errors->get('titre')" class="mt-2" />
                </div>

                <div>
                    <label for="edit_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="edit_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_date_debut" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de début *</label>
                        <input type="date" name="date_debut" id="edit_date_debut" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('date_debut')" class="mt-2" />
                    </div>
                    <div>
                        <label for="edit_creneau_debut" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Créneau début *</label>
                        <select name="creneau_debut" id="edit_creneau_debut" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="matin">Matin (8h-11h)</option>
                            <option value="apres_midi">Après-midi (13h-16h)</option>
                        </select>
                        <x-input-error :messages="$errors->get('creneau_debut')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_date_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de fin *</label>
                        <input type="date" name="date_fin" id="edit_date_fin" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('date_fin')" class="mt-2" />
                    </div>
                    <div>
                        <label for="edit_creneau_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Créneau fin *</label>
                        <select name="creneau_fin" id="edit_creneau_fin" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="matin">Matin (8h-11h)</option>
                            <option value="apres_midi">Après-midi (13h-16h)</option>
                        </select>
                        <x-input-error :messages="$errors->get('creneau_fin')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_statut" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut</label>
                        <select name="statut" id="edit_statut" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="a_faire">À faire</option>
                            <option value="en_cours">En cours</option>
                            <option value="termine">Terminé</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_priorite" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priorité</label>
                        <select name="priorite" id="edit_priorite" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="basse">Basse</option>
                            <option value="normale">Normale</option>
                            <option value="haute">Haute</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Annuler
                </x-secondary-button>
                <x-primary-button>
                    Mettre à jour
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <script>
        function openEditTacheModal(id, titre, description, dateDebut, creneauDebut, dateFin, creneauFin, statut, priorite) {
            document.getElementById('edit-tache-form').action = '{{ route("affaires.personnel.taches.update", [$affaire, $personnel, "__ID__"]) }}'.replace('__ID__', id);
            document.getElementById('edit_titre').value = titre;
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_date_debut').value = dateDebut;
            document.getElementById('edit_creneau_debut').value = creneauDebut;
            document.getElementById('edit_date_fin').value = dateFin;
            document.getElementById('edit_creneau_fin').value = creneauFin;
            document.getElementById('edit_statut').value = statut;
            document.getElementById('edit_priorite').value = priorite;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-tache-modal' }));
        }

        // Rouvrir automatiquement le modal si des erreurs de validation existent
        document.addEventListener('DOMContentLoaded', function() {
            @if($errors->any())
                // Si des erreurs existent, rouvrir le modal approprié
                // On suppose que c'est pour l'ajout si on ne peut pas distinguer précisément
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'add-tache-modal' }));
            @endif
        });
    </script>
</x-app-layout>
