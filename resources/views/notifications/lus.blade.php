<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notifications ') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ Auth::user()->role->name }}
        </p>
    </x-slot>
    <div x-data="{ activeTab: 'tab1' }">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <div class="p-4">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Notifications') }}
                                </h2>
                                <div class="mt-4">
                                    @if (session('notification'))
                                        <div class="bg-green-500 text-white p-2 rounded-sm mb-4">
                                            {{ session('notification') }}
                                        </div>
                                    @endif
                                    <ul class="flex border-b">
                                        <li class="mr-1">
                                            <a href="{{ route('notifications.index', ['activeTab' => 'tab1']) }}"
                                                class="inline-block py-2 px-4 text-gray-500">Tout
                                                @if ($_notifications_count > 0)
                                                    <span
                                                        class="relative bottom-2 right-4 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $_notifications_count }}</span>
                                                @endif
                                            </a>
                                        </li>
                                        <li class="mr-1">
                                            <a href="{{ route('notifications.index', ['activeTab' => 'tab2']) }}"
                                                class="inline-block py-2 pl-4 text-gray-500">Système
                                                @if ($_notificationsSystem_count > 0)
                                                    <span
                                                        class="relative bottom-2 right-4 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $_notificationsSystem_count }}</span>
                                                @endif
                                            </a>
                                        </li>
                                        <li class="mr-1">
                                            <p
                                                class="inline-block py-2 px-4 border-b-2 border-blue-500 text-blue-500 :text-gray-500">
                                                Lu</p>
                                        </li>
                                    </ul>
                                    <div>
                                        <x-table-notifications :notifications="$notifications_readed" :specifyType="true" :tab="'tab3'"
                                            :scrollInfini="true" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let page = 1; // La page actuelle
        let loading = false; // Pour éviter de déclencher plusieurs requêtes en même temps

        window.onscroll = function() {
            // Vérifier si on est proche du bas de la page
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100 && !loading) {
                page++;
                loadMoreNotifications(page);
                console.log('scroll');
            }
        };

        function loadMoreNotifications(page) {
            if (!loading) {
                loading = true;
                document.getElementById('loading-message').style.display = 'block'; // Afficher le message de chargement

                fetch('?page=' + page, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        return response.text();
                    })
                    .then(data => {
                        console.log(data);

                        if (data.trim().length === 0) {
                            window.onscroll = null; // Désactiver le scroll infini si plus de données
                            document.getElementById('loading-message').style.display =
                                'none'; // Cacher le message de chargement
                            return;
                        }

                        document.getElementById('notification-list').insertAdjacentHTML('beforeend', data);

                        loading = false;
                        document.getElementById('loading-message').style.display =
                            'none'; // Cacher le message de chargement
                    })
                    .catch(error => {
                        loading = false;
                        document.getElementById('loading-message').style.display =
                            'none'; // Cacher le message de chargement
                    });
            }
        }
    </script>

</x-app-layout>
