    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Notifications ') }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ Auth::user()->role->name }}
            </p>
        </x-slot>
        <div x-data="{ activeTab: '{{ $activeTab }}' }">
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
                                        <ul class="flex border-b">
                                            <li class="mr-1">
                                                <a @click.prevent="activeTab = 'tab1'"
                                                    :class="activeTab === 'tab1' ? 'border-b-2 border-blue-500 text-blue-500' :
                                                        'text-gray-500'"
                                                    class="inline-block py-2 px-4" href="#">Tout
                                                    @if ($_notifications_count > 0)
                                                    <span id="notifications-count"
                                                        class="relative bottom-2 right-4 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $_notifications_count }}</span>
                                                    @endif
                                                    </a>
                                            </li>
                                            <li class="mr-1">
                                                <a @click.prevent="activeTab = 'tab2'"
                                                    :class="activeTab === 'tab2' ? 'border-b-2 border-blue-500 text-blue-500' :
                                                        'text-gray-500'"
                                                    class="inline-block py-2 pl-4" href="#">Système
                                                    @if ($_notificationsSystem_count > 0)
                                                    <span id="notifications-system-count"
                                                        class=" relative bottom-2 right-4 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $_notificationsSystem_count }}</span>
                                                    @endif
                                                    </a>
                                            </li>
                                            <li class="mr-1">
                                                <a class="inline-block py-2 px-4 text-gray-500" href="{{ route('notifications.lus') }}">Lu</a>
                                            </li>
                                        </ul>
                                        <div>
                                            <div x-show="activeTab === 'tab1'" id="tab1">
                                                <x-table-notifications :notifications="$notifications" :specifyType="true" :tab="'tab1'"
                                                    :scrollInfini="true" />
                                            </div>
                                            <div x-show="activeTab === 'tab2'" id="tab2">
                                                <x-table-notifications :notifications="$notificationsSystem" :scrollInfini="true"
                                                    :tab="'tab2'" />
                                            </div>
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
            let page = {
                tab1: 1,
                tab2: 1
            }; // La page actuelle pour chaque onglet
            let loading = {
                tab1: false,
                tab2: false
            }; // Pour éviter de déclencher plusieurs requêtes en même temps

            window.onscroll = function() {
                let activeTab = getActiveTab();
                // Vérifier si on est proche du bas de la page
                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100 && !loading[activeTab]) {
                    page[activeTab]++;
                    loadMoreNotifications(page[activeTab], activeTab);
                    console.log('scroll' + activeTab);
                }
            };

            function loadMoreNotifications(page, tab) {
                if (!loading[tab]) {
                    loading[tab] = true;
                    document.getElementById('loading-message').style.display = 'block'; // Afficher le message de chargement

                    fetch('?page=' + page + '&tab=' + tab, {
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
                            document.getElementById('loading-message').style.display = 'none'; // Cacher le message de chargement
                            return;
                        }

                        document.getElementById('notification-list-' + tab).insertAdjacentHTML('beforeend', data);

                        loading[tab] = false;
                        document.getElementById('loading-message').style.display = 'none'; // Cacher le message de chargement
                    })
                    .catch(error => {
                        loading[tab] = false;
                        document.getElementById('loading-message').style.display = 'none'; // Cacher le message de chargement
                    });
                }
            }

            function getActiveTab() {
                let tabs = document.querySelectorAll('[id^="tab"]');
                for (let tab of tabs) {
                    if (window.getComputedStyle(tab).display !== 'none') {
                        return tab.id;
                    }
                }
                return 'tab1'; // Default to tab1 if no active tab is found
            }
        </script>

    </x-app-layout>

