<x-app-layout>
    @section('title', $matiere->designation)

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('matieres.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Matières</a>
                >> {{ $matiere->designation }}
            </h2>
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('matieres.edit', $matiere->id) }}" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>

                @if (!$matiere->isLocked())
                    <x-boutons.supprimer
                        customButton="<button class='btn'>
                        <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5 mr-1' fill='none' viewBox='0 0 24
                            24' stroke='currentColor'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867
                                12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                                00-1-1h-4a1 1 0 00-1 1v3M4 7h16' />
                        </svg>
                        Supprimer
                        </button>"
                        modalTitle="Supprimer la matière"
                        userInfo="Êtes-vous sûr de vouloir supprimer la matière {{ $matiere->designation }} ? Cette action est irréversible."
                        formAction="{{ route('matieres.destroy', $matiere->id) }}"
                        confirmButtonText="Supprimer définitivement" cancelButtonText="Annuler" />
                @else
                    <x-tooltip position="left" class="">
                        <x-slot name="slot_item">
                            <button class="btn-secondary opacity-50 cursor-not-allowed" disabled
                                title="Cette matière ne peut pas être supprimée car elle est utilisée">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Supprimer
                            </button>
                        </x-slot>
                        <x-slot name="slot_tooltip">
                            <div class="flex items-center gap-2 text-yellow-700 dark:text-yellow-300 ">
                                <x-icons.lock class="w-5 h-5 fill-yellow-700 dark:fill-yellow-300" />
                                <span class="font-bold">Matière verrouillée</span>
                            </div>
                            <p>Cette matière a déjà été utilisée dans un ou plusieurs mouvements de stock ou est
                                associée à des fournisseurs. <br /> Pour préserver l'intégrité des données, vous ne
                                pouvez pas la supprimer.</p>

                        </x-slot>
                    </x-tooltip>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6 space-y-6">
        <!-- Carte d'information principale -->
        <div
            class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-3 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-gray-100 dark:to-gray-300 bg-clip-text text-transparent">
                        {{ $matiere->designation }}</h1>
                </div>
                <div
                    class="bg-gray-100 dark:bg-gray-700 rounded-full px-5 py-2 flex items-center gap-2 shadow-inner text-sm font-medium">
                    <span class="text-gray-500 dark:text-gray-400">Référence:</span>
                    <span class="font-bold text-gray-900 dark:text-gray-100"> <x-copiable_text
                            text="{{ $matiere->ref_interne }}" /></span>
                </div>
            </div>

            <!-- Infos principales en grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md flex">
                    <div class="border-r-2 pr-4 border-gray-100 dark:border-gray-700">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Famille</p>
                        <p class="font-semibold text-lg">{{ $matiere->famille->nom }}</p>
                    </div>
                    <div class="pl-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Sous Famille</p>
                        <p class="font-semibold text-lg">{{ $matiere->sousFamille->nom }}</p>
                    </div>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Matière</p>
                    <p class="font-semibold text-lg">{{ $matiere->material->nom ?? '-' }}</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Stock actuel</p>

                    <div class=" flex items-end">
                        <div class="flex items-center flex-col w-fit">
                            <div class="border-b-2 border-gray-500 dark:border-gray-400">
                                <p class="font-semibold text-lg">
                                    <x-stock-tooltip matiereId="{{ $matiere->id }}" no_underline />
                                </p>
                            </div>

                            <x-tooltip position="bottom" class="">
                                <x-slot name="slot_item">
                                    <p class="text-gray-500 dark:text-gray-400 ">
                                        {{ $matiere->stock_min }}
                                    </p>
                                </x-slot>
                                <x-slot name="slot_tooltip">
                                    @if ($matiere->quantite() < $matiere->stock_min)
                                        <span class="text-red-500 dark:text-red-400 font-semibold">Stock
                                            insuffisant</span>
                                        <p class="text-sm">Le stock de cette matière est inférieur au seuil minimum
                                            défini.</p>
                                    @else
                                        Si le stock passe en dessous de ce seuil, vous serez notifié automatiquement.
                                    @endif
                                    <div class="flex justify-end mt-2">
                                        <a href="{{ route('matieres.edit', $matiere->id) }}" class="btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Modifier le stock minimum
                                        </a>
                                    </div>
                                </x-slot>
                            </x-tooltip>
                        </div>
                        @if ($matiere->quantite() < $matiere->stock_min)
                            <div class="ml-2">
                            </div>
                        @endif
                    </div>

                </div>
                @if ($matiere->typeAffichageStock() == 2)
                    <div
                        class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Valeur de référence unitaire</p>
                        <p class="font-semibold text-lg">{{ $matiere->ref_valeur_unitaire }}</p>
                    </div>
                @else
                    <div
                        class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 brightness-110 dark:brightness-90">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Valeur de référence unitaire</p>
                        <p class="font-semibold text-lg text-gray-500 dark:text-gray-400">Aucune</p>
                    </div>
                @endif
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">DN</p>
                    <p class="font-semibold text-lg">{{ $matiere->dn ?? '-' }}</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Épaisseur</p>
                    <p class="font-semibold text-lg">{{ $matiere->epaisseur ?? '-' }}</p>
                </div>


                @if ($matiere->standardVersion != null)
                    <div
                        class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg mb-6 flex items-center gap-3 border border-blue-100 dark:border-blue-800 transition-all duration-300 hover:shadow-md">
                        <x-icons.pdf class="w-8 h-8 text-blue-600 dark:text-blue-400" />
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Standard</p>
                            <a href="{{ $matiere->standardVersion->chemin_pdf ?? '-' }}"
                                class="font-semibold text-blue-600 dark:text-blue-400 hover:underline hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200"
                                target="_blank">
                                {{ $matiere->standardVersion->standard->nom ?? '-' }} -
                                {{ $matiere->standardVersion->version ?? '-' }}
                            </a>
                        </div>
                    </div>
                @else
                    <div
                        class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg mb-6 border border-gray-100 dark:border-gray-700 flex items-center justify-between ">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Standard</p>
                            <p class="font-semibold">Aucun standard</p>
                        </div>
                        <a href="{{ route('matieres.edit', $matiere->id) }}" class="btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Ajouter un standard
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Grille avec 2 colonnes pour fournisseurs et mouvements -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{--
########  #######  ##     ## ########  ##    ## ####  ######   ######  ######## ##     ## ########   ######
##       ##     ## ##     ## ##     ## ###   ##  ##  ##    ## ##    ## ##       ##     ## ##     ## ##    ##
##       ##     ## ##     ## ##     ## ####  ##  ##  ##       ##       ##       ##     ## ##     ## ##
######   ##     ## ##     ## ########  ## ## ##  ##   ######   ######  ######   ##     ## ########   ######
##       ##     ## ##     ## ##   ##   ##  ####  ##        ##       ## ##       ##     ## ##   ##         ##
##       ##     ## ##     ## ##    ##  ##   ###  ##  ##    ## ##    ## ##       ##     ## ##    ##  ##    ##
##        #######   #######  ##     ## ##    ## ####  ######   ######  ########  #######  ##     ##  ######
--}}
            <div
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-indigo-100 dark:bg-indigo-900 rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold">Fournisseurs</h2>
                    </div>
                    <div>
                        <button x-data x-on:click.prevent="$dispatch('open-modal','create-fournisseur')"
                            class="btn">
                            Ajouter un fournisseur
                        </button>
                        <x-modal name="create-fournisseur">
                            <div class="p-6 bg-white dark:bg-gray-800">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Ajouter un
                                        fournisseur</h2>
                                    <button x-on:click="$dispatch('close')"
                                        class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <form action="{{ route('matieres.fournisseurs.store', $matiere->id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="mb-4 ml-2 flex-grow">
                                        <x-input-label for="societe_id" :value="__('référence externe')" />
                                        <div class="flex w-full">
                                            <select name="societe_id" id="societe_id"
                                                class="mt-1 py-3 select-left rounded-r-none w-fit">
                                                <option value="" disabled selected>Sélectionner un fournisseur
                                                    &nbsp;
                                                </option>
                                                @foreach ($societes as $societe)
                                                    <option value="{{ $societe->id }}">
                                                        {{ $societe->raison_sociale }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-text-input type="text" name="ref_externe" id="ref_externe"
                                                class="mt-1 block w-full rounded-l-none" placeholder="Référence" />
                                        </div>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <button type="button" x-on:click="$dispatch('close')"
                                            class="btn-secondary">Annuler</button>
                                        <button type="submit" class="btn">Ajouter</button>
                                    </div>
                                </form>
                            </div>
                        </x-modal>
                    </div>

                </div>
                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-750">
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Référence</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Fournisseur</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Dernier prix</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($fournisseurs as $fournisseur)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-4 py-3 whitespace-nowrap cursor-pointer"
                                        onclick="window.location.href = '{{ route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id]) }}';">
                                        @if ($fournisseur->ref_externe && $fournisseur->ref_externe != '')
                                            {{ $fournisseur->ref_externe }}
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">Aucune référence</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap font-medium cursor-pointer"
                                        onclick="window.location.href = '{{ route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id]) }}';">
                                        {{ $fournisseur->raison_sociale }}</td>
                                    @if ($fournisseur->prix != null && $fournisseur->prix->prix_unitaire != null)
                                        <td
                                            class="px-4 py-3 whitespace-nowrap font-semibold text-green-600 dark:text-green-400 cursor-pointer"
                                            onclick="window.location.href = '{{ route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id]) }}';">
                                            {{ formatNumberArgent($fournisseur->prix->prix_unitaire) . '/' . $matiere->unite->short }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400 cursor-pointer"
                                            onclick="window.location.href = '{{ route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id]) }}';">
                                            {{ formatDate(date_string: $fournisseur->prix->date) }}
                                        </td>
                                    @else
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400 cursor-pointer"
                                            colspan="2"
                                            onclick="window.location.href = '{{ route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id]) }}';">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                Aucun prix
                                            </span>
                                        </td>
                                    @endif
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <x-boutons.supprimer
                                            modalTitle="Détacher le fournisseur"
                                            userInfo="Êtes-vous sûr de vouloir détacher le fournisseur {{ $fournisseur->raison_sociale }} de cette matière ? <br/> Tous les prix associés seront également supprimés. Cette action est irréversible."
                                            formAction="{{ route('matieres.fournisseurs.detacher', ['matiere' => $matiere->id, 'fournisseur' => $fournisseur->id]) }}"
                                            confirmButtonText="Détacher définitivement"
                                            cancelButtonText="Annuler" >
                                            <x-slot:customButton>
                                                <button class=' btn' title="Détacher le fournisseur">
                                                <x-icons.unlink  />
                                            </button>
                                            </x-slot:customButton>
                                        </x-boutons.supprimer>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($fournisseurs->count() == 0)
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400"
                                        colspan="4">Aucun fournisseur pour le moment</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            {{--
   ###          ##  #######  ##     ## ######## ######## ########
  ## ##         ## ##     ## ##     ##    ##    ##       ##     ##
 ##   ##        ## ##     ## ##     ##    ##    ##       ##     ##
##     ##       ## ##     ## ##     ##    ##    ######   ########
######### ##    ## ##     ## ##     ##    ##    ##       ##   ##
##     ## ##    ## ##     ## ##     ##    ##    ##       ##    ##
##     ##  ######   #######   #######     ##    ######## ##     ## --}}

            @include('matieres.partials.ajouter_matiere', ['matiere' => $matiere])

        </div>

        {{--
##     ##  #######  ##     ## ##     ## ######## ##     ## ######## ##    ## ########  ######
###   ### ##     ## ##     ## ##     ## ##       ###   ### ##       ###   ##    ##    ##    ##
#### #### ##     ## ##     ## ##     ## ##       #### #### ##       ####  ##    ##    ##
## ### ## ##     ## ##     ## ##     ## ######   ## ### ## ######   ## ## ##    ##     ######
##     ## ##     ## ##     ##  ##   ##  ##       ##     ## ##       ##  ####    ##          ##
##     ## ##     ## ##     ##   ## ##   ##       ##     ## ##       ##   ###    ##    ##    ##
##     ##  #######   #######     ###    ######## ##     ## ######## ##    ##    ##     ######

 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-emerald-100 dark:bg-emerald-900 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 dark:text-emerald-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold">Mouvements de stock ({{ $matiere->mouvementStocks->count() }})</h2>
                </div>
                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-750">
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Mouvement</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Utilisateur</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Raison</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($matiere->mouvementStocks && $matiere->mouvementStocks->count() > 0)
                                @foreach ($mouvements->take(5) as $mouvement)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 @if ($mouvement->cde_ligne_id != null) cursor-pointer @endif"
                                        @if ($mouvement->cde_ligne_id != null) onclick="window.open('{{ route('cde.show', $mouvement->cdeLigne->cde->id) }}', '_blank');"
                                            title="Voir la commande {{ $mouvement->cdeLigne->cde->code }}" @endif>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if ($mouvement->type == 'sortie')
                                                <div class="flex items-center">
                                                    <span
                                                        class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-red-100 text-red-500 dark:bg-red-900 dark:text-red-300 mr-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </span>
                                                    <span class="text-red-500 dark:text-red-400 font-medium">-
                                                        {{ $mouvement->valeur_unitaire ? formatNumber($mouvement->quantite * $mouvement->valeur_unitaire) : formatNumber($mouvement->quantite) }}
                                                        {{ $matiere->unite->short }}</span>
                                                    @if ($mouvement->valeur_unitaire != null)
                                                        <span class="text-gray-500 dark:text-gray-400 ml-1 text-xs">
                                                            ({{ formatNumber($mouvement->quantite) }} ×
                                                            {{ formatNumber($mouvement->valeur_unitaire) }}
                                                            {{ $matiere->unite->short }})
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="flex items-center">
                                                    <span
                                                        class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-100 text-green-500 dark:bg-green-900 dark:text-green-300 mr-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 15l7-7 7 7" />
                                                        </svg>
                                                    </span>
                                                    <span class="text-green-500 dark:text-green-400 font-medium">+
                                                        {{ $mouvement->valeur_unitaire ? formatNumber($mouvement->quantite * $mouvement->valeur_unitaire) : formatNumber($mouvement->quantite) }}
                                                        {{ $matiere->unite->short }}</span>
                                                    @if ($mouvement->valeur_unitaire != null)
                                                        <span class="text-gray-500 dark:text-gray-400 ml-1 text-xs">
                                                            ({{ formatNumber($mouvement->quantite) }} ×
                                                            {{ formatNumber($mouvement->valeur_unitaire) }}
                                                            {{ $matiere->unite->short }})
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $mouvement->user->first_name . ' ' . $mouvement->user->last_name }}
                                        </td>
                                        <td>
                                            <div class="relative">
                                                <x-tooltip position="right" class="">
                                                    <x-slot name="slot_item">
                                                        @if ($mouvement->cde_ligne_id != null)
                                                            <button
                                                                onclick="event.stopPropagation(); window.open('{{ route('cde.show', $mouvement->cdeLigne->cde->id) }}', '_blank');"
                                                                class="inline-flex items-center px-3 py-2 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors duration-200 cursor-pointer border border-blue-200 dark:border-blue-700 max-w-full"
                                                                title="Voir la commande liée"
                                                                style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-4 w-4 mr-1" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2" />
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M14 4h6m0 0v6m0-6L10 14" />
                                                                </svg>
                                                                <span class="block truncate sm:truncate">
                                                                    {{ Str::limit($mouvement->raison, 20, '...') }}
                                                                </span>
                                                            </button>
                                                        @else
                                                            <span
                                                                class="inline-block px-2 py-1 rounded text-sm max-w-full truncate">
                                                                {{ Str::limit($mouvement->raison, 15, '...') }}
                                                            </span>
                                                        @endif
                                                    </x-slot>
                                                    <x-slot name="slot_tooltip">
                                                        <div class="max-w-xs break-words">
                                                            {{ $mouvement->raison }}
                                                        </div>
                                                    </x-slot>
                                                </x-tooltip>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            {{ $mouvement->created_at->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400"
                                        colspan="1000">Aucun mouvement</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('matieres.mouvements', $matiere->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-700 dark:hover:bg-emerald-600 text-white rounded-md transition-colors duration-200 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        Voir tous les mouvements
                    </a>
                </div>
            </div>

            {{--
########  ######## ######## #### ########  ######## ########
##     ## ##          ##     ##  ##     ## ##       ##     ##
##     ## ##          ##     ##  ##     ## ##       ##     ##
########  ######      ##     ##  ########  ######   ########
##   ##   ##          ##     ##  ##   ##   ##       ##   ##
##    ##  ##          ##     ##  ##    ##  ##       ##    ##
##     ## ########    ##    #### ##     ## ######## ##     ##  --}}

            @include('matieres.partials.retirer_matiere', ['matiere' => $matiere])

        </div>
    </div>
    <!-- Graphique d'évolution du stock -->
    @if ($dates == null)
        <div
            class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-yellow-100 dark:bg-yellow-900 rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 dark:text-yellow-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-yellow-600 dark:text-yellow-400 font-medium">Aucun mouvement pour cette matière</p>
            </div>
        </div>
    @else
        <div
            class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold">Évolution du stock</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                    <x-input-label for="startDate" class="block mb-2">Date de début :</x-input-label>
                    <select id="startDate" class="select w-full focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($dates as $date)
                            <option value="{{ $date }}">{{ $date }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                    <x-input-label for="endDate" class="block mb-2">Date de fin :</x-input-label>
                    <select id="endDate" class="select w-full focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($dates->reverse() as $date)
                            <option value="{{ $date }}">{{ $date }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="mb-6 chart-container" style="position: relative; height:300px;">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
        </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const ctx = document.getElementById('myChart').getContext('2d');

                const myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($dates),
                        datasets: [{
                            label: 'Quantité sur le temps',
                            data: @json($quantites),
                            borderColor: '#4F46E5', // Indigo-600
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 2,
                            tension: 0.1,
                            fill: true,
                            pointBackgroundColor: '#4F46E5',
                            pointRadius: 3,
                            pointHoverRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    font: {
                                        size: 14
                                    }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'hour',
                                    displayFormats: {
                                        hour: 'yyyy-MM-dd HH:mm',
                                    },
                                },
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                type: 'linear',
                                grid: {
                                    borderDash: [2]
                                }
                            }
                        }
                    }
                });

                // Gestionnaires des menus déroulants
                const startDateSelect = document.getElementById('startDate');
                const endDateSelect = document.getElementById('endDate');

                // Fonction pour mettre à jour les limites de l'axe X
                const updateChartLimits = () => {
                    const startDate = startDateSelect.value;
                    const endDate = endDateSelect.value;

                    if (new Date(startDate) <= new Date(endDate)) {
                        myChart.options.scales.x.min = startDate;
                        myChart.options.scales.x.max = endDate;
                        myChart.update();
                    } else {
                        alert("La date de début doit être inférieure ou égale à la date de fin.");
                    }
                };

                // Ajoute des événements de changement aux sélecteurs
                startDateSelect.addEventListener('change', updateChartLimits);
                endDateSelect.addEventListener('change', updateChartLimits);
            });
        </script>
    @endif
{{--
 ######   #######  ##     ## ########     ###    ########     ###    ####  ######   #######  ##    ##
##    ## ##     ## ###   ### ##     ##   ## ##   ##     ##   ## ##    ##  ##    ## ##     ## ###   ##
##       ##     ## #### #### ##     ##  ##   ##  ##     ##  ##   ##   ##  ##       ##     ## ####  ##
##       ##     ## ## ### ## ########  ##     ## ########  ##     ##  ##   ######  ##     ## ## ## ##
##       ##     ## ##     ## ##        ######### ##   ##   #########  ##        ## ##     ## ##  ####
##    ## ##     ## ##     ## ##        ##     ## ##    ##  ##     ##  ##  ##    ## ##     ## ##   ###
 ######   #######  ##     ## ##        ##     ## ##     ## ##     ## ####  ######   #######  ##    ##

########  ########  #### ##      ##
##     ## ##     ##  ##   ##    ##
##     ## ##     ##  ##    ##  ##
########  ########   ##     ####
##        ##   ##    ##    ##  ##
##        ##    ##   ##   ##    ##
##        ##     ## #### ##      ##

 --}}

    @if($fournisseurs->count() > 0)
        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-purple-100 dark:bg-purple-900 rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold">Comparaison des prix par fournisseur</h2>
            </div>

            <!-- Filtres pour les prix -->
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-750 rounded-lg border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Filtres des prix</h3>
                <form id="prix-filters-form" method="GET" action="{{ route('matieres.show', $matiere->id) }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Préserver les autres paramètres de requête -->
                    @foreach(request()->except(['periode_prix', 'date_debut_prix', 'date_fin_prix']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <!-- Filtre par période -->
                    <div>
                        <label for="periode_prix" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Période</label>
                        <select id="periode_prix" name="periode_prix" class="select w-full focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Toutes les périodes</option>
                            <option value="today" {{ request('periode_prix') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                            <option value="week" {{ request('periode_prix') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                            <option value="month" {{ request('periode_prix') == 'month' ? 'selected' : '' }}>Ce mois</option>
                            <option value="3months" {{ request('periode_prix') == '3months' ? 'selected' : '' }}>3 derniers mois</option>
                            <option value="6months" {{ request('periode_prix') == '6months' ? 'selected' : '' }}>6 derniers mois</option>
                            <option value="year" {{ request('periode_prix') == 'year' ? 'selected' : '' }}>Cette année</option>
                            <option value="custom" {{ request('periode_prix') == 'custom' ? 'selected' : '' }}>Période personnalisée</option>
                        </select>
                    </div>

                    <!-- Bouton reset -->
                    <div class="flex items-end">
                        <a href="{{ route('matieres.show', $matiere->id) }}" class="btn">
                            Réinitialiser
                        </a>
                    </div>

                    <!-- Filtres de période personnalisée (cachés par défaut) -->
                    <div id="custom-period-prix" class="col-span-full mt-4 flex gap-4"
                        style="display: {{ request('periode_prix') == 'custom' ? 'flex' : 'none' }}">
                        <div>
                            <label for="date_debut_prix" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date de début</label>
                            <input type="date" id="date_debut_prix" name="date_debut_prix" value="{{ request('date_debut_prix') }}" class="mt-1 block px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500" />
                        </div>
                        <div>
                            <label for="date_fin_prix" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date de fin</label>
                            <input type="date" id="date_fin_prix" name="date_fin_prix" value="{{ request('date_fin_prix') }}" class="mt-1 block px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500" />
                        </div>
                    </div>
                </form>
            </div>

            @if(!$hasPriceData)
                <div class="bg-yellow-50 dark:bg-yellow-900/30 p-4 rounded-lg mb-6 flex items-center gap-3 border border-yellow-100 dark:border-yellow-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        @if(request('periode_prix'))
                            <p class="text-yellow-600 dark:text-yellow-400 font-medium">Aucun prix trouvé pour la période sélectionnée.</p>
                            <p class="text-yellow-600 dark:text-yellow-400 text-sm">Essayez de modifier les filtres ou de sélectionner une période plus large.</p>
                        @else
                            <p class="text-yellow-600 dark:text-yellow-400 font-medium">Aucun prix enregistré pour cette matière.</p>
                            <p class="text-yellow-600 dark:text-yellow-400 text-sm">Ajoutez des prix aux fournisseurs pour voir le graphique de comparaison.</p>
                        @endif
                    </div>
                </div>
            @elseif(count($prixParFournisseur) < 2)
                <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg mb-6 flex items-center gap-3 border border-blue-100 dark:border-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-blue-600 dark:text-blue-400 font-medium">Il faut au moins deux fournisseurs avec des prix pour afficher le graphique de comparaison.</p>
                </div>
            @else
                <div class="bg-gray-50 dark:bg-gray-750 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="mb-6 chart-container" style="position: relative; height:400px;">
                        <canvas id="prixChart"></canvas>
                    </div>
                </div>
            @endif

            <!-- Légende des fournisseurs -->
            @if($hasPriceData)
                <div class="mt-4 flex flex-wrap gap-4">
                    @foreach($prixParFournisseur as $fournisseurId => $data)
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 rounded-full" style="background-color: {{ $data['couleur'] }}"></div>
                            <span class="text-sm font-medium">{{ $data['nom'] }}</span>
                            <a href="{{ route('matieres.show_prix', ['matiere' => $matiere->id, 'fournisseur' => $fournisseurId]) }}"
                               class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                Voir détails
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Gestion des filtres de prix
                const formPrix = document.getElementById('prix-filters-form');
                const periodePrixSelect = document.getElementById('periode_prix');
                const customPeriodPrix = document.getElementById('custom-period-prix');
                const dateDebutPrix = document.getElementById('date_debut_prix');
                const dateFinPrix = document.getElementById('date_fin_prix');

                // Fonction pour soumettre le formulaire des prix automatiquement
                function submitPrixForm() {
                    formPrix.submit();
                }

                // Gérer le changement de période pour les prix
                if (periodePrixSelect) {
                    periodePrixSelect.addEventListener('change', function() {
                        if (this.value === 'custom') {
                            customPeriodPrix.style.display = 'flex';
                        } else {
                            customPeriodPrix.style.display = 'none';
                            submitPrixForm();
                        }
                    });
                }

                // Soumettre lors du changement des dates personnalisées pour les prix
                if (dateDebutPrix) {
                    dateDebutPrix.addEventListener('change', function() {
                        if (periodePrixSelect.value === 'custom') {
                            submitPrixForm();
                        }
                    });
                }

                if (dateFinPrix) {
                    dateFinPrix.addEventListener('change', function() {
                        if (periodePrixSelect.value === 'custom') {
                            submitPrixForm();
                        }
                    });
                }

                // Graphique de comparaison des prix
                @if($hasPriceData && count($prixParFournisseur) >= 2)
                const ctxPrix = document.getElementById('prixChart').getContext('2d');

                const datasets = [];
                @foreach($prixParFournisseur as $fournisseurId => $data)
                    datasets.push({
                        label: '{{ $data["nom"] }}',
                        data: @json($data['prix']),
                        borderColor: '{{ $data["couleur"] }}',
                        backgroundColor: '{{ $data["couleur"] }}20',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: false,
                        pointBackgroundColor: '{{ $data["couleur"] }}',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    });
                @endforeach

                const prixChart = new Chart(ctxPrix, {
                    type: 'line',
                    data: {
                        labels: @json($datesPrix),
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    font: {
                                        size: 14
                                    }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + new Intl.NumberFormat('fr-FR', {
                                            style: 'currency',
                                            currency: 'EUR'
                                        }).format(context.parsed.y);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'day',
                                    displayFormats: {
                                        day: 'dd/MM/yyyy',
                                    },
                                },
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: false,
                                type: 'linear',
                                grid: {
                                    borderDash: [2]
                                },
                                ticks: {
                                    callback: function(value) {
                                        return new Intl.NumberFormat('fr-FR', {
                                            style: 'currency',
                                            currency: 'EUR'
                                        }).format(value);
                                    }
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });
                @endif
            });
        </script>
    @endif
</x-app-layout>
