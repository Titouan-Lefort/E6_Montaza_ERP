<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-8xl mx-auto px-4 sm:px-6 ">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <x-nav-link :href="route('accueil')" :active="request()->routeIs('accueil')">
                        <x-application-logo class="block h-14 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </x-nav-link>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if (Auth::check())
                        <x-nav-link :href="route('accueil')" :active="request()->routeIs('accueil')">
                            {{ __('Accueil') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        @if (Auth::user()->hasPermission('voir_les_societes'))
                            <x-nav-link :href="route('societes.index')" :active="request()->routeIs('societes.index')">
                                {{ __('Sociétés') }}
                            </x-nav-link>
                        @endif
                        @can('voir_les_matieres')
                            <x-nav-link :href="route('matieres.index')" :active="request()->routeIs('matieres.index')">
                                {{ __('Matières') }}
                            </x-nav-link>
                        @endcan
                        @can('voir_les_ddp_et_cde')
                            <x-nav-link :href="route('ddp_cde.index')" :active="request()->routeIs('ddp_cde.index')" title="Demande de prix et Commande">
                                {{ __('DDP/CDE') }}
                            </x-nav-link>
                        @endcan
                        @can('voir_les_affaires')
                            <x-nav-link :href="route('affaires.index')" :active="request()->routeIs('affaires.index')" title="Affaires">
                                {{ __('Affaires') }}
                            </x-nav-link>
                        @endcan

                         @can('voir_les_reparations')
                            <x-nav-link :href="route('reparation.index')" :active="request()->routeIs('reparation.index')" title="Réparations du matériels">
                                {{ __('Réparations du matériels') }}
                            </x-nav-link>
                        @endcan
                        @can('voir_les_devis')
                            <x-nav-link :href="route('devis_tuyauterie.index')" :active="request()->routeIs('devis_tuyauterie.*')" title="Devis">
                                {{ __('Devis') }}
                            </x-nav-link>
                        @endcan
                    @endif
                </div>
            </div>

            <!-- Shortcuts Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if (Auth::check())
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'shortcuts-modal')" title="Raccourcis"
                                class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-hidden transition ease-in-out duration-150">
                                <x-icons.apps :size="1" class="icons" />
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="flex justify-between p-2">
                                <h2 class="font-semibold text-l text-gray-800 dark:text-gray-200 leading-tight ml-1">
                                    {{ __('Raccourcis') }}</h2>
                                <a href="{{ route('shortcuts.index') }}"
                                    class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-hidden transition ease-in-out duration-150"
                                    title="Modifier les raccourcis">
                                    <x-icons.apps-edit :size="1" class="icons mt-1 mr-1" />
                                </a>
                            </div>
                            <div class="p-4">
                                @include('shortcuts.partials.shortcuts')
                            </div>
                        </x-slot>
                    </x-dropdown>

                    <div class="relative">
                        <button x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'notifications-modal')"
                            class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-hidden transition ease-in-out duration-150">
                            <x-icon type="bell" :size="1" class="icons" />
                            @if ($_notifications_count > 0)
                                <span
                                    class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $_notifications_count }}</span>
                            @endif
                        </button>
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-hidden transition ease-in-out duration-150">
                                <div>
                                    @if (Auth::check())
                                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                    @endif
                                </div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit', ['id' => Auth::user()->id])">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                            <x-dropdown-link :href="route('administration.index')">
                                {{ __('Administration') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('documentation.index')">
                                {{ __('Documentation') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    <div x-data="{ activeTab: 'tab1' }">
                        <!-- Your modal code -->
                        <x-modal name="notifications-modal" :show="session()->has('notification') && Route::currentRouteName() != 'notifications.index'
                            ? true
                            : false">
                            <script>
                                document.addEventListener('open-modal', event => {
                                    const modalTitle = event.detail;
                                    if (modalTitle === 'notifications-modal') {
                                        const containerModal = document.getElementById('notifications-modal');
                                        containerModal.innerHTML =
                                            '<div id="loading-spinner" class=" m-6 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-32 w-full"><div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div></div><style>.loader {border-top-color: #3498db;animation: spinner 1.5s linear infinite;}@keyframes spinner {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}</style>';

                                        fetch('{{ route('notifications.modal') }}')
                                            .then(response => response.text())
                                            .then(html => {
                                                const modalContent = document.getElementById('notifications-modal');
                                                if (modalContent) {
                                                    // Utilisation de DOMParser pour analyser le HTML
                                                    const parser = new DOMParser();
                                                    const doc = parser.parseFromString(html, 'text/html');

                                                    // Insérer le contenu du body
                                                    modalContent.innerHTML = doc.body.innerHTML;

                                                    // Fonction pour exécuter les scripts
                                                    function executeScripts(doc) {
                                                        doc.querySelectorAll('script.SCRIPT').forEach(script => {
                                                            const newScript = document.createElement('script');
                                                            newScript.textContent = script.textContent;
                                                            document.body.appendChild(newScript);
                                                        });
                                                    }

                                                    // Exécuter les scripts après avoir rempli le modal
                                                    executeScripts(doc);
                                                } else {
                                                    console.error('Modal notification content element not found');
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Erreur de chargement:', error);
                                            });
                                    }
                                });
                            </script>
                            <div class="modal-content" id="notifications-modal">
                                <!-- Content will be loaded here -->
                            </div>
                        </x-modal>

                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-hidden transition ease-in-out duration-150">
                        {{ __('Log in') }}
                    </a>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-hidden focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Navigation Links -->
            @if (Auth::check())
                <x-responsive-nav-link :href="route('accueil')" :active="request()->routeIs('accueil')">
                    {{ __('Accueil') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                @if (Auth::user()->hasPermission('voir_les_societes'))
                    <x-responsive-nav-link :href="route('societes.index')" :active="request()->routeIs('societes.index')">
                        {{ __('Société') }}
                    </x-responsive-nav-link>
                @endif
                @can('voir_les_matieres')
                    <x-responsive-nav-link :href="route('matieres.index')" :active="request()->routeIs('matieres.index')">
                        {{ __('Matieres') }}
                    </x-responsive-nav-link>
                @endcan
                @can('voir_les_ddp_et_cde')
                    <x-responsive-nav-link :href="route('ddp_cde.index')" :active="request()->routeIs('ddp_cde.index')">
                        {{ __('DDP/CDE') }}
                    </x-responsive-nav-link>
                @endcan
                @can('voir_les_affaires')
                    <x-responsive-nav-link :href="route('affaires.index')" :active="request()->routeIs('affaires.index')">
                        {{ __('Affaires') }}
                    </x-responsive-nav-link>
                @endcan
                @can('voir_les_reparations')
                    <x-responsive-nav-link :href="route('reparation.index')" :active="request()->routeIs('reparation.index')">
                        {{ __('Réparations du matériels') }}
                    </x-responsive-nav-link>
                @endcan
                @can('voir_les_devis')
                    <x-responsive-nav-link :href="route('dossiers_devis.index')" :active="request()->routeIs('dossiers_devis.*')">
                        {{ __('Dossiers de Devis') }}
                    </x-responsive-nav-link>
                @endcan
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                @if (Auth::check())
                    <div class="grid-cols-2 grid">
                        <div>
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                {{ Auth::user()->first_name }}
                                {{ Auth::user()->last_name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                        <div>
                            <div class="float-right">

                                <a href="{{ route('notifications.index') }}"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-hidden transition ease-in-out duration-150">

                                    <x-icon :size="1" type="bell" class="icons " />
                                    @if ($_notifications_count > 0)
                                        <span id="notifications-count"
                                            class="relative bottom-3 right-4 inline-flex items-center justify-center px-1 py-1 text-xs font-semibold leading-none text-red-100 bg-red-600 rounded-full">{{ $_notifications_count }}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                @if (Auth::check())
                    <x-responsive-nav-link :href="route('profile.edit', ['id' => Auth::user()->id])">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('administration.index')">
                        {{ __('Administration') }}
                    </x-responsive-nav-link>
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                @else
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                @endif

            </div>
        </div>
    </div>
</nav>
<script>
    function marquerCommeLu(notificationId) {

        const notificationCountElements = document.querySelectorAll('#notifications-count');
        notificationCountElements.forEach(notificationCountElement => {
            let count = parseInt(notificationCountElement.textContent);
            if (!isNaN(count) && count > 0) {
                notificationCountElement.textContent = count - 1;
            }
        });
        const notificationElement = document.getElementById(`notification-${notificationId}`);
        if (notificationElement && notificationElement.classList.contains('system')) {
            const notificationSystemCountElements = document.querySelectorAll('#notifications-system-count');
            notificationSystemCountElements.forEach(notificationSystemCountElement => {
                let count = parseInt(notificationSystemCountElement.textContent);
                if (!isNaN(count) && count > 0) {
                    notificationSystemCountElement.textContent = count - 1;
                }
            });
        }
        while (document.getElementById(`notification-${notificationId}`)) {
            document.getElementById(`notification-${notificationId}`).remove();
        }
        fetch(`/notifications/${notificationId}/lu`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            if (response.ok) {
                // Optionally, you can update the UI to reflect the notification as read
                console.log('Notification marked as read');

            } else {
                console.error('Failed to mark notification as read');
            }
        }).catch(error => {
            console.error('Error:', error);
        });

    }

    function marquerCommeNonLu(notificationId) {
        document.getElementById(`notification-${notificationId}`).remove();
        fetch(`/notifications/${notificationId}/non-lu`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            if (response.ok) {
                // Optionally, you can update the UI to reflect the notification as read
                console.log('Notification marked as unread');

            } else {
                console.error('Failed to mark notification as unread');
            }
        }).catch(error => {
            console.error('Error:', error);
        });

    }
</script>
