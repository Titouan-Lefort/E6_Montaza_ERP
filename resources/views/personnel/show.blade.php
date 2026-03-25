<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('administration.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Administration') !!}</a>
                >>
                <a href="{{ route('personnel.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{{ __('Personnel') }}</a>
                >>
            </h2>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $personnel->prenom }} {{ $personnel->nom }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg mb-6">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Informations de l'employé
                        </h3>
                        <div class="flex gap-2">
                            <a href="{{ route('personnel.emploi_du_temps', $personnel) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                📅 Emploi du temps
                            </a>
                            <a href="{{ route('personnel.edit', $personnel->id) }}" class="btn">
                                Modifier
                            </a>
                            <a href="{{ route('personnel.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-hidden focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Retour
                            </a>
                        </div>
                    </div>

                    <!-- Informations de base -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informations de base</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Matricule</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $personnel->matricule }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nom</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $personnel->nom }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Prénom</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $personnel->prenom }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">
                                    <a href="mailto:{{ $personnel->email }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        {{ $personnel->email }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Téléphone</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $personnel->telephone ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Téléphone mobile</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $personnel->telephone_mobile ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut</label>
                                <div class="mt-1 flex items-center gap-3">
                                    @if ($personnel->statut == 'actif')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            Actif
                                        </span>
                                    @elseif ($personnel->statut == 'en_conge')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                            En congé (automatique)
                                        </span>
                                    @elseif ($personnel->statut == 'suspendu')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100">
                                            Suspendu
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                            Parti
                                        </span>
                                    @endif
                                    <button onclick="openStatutModal()" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium">
                                        ✏️ Modifier
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations professionnelles -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informations professionnelles</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Poste</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $personnel->poste ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Département</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $personnel->departement ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Date d'embauche</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">
                                    {{ $personnel->date_embauche ? $personnel->date_embauche->format('d/m/Y') : 'Non renseignée' }}
                                </p>
                            </div>
                            @if ($personnel->date_depart)
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de départ</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">
                                        {{ $personnel->date_depart->format('d/m/Y') }}
                                    </p>
                                </div>
                            @endif
                            @if ($personnel->statut == 'parti' && $personnel->raison_depart)
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Raison du départ</label>
                                    <p class="mt-1">
                                        @switch($personnel->raison_depart)
                                            @case('demission')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                    Démission
                                                </span>
                                                @break
                                            @case('licenciement')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                    Licenciement
                                                </span>
                                                @break
                                            @case('retraite')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                                                    Retraite
                                                </span>
                                                @break
                                            @case('fin_contrat')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                    Fin de contrat
                                                </span>
                                                @break
                                            @case('mutation')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                    Mutation
                                                </span>
                                                @break
                                            @case('autre')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                    Autre
                                                </span>
                                                @break
                                        @endswitch
                                    </p>
                                </div>
                            @endif
                            @if ($personnel->statut == 'parti' && $personnel->motif_depart)
                                <div class="md:col-span-3">
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Motif du départ</label>
                                    <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                        <p class="text-gray-900 dark:text-gray-100">{{ $personnel->motif_depart }}</p>
                                    </div>
                                </div>
                            @endif
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Salaire</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">
                                    {{ $personnel->salaire ? number_format($personnel->salaire, 2, ',', ' ') . ' €' : 'Non renseigné' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Numéro de sécurité sociale</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $personnel->numero_securite_sociale ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Adresse</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Adresse</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $personnel->adresse ?? 'Non renseignée' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Code postal</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $personnel->code_postal ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Ville</label>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $personnel->ville ?? 'Non renseignée' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if ($personnel->notes)
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Notes</h4>
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $personnel->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Congés -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg mb-6">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">Congés</h4>
                        <button onclick="openAddCongeModal()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                            + Ajouter un congé
                        </button>
                    </div>

                    @if($personnel->conges->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Aucun congé enregistré pour le moment.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($personnel->conges as $conge)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                @if($conge->type == 'conge_paye')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Congé payé</span>
                                                @elseif($conge->type == 'conge_maladie')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Congé maladie</span>
                                                @elseif($conge->type == 'conge_sans_solde')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">Sans solde</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Autre</span>
                                                @endif

                                                @if($conge->statut == 'valide')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Validé</span>
                                                @elseif($conge->statut == 'demande')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">En attente</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Refusé</span>
                                                @endif
                                            </div>

                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                📅 Du {{ $conge->date_debut->format('d/m/Y') }} au {{ $conge->date_fin->format('d/m/Y') }}
                                                ({{ $conge->date_debut->diffInDays($conge->date_fin) + 1 }} jour{{ $conge->date_debut->diffInDays($conge->date_fin) > 0 ? 's' : '' }})
                                            </div>

                                            @if($conge->motif)
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $conge->motif }}</p>
                                            @endif
                                        </div>

                                        <div class="flex gap-2 ml-4">
                                            <button onclick="openEditCongeModal({{ $conge->id }}, '{{ $conge->date_debut->format('Y-m-d') }}', '{{ $conge->date_fin->format('Y-m-d') }}', '{{ $conge->type }}', '{{ addslashes($conge->motif ?? '') }}', '{{ $conge->statut }}')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Modifier
                                            </button>
                                            <form method="POST" action="{{ route('personnel.conges.delete', [$personnel, $conge]) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce congé ?')">
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

            <!-- Affaires assignées -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg mb-6">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">Affaires assignées</h4>
                        <a href="{{ route('personnel.emploi_du_temps', $personnel) }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                            Voir l'emploi du temps complet →
                        </a>
                    </div>

                    @if($personnel->affaires->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune affaire assignée pour le moment.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Code</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom de l'affaire</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rôle</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Période</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tâches</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($personnel->affaires as $affaire)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('affaires.show', $affaire) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                    {{ $affaire->code }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $affaire->nom }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                @if($affaire->pivot->role)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        {{ $affaire->pivot->role }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                @if($affaire->pivot->date_debut || $affaire->pivot->date_fin)
                                                    {{ $affaire->pivot->date_debut ? $affaire->pivot->date_debut->format('d/m/Y') : '?' }}
                                                    →
                                                    {{ $affaire->pivot->date_fin ? $affaire->pivot->date_fin->format('d/m/Y') : '...' }}
                                                @else
                                                    <span class="text-gray-400">Non définie</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                @if($affaire->statut == 'en_cours')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                        En cours
                                                    </span>
                                                @elseif($affaire->statut == 'termine')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100">
                                                        Terminé
                                                    </span>
                                                @elseif($affaire->statut == 'en_attente')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                        En attente
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100">
                                                        {{ $affaire->statut_label }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                @php
                                                    $tachesCount = $affaire->pivot->taches ? $affaire->pivot->taches->count() : 0;
                                                @endphp
                                                @if($tachesCount > 0)
                                                    <span class="text-blue-600 dark:text-blue-400 font-medium">{{ $tachesCount }} tâche(s)</span>
                                                @else
                                                    <span class="text-gray-400">Aucune</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('affaires.personnel.taches', [$affaire, $personnel]) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                    Gérer les tâches
                                                </a>
                                            </td>
                                        </tr>
                                        @if($affaire->pivot->notes)
                                            <tr class="bg-gray-50 dark:bg-gray-900">
                                                <td colspan="7" class="px-4 py-2 text-xs text-gray-600 dark:text-gray-400 italic">
                                                    📝 {{ $affaire->pivot->notes }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informations système -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informations système</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Créé le</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $personnel->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Dernière modification</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $personnel->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter un congé -->
    <div id="addCongeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ajouter un congé</h3>
                <button onclick="closeAddCongeModal()" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form method="POST" action="{{ route('personnel.conges.store', $personnel) }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date de début</label>
                        <input type="date" name="date_debut" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date de fin</label>
                        <input type="date" name="date_fin" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type de congé</label>
                        <select name="type" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="conge_paye">Congé payé</option>
                            <option value="conge_maladie">Congé maladie</option>
                            <option value="conge_sans_solde">Congé sans solde</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Statut</label>
                        <select name="statut" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="valide">Validé</option>
                            <option value="demande">En attente</option>
                            <option value="refuse">Refusé</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motif (optionnel)</label>
                        <textarea name="motif" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeAddCongeModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Modifier un congé -->
    <div id="editCongeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Modifier le congé</h3>
                <button onclick="closeEditCongeModal()" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form method="POST" id="editCongeForm">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date de début</label>
                        <input type="date" name="date_debut" id="edit_date_debut" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date de fin</label>
                        <input type="date" name="date_fin" id="edit_date_fin" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type de congé</label>
                        <select name="type" id="edit_type" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="conge_paye">Congé payé</option>
                            <option value="conge_maladie">Congé maladie</option>
                            <option value="conge_sans_solde">Congé sans solde</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Statut</label>
                        <select name="statut" id="edit_statut" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="valide">Validé</option>
                            <option value="demande">En attente</option>
                            <option value="refuse">Refusé</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motif (optionnel)</label>
                        <textarea name="motif" id="edit_motif" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeEditCongeModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Modifier
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Modifier le statut -->
    <div id="statutModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Modifier le statut</h3>
                <button onclick="closeStatutModal()" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form method="POST" action="{{ route('personnel.updateStatut', $personnel) }}">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md p-3">
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                            ℹ️ Le statut "En congé" est géré automatiquement selon les congés validés.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nouveau statut</label>
                        <select name="statut" id="statut_select" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="actif" {{ $personnel->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="suspendu" {{ $personnel->statut == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="parti" {{ $personnel->statut == 'parti' ? 'selected' : '' }}>Parti</option>
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Le statut "En congé" n'est pas sélectionnable manuellement</p>
                    </div>

                    <!-- Date de départ (affiché si statut = parti) -->
                    <div id="statut_date_depart_container" style="display: {{ $personnel->statut == 'parti' ? 'block' : 'none' }};">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date de départ</label>
                        <input type="date" name="date_depart" value="{{ $personnel->date_depart ? $personnel->date_depart->format('Y-m-d') : '' }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                    </div>

                    <!-- Raison du départ (affiché si statut = parti) -->
                    <div id="statut_raison_depart_container" style="display: {{ $personnel->statut == 'parti' ? 'block' : 'none' }};">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Raison du départ</label>
                        <select name="raison_depart" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="">Sélectionner...</option>
                            <option value="demission" {{ $personnel->raison_depart == 'demission' ? 'selected' : '' }}>Démission</option>
                            <option value="licenciement" {{ $personnel->raison_depart == 'licenciement' ? 'selected' : '' }}>Licenciement</option>
                            <option value="retraite" {{ $personnel->raison_depart == 'retraite' ? 'selected' : '' }}>Retraite</option>
                            <option value="fin_contrat" {{ $personnel->raison_depart == 'fin_contrat' ? 'selected' : '' }}>Fin de contrat</option>
                            <option value="mutation" {{ $personnel->raison_depart == 'mutation' ? 'selected' : '' }}>Mutation</option>
                            <option value="autre" {{ $personnel->raison_depart == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <!-- Motif du départ (affiché si statut = parti) -->
                    <div id="statut_motif_depart_container" style="display: {{ $personnel->statut == 'parti' ? 'block' : 'none' }};">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motif du départ (détails)</label>
                        <textarea name="motif_depart" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" placeholder="Détails sur le départ (obligatoire en cas de licenciement)">{{ $personnel->motif_depart }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeStatutModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Mettre à jour le statut
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddCongeModal() {
            document.getElementById('addCongeModal').classList.remove('hidden');
        }

        function closeAddCongeModal() {
            document.getElementById('addCongeModal').classList.add('hidden');
        }

        function openEditCongeModal(id, dateDebut, dateFin, type, motif, statut) {
            const modal = document.getElementById('editCongeModal');
            const form = document.getElementById('editCongeForm');

            form.action = '{{ route("personnel.conges.update", [$personnel, ":id"]) }}'.replace(':id', id);

            document.getElementById('edit_date_debut').value = dateDebut;
            document.getElementById('edit_date_fin').value = dateFin;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_statut').value = statut;
            document.getElementById('edit_motif').value = motif;

            modal.classList.remove('hidden');
        }

        function closeEditCongeModal() {
            document.getElementById('editCongeModal').classList.add('hidden');
        }

        function openStatutModal() {
            document.getElementById('statutModal').classList.remove('hidden');
        }

        function closeStatutModal() {
            document.getElementById('statutModal').classList.add('hidden');
        }

        // Afficher/masquer les champs de départ selon le statut
        document.getElementById('statut_select')?.addEventListener('change', function() {
            const statutValue = this.value;
            const raisonContainer = document.getElementById('statut_raison_depart_container');
            const motifContainer = document.getElementById('statut_motif_depart_container');
            const dateContainer = document.getElementById('statut_date_depart_container');

            if (statutValue === 'parti') {
                raisonContainer.style.display = 'block';
                motifContainer.style.display = 'block';
                dateContainer.style.display = 'block';
            } else {
                raisonContainer.style.display = 'none';
                motifContainer.style.display = 'none';
                dateContainer.style.display = 'none';
            }
        });
    </script>
</x-app-layout>
