<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div class="flex items-center space-x-2">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('administration.index') }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Administration') !!}</a>
                    >>
                </h2>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Personnel') }}
                    </h2>
                </div>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center mx-auto">
                <a href="{{ route('personnel.anciens-employes') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md text-sm mr-4 mb-2 sm:mb-0 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Anciens employés
                </a>

                <label for="Toggle1"
                    class="inline-flex items-center space-x-4 cursor-pointer dark:text-gray-100 text-gray-800 mr-4 mb-1">
                    <span class="whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">Afficher les employés supprimés</span>
                    <span class="relative">
                        <input id="Toggle1" type="checkbox" class="hidden peer" onchange="toggleDeletedPersonnel(this)"
                            {{ request()->get('show_deleted') ? 'checked' : '' }} />
                        <div
                            class="w-10 h-6 rounded-full shadow-inner bg-gray-400 dark:bg-gray-600 peer-checked:bg-violet-400 dark:peer-checked:bg-violet-600">
                        </div>
                        <div
                            class="absolute inset-y-0 left-0 w-4 h-4 m-1 rounded-full shadow-sm peer-checked:right-0 peer-checked:left-auto bg-gray-800 dark:bg-gray-100">
                        </div>
                    </span>
                </label>

                <form method="GET" action="{{ route('personnel.index') }}" class="mr-4 mb-1 sm:mr-0 sm:grow">
                    <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}"
                        oninput="debounceSubmit(this.form)"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="ml-2 btn">
                        {{ __('Rechercher') }}
                    </button>
                </form>
                <a href="{{ route('personnel.create') }}" class="btn sm:ml-4">
                    {{ __('Ajouter un employé') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" id="container">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-800 dark:border-green-600 dark:text-green-200" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-linear-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Matricule
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nom
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Téléphone
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Poste
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Département
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date d'embauche
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Ville
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="{{ request()->get('show_deleted') ? 'bg-gray-100 dark:bg-gray-900' : 'bg-white dark:bg-gray-800' }} divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($personnels as $personnel)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-150"
                                        onclick="window.location='{{ route('personnel.show', $personnel->id) }}'">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                            {{ $personnel->matricule }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $personnel->prenom }} {{ $personnel->nom }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $personnel->email }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $personnel->telephone_mobile ?? $personnel->telephone }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $personnel->poste }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $personnel->departement }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $personnel->date_embauche ? $personnel->date_embauche->format('d/m/Y') : '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $personnel->ville }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($personnel->statut == 'actif')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                    Actif
                                                </span>
                                            @elseif ($personnel->statut == 'en_conge')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                    En congé
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
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if (request()->get('show_deleted'))
                                                <a href="{{ route('personnel.restore', $personnel->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3"
                                                    onclick="event.stopPropagation(); event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir restaurer cet employé ?')) { document.getElementById('restore-form-{{ $personnel->id }}').submit(); }">
                                                    Restaurer
                                                </a>
                                                <form id="restore-form-{{ $personnel->id }}"
                                                    action="{{ route('personnel.restore', $personnel->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            @else
                                                <a href="{{ route('personnel.edit', $personnel->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3"
                                                    onclick="event.stopPropagation();">
                                                    Modifier
                                                </a>
                                                <form action="{{ route('personnel.destroy', $personnel->id) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="event.stopPropagation(); return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10"
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">
                                            Aucun employé trouvé.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function debounceSubmit(form) {
            clearTimeout(form.debounceTimer);
            form.debounceTimer = setTimeout(() => {
                form.submit();
            }, 500);
        }

        function toggleDeletedPersonnel(checkbox) {
            const url = new URL(window.location.href);
            if (checkbox.checked) {
                url.searchParams.set('show_deleted', '1');
            } else {
                url.searchParams.delete('show_deleted');
            }
            window.location.href = url.toString();
        }
    </script>
</x-app-layout>
