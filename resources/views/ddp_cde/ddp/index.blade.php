<x-app-layout>
    @section('title', 'Demandes de prix')
    <x-slot name="header">
        <div class="flex items-center gap-20 ">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('ddp_cde.index') }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Demandes de prix et commandes</a>
                    >> Demandes de prix
                </h2>
            </div>
            <form method="GET" action="{!! route('ddp.index') !!}"
                class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center">
                <x-select-custom name="statut" id="statut" onchange="this.form.submit()" :selected="request('statut')"
                    class=" mr-2 mb-2 sm:mb-0 ">
                    <x-opt value="">{!! __('Tous les types') !!}</x-opt>
                    @foreach ($ddp_statuts as $ddp_statut)
                        <x-opt value="{{ $ddp_statut->id }}">
                            <div class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                                style="background-color: {{ $ddp_statut->couleur }}; color: {{ $ddp_statut->couleur_texte }}">
                                {{ $ddp_statut->nom }}
                            </div>
                        </x-opt>
                    @endforeach
                </x-select-custom>
                <input type="text" name="search" placeholder="Rechercher..." value="{!! request('search') !!}" oninput="debounceSubmit(this.form)"
                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                <div class="flex items-center ml-4 my-1 ">
                    <label for="nombre" class="mr-2 text-gray-900 dark:text-gray-100">{!! __('Quantité') !!}</label>
                    <input type="number" name="nombre" id="nombre" value="{!! old('nombre', request('nombre', 100)) !!}"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 w-20 mr-2 ">
                </div>
                <button type="submit" class="mr-2 btn w-full sm:w-auto sm:mt-0 md:mt-0 lg:mt-0">
                    {!! __('Rechercher') !!}
                </button>
                <a href="{!! route('ddp.create') !!}"
                    class="btn whitespace-nowrap w-fit-content sm:mt-0 md:mt-0 lg:mt-0">
                    {!! __('Créer une demande de prix') !!}
                </a>
            </form>
        </div>
    </x-slot>
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">

        <div class="bg-white dark:bg-gray-800 flex flex-col p-4 text-gray-800 dark:text-gray-200">

            @if($ddpsGrouped->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr>
                            <x-sortable-header column="code" route="ddp.index">Numéro</x-sortable-header>
                            <x-sortable-header column="created_at" route="ddp.index">Date</x-sortable-header>
                            <x-sortable-header column="nom" route="ddp.index">Nom</x-sortable-header>
                            <x-sortable-header column="user" route="ddp.index">Demandé par</x-sortable-header>
                            <x-sortable-header column="statut" route="ddp.index">Statut</x-sortable-header>
                        </tr>
                    </thead>
                    @include('ddp_cde.ddp.partials.index_lignes', ['isSmall' => false, 'showCreateButton' => false, 'ddpsGrouped' => $ddpsGrouped])
                </table>
            @else
                <!-- Message si aucune demande de prix -->
                <div class="text-center py-8">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Aucune demande de prix trouvée</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Aucune demande de prix ne correspond à vos critères de recherche.</p>
                        <a href="{{ route('ddp.create') }}" class="btn">
                            Créer une nouvelle demande de prix
                        </a>
                    </div>
                </div>
            @endif

            <div class="mt-4 flex justify-center items-center pb-3">
                <div>
                    {{ $ddps->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        let timeout = null;
        function debounceSubmit(form) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                form.submit();
            }, 500);
        }
    </script>
</x-app-layout>
