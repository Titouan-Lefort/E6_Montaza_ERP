<x-app-layout>
    @section('title', 'Affaire ' . $affaire->code)
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $affaire->nom }} <span class="text-gray-500 text-sm font-normal">({{ $affaire->code }})</span>
                </h2>
                <div class="mt-1 flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $affaire->statut_color }}-100 text-{{ $affaire->statut_color }}-800 dark:bg-{{ $affaire->statut_color }}-900 dark:text-{{ $affaire->statut_color }}-200">
                        {{ $affaire->statut_label }}
                    </span>
                    @if($affaire->date_debut && $affaire->date_fin_prevue)
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Du {{ \Carbon\Carbon::parse($affaire->date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($affaire->date_fin_prevue)->format('d/m/Y') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-2">
                <a href="{{ route('affaires.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Retour
                </a>
                <a href="{{ route('affaires.suivi_detail', $affaire) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Suivi
                </a>
                @if($affaire->statut !== \App\Models\Affaire::STATUT_TERMINE && $affaire->statut !== \App\Models\Affaire::STATUT_ARCHIVE)
                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'update-status-modal')" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                        Changer Statut
                    </button>
                @endif
                <a href="{{ route('affaires.edit', $affaire) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Modifier
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Debug des erreurs (à retirer après test) -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Erreurs de validation :</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Budget</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($affaire->budget, 2, ',', ' ') }} €</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Engagé (CDE)</div>
                    <div class="mt-1 text-2xl font-semibold {{ $affaire->total_ht > $affaire->budget ? 'text-red-600' : 'text-green-600' }}">
                        {{ number_format($affaire->total_ht, 2, ',', ' ') }} €
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Commandes</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $affaire->cdes->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Matériels</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $affaire->materiels->where('pivot.statut', '!=', 'termine')->count() }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Section Commerciale -->
                <div class="space-y-6">
                    <!-- Commandes -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Commandes Fournisseurs</h3>
                            @if($affaire->statut !== \App\Models\Affaire::STATUT_TERMINE && $affaire->statut !== \App\Models\Affaire::STATUT_ARCHIVE)
                                <a href="{{ route('cde.create', ['affaire_id' => $affaire->id]) }}" class="text-sm text-blue-600 hover:text-blue-500">Nouvelle CDE</a>
                            @endif
                        </div>
                        <div class="p-6">
                            @if($affaire->cdes->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune commande liée.</p>
                            @else
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($affaire->cdes as $cde)
                                        <li class="py-3 flex justify-between items-center">
                                            <div>
                                                <a href="{{ route('cde.show', $cde) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">{{ $cde->code }}</a>
                                                <p class="text-xs text-gray-500">{{ $cde->societe?->raison_sociale ?? 'Fournisseur inconnu' }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ number_format($cde->total_ht, 2, ',', ' ') }} €</span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $cde->statut->nom ?? '-' }}
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <!-- Demandes de Prix -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Demandes de Prix</h3>
                            @if($affaire->statut !== \App\Models\Affaire::STATUT_TERMINE && $affaire->statut !== \App\Models\Affaire::STATUT_ARCHIVE)
                                <a href="{{ route('ddp.create', ['affaire_id' => $affaire->id]) }}" class="text-sm text-blue-600 hover:text-blue-500">Nouvelle DDP</a>
                            @endif
                        </div>
                        <div class="p-6">
                            @if($affaire->ddps->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune demande de prix liée.</p>
                            @else
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($affaire->ddps as $ddp)
                                        <li class="py-3 flex justify-between items-center">
                                            <div>
                                                <a href="{{ route('ddp.show', $ddp) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">{{ $ddp->code }}</a>
                                                <p class="text-xs text-gray-500">{{ $ddp->nom }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $ddp->statut->nom ?? '-' }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <!-- Devis Tuyauterie -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Devis Tuyauterie</h3>
                            @if($affaire->statut !== \App\Models\Affaire::STATUT_TERMINE && $affaire->statut !== \App\Models\Affaire::STATUT_ARCHIVE)
                                <div class="flex gap-2">
                                    <button onclick="openAssignDevisModal()" class="text-sm text-green-600 hover:text-green-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Assigner un devis
                                    </button>
                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                    <a href="{{ route('devis_tuyauterie.create', ['affaire_id' => $affaire->id]) }}" class="text-sm text-blue-600 hover:text-blue-500">Nouveau Devis</a>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            @if($affaire->devisTuyauteries->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Aucun devis de tuyauterie lié.</p>
                            @else
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($affaire->devisTuyauteries as $devis)
                                        <li class="py-3 flex justify-between items-center">
                                            <div class="flex-1">
                                                <a href="{{ route('devis_tuyauterie.show', $devis) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">{{ $devis->reference_projet }}</a>
                                                <p class="text-xs text-gray-500">{{ $devis->client_nom ?? 'Client inconnu' }}</p>
                                            </div>
                                            <div class="text-right flex items-center gap-4">
                                                <div>
                                                    <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ number_format($devis->total_ttc, 2, ',', ' ') }} €</span>
                                                    <span class="text-xs text-gray-500">{{ $devis->date_emission ? \Carbon\Carbon::parse($devis->date_emission)->format('d/m/Y') : '-' }}</span>
                                                </div>
                                                @if($affaire->statut !== \App\Models\Affaire::STATUT_TERMINE && $affaire->statut !== \App\Models\Affaire::STATUT_ARCHIVE)
                                                    <form method="POST" action="{{ route('affaires.unassign_devis', [$affaire, $devis]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium" onclick="return confirm('Êtes-vous sûr de vouloir désassigner ce devis de l\'affaire ?')">
                                                            Désassigner
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Section Technique -->
                <div class="space-y-6">
                    <!-- Matériel -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Matériel Assigné</h3>
                            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'assign-materiel')" class="text-sm text-blue-600 hover:text-blue-500">
                                Assigner un matériel
                            </button>
                        </div>
                        <div class="p-6">
                            @if($affaire->materiels->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Aucun matériel assigné.</p>
                            @else
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($affaire->materiels as $materiel)
                                        <li class="py-3 flex justify-between items-center">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $materiel->designation }}</span>
                                                <p class="text-xs text-gray-500">{{ $materiel->numero_serie }}</p>
                                            </div>
                                            <div class="text-right flex items-center gap-4">
                                                <span class="text-xs text-gray-500">Du {{ $materiel->pivot->date_debut }} au {{ $materiel->pivot->date_fin }}</span>
                                                @if(is_null($materiel->pivot->date_fin) || \Carbon\Carbon::parse($materiel->pivot->date_fin)->isFuture())
                                                    <form method="POST" action="{{ route('affaires.detach_materiel', ['affaire' => $affaire, 'materiel' => $materiel]) }}">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium" onclick="return confirm('Êtes-vous sûr de vouloir désassigner ce matériel ?')">
                                                            Désassigner
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <!-- Personnel Assigné -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Personnel Assigné</h3>
                            @if($affaire->statut !== \App\Models\Affaire::STATUT_TERMINE && $affaire->statut !== \App\Models\Affaire::STATUT_ARCHIVE)
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'assign-personnel')" class="text-sm text-blue-600 hover:text-blue-500">
                                    Assigner du personnel
                                </button>
                            @endif
                        </div>
                        <div class="p-6">
                            @if($affaire->personnels->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Aucun personnel assigné.</p>
                            @else
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($affaire->personnels as $personnel)
                                        <li class="py-3">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <a href="{{ route('personnel.show', $personnel) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                                        {{ $personnel->prenom }} {{ $personnel->nom }}
                                                    </a>
                                                    <p class="text-xs text-gray-500">{{ $personnel->poste }}</p>
                                                    @if($personnel->pivot->role)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mt-1">
                                                            {{ $personnel->pivot->role }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-right flex items-center gap-4 ml-4">
                                                    <div class="text-xs text-gray-500">
                                                        @if($personnel->pivot->date_debut || $personnel->pivot->date_fin)
                                                            <div>
                                                                {{ $personnel->pivot->date_debut ? \Carbon\Carbon::parse($personnel->pivot->date_debut)->format('d/m/Y') : '?' }}
                                                                →
                                                                {{ $personnel->pivot->date_fin ? \Carbon\Carbon::parse($personnel->pivot->date_fin)->format('d/m/Y') : '...' }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <a href="{{ route('affaires.personnel.taches', [$affaire, $personnel]) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                                            Tâches
                                                        </a>
                                                        @if($affaire->statut !== \App\Models\Affaire::STATUT_TERMINE && $affaire->statut !== \App\Models\Affaire::STATUT_ARCHIVE)
                                                            <span class="text-gray-300 dark:text-gray-600">|</span>
                                                            <form method="POST" action="{{ route('affaires.unassign_personnel', [$affaire, $personnel]) }}" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium" onclick="return confirm('Êtes-vous sûr de vouloir désassigner cet employé ?')">
                                                                    Désassigner
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if($personnel->pivot->notes)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 italic">{{ $personnel->pivot->notes }}</p>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <!-- Réparations -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Réparations / SAV</h3>
                        </div>
                        <div class="p-6">
                            @php
                                // Récupérer les IDs des matériels actuellement assignés (date_fin null ou future)
                                $assignedMaterielIds = $affaire->materiels->filter(function ($m) {
                                    return is_null($m->pivot->date_fin) || \Carbon\Carbon::parse($m->pivot->date_fin)->isFuture();
                                })->pluck('id');

                                // Filtrer les réparations pour ne montrer que celles des matériels assignés
                                $visibleReparations = $affaire->reparations->filter(function ($reparation) use ($assignedMaterielIds) {
                                    return $assignedMaterielIds->contains($reparation->materiel_id);
                                });

                                // Filtrer les matériels inactifs pour ne montrer que ceux assignés
                                $inactiveMateriels = $affaire->materiels->filter(function ($m) use ($assignedMaterielIds) {
                                    return $m->status === 'inactif' && $assignedMaterielIds->contains($m->id);
                                });

                                $hasReparations = $visibleReparations->isNotEmpty();
                                $hasInactiveMateriels = $inactiveMateriels->isNotEmpty();
                            @endphp

                            @if(!$hasReparations && !$hasInactiveMateriels)
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune réparation liée.</p>
                            @else
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($visibleReparations as $reparation)
                                        <li class="py-3 flex justify-between items-center">
                                            <div>
                                                <a href="{{ route('reparation.show', $reparation) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">Réparation #{{ $reparation->id }}</a>
                                                <p class="text-xs text-gray-500">{{ $reparation->materiel->designation }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ $reparation->status }}
                                            </span>
                                        </li>
                                    @endforeach

                                    @foreach($inactiveMateriels as $materiel)
                                        @php
                                            $alreadyShown = $visibleReparations->contains('materiel_id', $materiel->id);
                                        @endphp
                                        @if(!$alreadyShown)
                                            <li class="py-3 flex justify-between items-center">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $materiel->designation }} ({{ $materiel->reference }})</span>
                                                    <p class="text-xs text-red-500">Matériel Inactif / En panne</p>
                                                </div>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    Inactif
                                                </span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <x-modal name="assign-materiel" focusable>
        <form method="post" action="{{ route('affaires.assign_materiel', $affaire) }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Assigner un matériel
            </h2>

            <div class="mt-6">
                <x-input-label for="materiel_id" value="Matériel" />
                <select id="materiel_id" name="materiel_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    @foreach($availableMateriels as $materiel)
                        <option value="{{ $materiel->id }}" {{ old('materiel_id') == $materiel->id ? 'selected' : '' }}>{{ $materiel->designation }} ({{ $materiel->reference }})</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('materiel_id')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="date_debut" value="Date de début" />
                <x-text-input id="date_debut" name="date_debut" type="date" class="mt-1 block w-full" value="{{ old('date_debut') }}" required />
                <x-input-error :messages="$errors->get('date_debut')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="date_fin" value="Date de fin (optionnel)" />
                <x-text-input id="date_fin" name="date_fin" type="date" class="mt-1 block w-full" value="{{ old('date_fin') }}" />
                <x-input-error :messages="$errors->get('date_fin')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Annuler
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    Assigner
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="update-status-modal" focusable>
        <form method="post" action="{{ route('affaires.update_status', $affaire) }}" class="p-6" x-data="{ statut: '{{ $affaire->statut }}', currentStatut: '{{ $affaire->statut }}' }" x-on:submit.prevent="if(statut === 'termine') { if(currentStatut === 'en_attente') { alert('Impossible de passer directement de \'En attente\' à \'Terminé\'.'); return; } if(confirm('Attention : Une fois la production terminée, elle ne pourra plus être modifiée et tous les matériels seront désassignés. Voulez-vous continuer ?')) $el.submit(); } else if(statut === 'archive') { if(confirm('Attention : Une fois l\'affaire archivée, elle ne pourra plus être modifiée. Voulez-vous continuer ?')) $el.submit(); } else { $el.submit(); }">
            @csrf
            @method('PATCH')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Modifier le statut de la production
            </h2>

            <div class="mt-6">
                <x-input-label for="statut" value="Statut" />
                <select id="statut" name="statut" x-model="statut" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    @foreach($statuts as $key => $label)
                        <option value="{{ $key }}" {{ $affaire->statut === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Annuler
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    Enregistrer
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Assigner Devis -->
    <x-modal name="assign-devis-modal" :show="false" maxWidth="2xl">
        <form method="POST" action="{{ route('affaires.assign_devis', $affaire) }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Assigner un devis à l'affaire
            </h2>

            @if($devisNonAssignes->isEmpty())
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">
                    Aucun devis disponible pour assignation. Tous les devis sont déjà assignés à une affaire.
                </p>
            @else
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Sélectionnez un ou plusieurs devis à assigner à cette affaire :
                </p>

                <div class="max-h-96 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0">
                            <tr>
                                <th scope="col" class="w-12 px-4 py-3">
                                    <input type="checkbox" id="select-all-devis" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Référence
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Client
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Montant TTC
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($devisNonAssignes as $devis)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3">
                                        <input type="checkbox" name="devis_ids[]" value="{{ $devis->id }}" class="devis-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('devis_tuyauterie.show', $devis) }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                            {{ $devis->reference_projet }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $devis->client_nom }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        @if($devis->date_emission)
                                            {{ \Carbon\Carbon::parse($devis->date_emission)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 text-right">
                                        {{ number_format($devis->total_ttc, 2, ',', ' ') }} €
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-6 flex justify-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Annuler
                </x-secondary-button>

                @if(!$devisNonAssignes->isEmpty())
                    <x-primary-button>
                        Assigner les devis sélectionnés
                    </x-primary-button>
                @endif
            </div>
        </form>
    </x-modal>

    <!-- Modal Assigner Personnel -->
    <x-modal name="assign-personnel" :show="false" maxWidth="2xl">
        <form method="POST" action="{{ route('affaires.assign_personnel', $affaire) }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Assigner du personnel à l'affaire
            </h2>

            @php
                $personnelsDisponibles = \App\Models\Personnel::where('statut', 'actif')
                    ->whereNotIn('id', $affaire->personnels->pluck('id'))
                    ->orderBy('nom')
                    ->get();
            @endphp

            @if($personnelsDisponibles->isEmpty())
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">
                    Aucun personnel disponible. Tous les employés actifs sont déjà assignés à cette affaire.
                </p>
            @else
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Sélectionnez un ou plusieurs employés à assigner à cette affaire :
                </p>

                <div class="max-h-64 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md mb-4">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0">
                            <tr>
                                <th scope="col" class="w-12 px-4 py-3">
                                    <input type="checkbox" id="select-all-personnel" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nom
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Poste
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Département
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($personnelsDisponibles as $personnel)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3">
                                        <input type="checkbox" name="personnel_ids[]" value="{{ $personnel->id }}" class="personnel-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $personnel->prenom }} {{ $personnel->nom }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $personnel->matricule }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $personnel->poste }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $personnel->departement }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rôle dans l'affaire</label>
                        <input type="text" name="role" id="role" value="{{ old('role') }}" placeholder="ex: Chef de projet, Technicien, etc." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de début</label>
                            <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <x-input-error :messages="$errors->get('date_debut')" class="mt-2" />
                        </div>
                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de fin</label>
                            <input type="date" name="date_fin" id="date_fin" value="{{ old('date_fin') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <x-input-error :messages="$errors->get('date_fin')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <textarea name="notes" id="notes" rows="2" placeholder="Notes supplémentaires..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                </div>
            @endif

            <div class="mt-6 flex justify-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Annuler
                </x-secondary-button>

                @if(!$personnelsDisponibles->isEmpty())
                    <x-primary-button>
                        Assigner le personnel sélectionné
                    </x-primary-button>
                @endif
            </div>
        </form>
    </x-modal>

    <script>
        function openAssignDevisModal() {
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'assign-devis-modal' }));
        }

        // Gestion du "tout sélectionner"
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all-devis');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.devis-checkbox');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                });
            }

            const selectAllPersonnel = document.getElementById('select-all-personnel');
            if (selectAllPersonnel) {
                selectAllPersonnel.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.personnel-checkbox');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                });
            }

            // Rouvrir automatiquement le modal si des erreurs de validation existent
            @if($errors->has('personnel_ids') || $errors->has('role') || $errors->has('date_debut') || $errors->has('date_fin') || $errors->has('notes'))
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'assign-personnel' }));
            @endif

            @if($errors->has('materiel_id') && !$errors->has('personnel_ids'))
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'assign-materiel' }));
            @endif
        });
    </script>
</x-app-layout>
