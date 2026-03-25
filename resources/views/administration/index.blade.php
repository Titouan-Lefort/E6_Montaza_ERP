<x-app-layout>
    @section('title', 'Administration')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg p-4 flex flex-wrap gap-4">
                @can('gerer_les_utilisateurs')
                    <a href="{{ route('profile.index') }}" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <x-icons.group class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Utilisateurs') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Gérer les utilisateurs') }}</p>
                        </div>
                    </a>
                @endcan
                @can('gerer_les_utilisateurs')
                    <a href="{{ route('personnel.index') }}" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <x-icons.group class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Personnel') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Gérer le personnel de l\'entreprise') }}</p>
                        </div>
                    </a>
                @endcan
                @can('gerer_les_permissions')
                    <a href="{{ route('permissions') }}" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <x-icons.key class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Permissions et Postes') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Gérer les permissions et les postes') }}</p>
                        </div>
                    </a>
                @endcan
                @can('voir_historique')
                    <a href="{{ route('model_changes.index') }}" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <x-icons.history class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Historique') }}</h1>
                            <p class="p-1 rounded-sm">{{ __('Voir l\'historique des modifications') }}</p>
                        </div>
                    </a>
                @endcan
                @can('gerer_les_donnees_de_reference')
                    <a href="{{ route('reference-data.index') }}" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <x-icons.database class="w-14 h-14 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Données de référence') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Gérer les familles, sous-familles et autres données de base') }}</p>
                        </div>
                    </a>
                @endcan
                @can('gerer_mail_templates')
                    <a href="{{ route('mailtemplates.index') }}" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <x-icons.inbox-text class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Modèles de mail') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Gérer les modèles de mail et la signature') }}</p>
                        </div>
                    </a>
                @endcan
                @can('voir_les_ddp_et_cde')
                    <a href="{{ route('administration.cdeNote.index',1) }}" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <x-icons.edit-note class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Notes de commande') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Gérer les notes de commande') }}</p>
                        </div>
                    </a>
                @endcan
                @can('gerer_info_entreprise')
                    <a href="{{ route('administration.info') }}" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <x-icons.entreprise class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('informations entreprise') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Gérer les informations des entreprises') }}</p>
                        </div>
                    </a>
                @endcan
                @can('gerer_les_medias')
                    <a href="{{ route('media.index') }}" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <x-icons.attachement class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Pièces jointes') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Gérer les pièces jointes') }}</p>
                        </div>
                    </a>
                @endcan
                @can('gerer_l_application')
                    <a href="{{ route('administration.appsettings.index') }}" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <x-icons.settings class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                        <div class=" flex flex-col justify-between">
                            <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Paramètres de l\'application') }}</h1>
                            <p class=" p-1 rounded-sm">{{ __('Gérer les paramètres principaux de l\'application') }}</p>
                        </div>
                    </a>
                @endcan
                <a href="{{ route('administration.icons') }}" class="flex p-6 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 w-96 h-auto rounded-md cursor-pointer transition-all hover:scale-105 hover:bg-gray-200 dark:hover:bg-gray-700">
                    <x-icons.settings class="w-12 h-12 mr-2 fill-gray-400 dark:fill-gray-100" />
                    <div class=" flex flex-col justify-between">
                        <h1 class="text-3xl font-bold mb-6 text-left">{{ __('Icons') }}</h1>
                        <p class=" p-1 rounded-sm">{{ __('Voir tout les icons utilisé') }}</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
