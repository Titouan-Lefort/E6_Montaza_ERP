<x-app-layout>
    @section('title', 'Données de référence')

    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('administration.index') }}"
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                {{ __('Administration') }}
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                >>
            </h2>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Données de référence') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        activeTab: '{{ $activeTab }}',
        loading: false,

        async switchTab(tab) {
            if (this.activeTab === tab) return;

            this.loading = true;
            this.activeTab = tab;

            // Mettre à jour l'URL
            const newUrl = new URL(window.location);
            newUrl.searchParams.set('tab', tab);
            window.history.pushState({ tab: tab }, '', newUrl);

            try {
                const response = await fetch(`{{ route('reference-data.index') }}?tab=${tab}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                document.getElementById('tab-content').innerHTML = data.html;
                document.getElementById('modals-container').innerHTML = data.modals || '';

                // Réinitialiser Alpine.js pour les nouveaux éléments
                if (window.Alpine) {
                    Alpine.initTree(document.getElementById('tab-content'));
                    Alpine.initTree(document.getElementById('modals-container'));
                }
            } catch (error) {
                console.error('Erreur lors du chargement:', error);
            } finally {
                this.loading = false;
            }
        },

        // Gérer le bouton retour du navigateur
        init() {
            window.addEventListener('popstate', (event) => {
                if (event.state && event.state.tab) {
                    this.switchTab(event.state.tab);
                } else {
                    // Si pas d'état, récupérer depuis l'URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const tab = urlParams.get('tab') || 'familles';
                    if (this.activeTab !== tab) {
                        this.switchTab(tab);
                    }
                }
            });

            // Définir l'état initial dans l'historique
            const currentUrl = new URL(window.location);
            if (!currentUrl.searchParams.has('tab')) {
                currentUrl.searchParams.set('tab', this.activeTab);
                window.history.replaceState({ tab: this.activeTab }, '', currentUrl);
            }
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Onglets de navigation -->
            <div class="mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <button x-on:click="switchTab('familles')"
                                :class="activeTab === 'familles' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            Familles & Sous-familles
                        </button>
                        <button x-on:click="switchTab('formes')"
                                :class="activeTab === 'formes' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            Formes juridiques
                        </button>
                        <button x-on:click="switchTab('dossiers')"
                                :class="activeTab === 'dossiers' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            Dossiers standards
                        </button>
                        <button x-on:click="switchTab('pays')"
                                :class="activeTab === 'pays' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            Pays
                        </button>
                        <button x-on:click="switchTab('codes-ape')"
                                :class="activeTab === 'codes-ape' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            Codes APE
                        </button>
                        <button x-on:click="switchTab('autres')"
                                :class="activeTab === 'autres' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                            Autres
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Indicateur de chargement -->
            <div x-show="loading" class="flex justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>

            <!-- Contenu des onglets -->
            <div id="tab-content">
                @switch($activeTab)
                    @case('familles')
                        @if(isset($familles))
                            @include('administration.reference-data.tabs.familles', compact('familles'))
                        @endif
                        @break
                    @case('formes')
                        @if(isset($formesJuridiques))
                            @include('administration.reference-data.tabs.formes', compact('formesJuridiques'))
                        @endif
                        @break
                    @case('dossiers')
                        @if(isset($dossiersStandards))
                            @include('administration.reference-data.tabs.dossiers', compact('dossiersStandards'))
                        @endif
                        @break
                    @case('pays')
                        @if(isset($pays))
                            @include('administration.reference-data.tabs.pays', compact('pays'))
                        @endif
                        @break
                    @case('codes-ape')
                        @if(isset($codesApe))
                            @include('administration.reference-data.tabs.codes-ape', compact('codesApe'))
                        @endif
                        @break
                    @case('autres')
                        @if(isset($conditionsPaiement) && isset($materials) && isset($unites))
                            @include('administration.reference-data.tabs.autres', compact('conditionsPaiement', 'materials', 'unites'))
                        @endif
                        @break
                @endswitch
            </div>

        </div>
    </div>

    <!-- Container pour les modales -->
    <div id="modals-container">
        @if($activeTab === 'familles' && isset($familles))
            @include('administration.reference-data.modals.famille', compact('familles'))
            @include('administration.reference-data.modals.sous-famille', compact('familles'))
        @endif

        @if($activeTab === 'formes' && isset($formesJuridiques))
            @include('administration.reference-data.modals.forme-juridique', compact('formesJuridiques'))
        @endif

        @if($activeTab === 'dossiers' && isset($dossiersStandards))
            @include('administration.reference-data.modals.dossier-standard', compact('dossiersStandards'))
        @endif

        @if($activeTab === 'pays' && isset($pays))
            @include('administration.reference-data.modals.pays', compact('pays'))
        @endif

        @if($activeTab === 'codes-ape' && isset($codesApe))
            @include('administration.reference-data.modals.code-ape', compact('codesApe'))
        @endif

        @if($activeTab === 'autres')
            @if(isset($conditionsPaiement))
                @include('administration.reference-data.modals.condition-paiement', compact('conditionsPaiement'))
            @endif
            @if(isset($materials))
                @include('administration.reference-data.modals.material', compact('materials'))
            @endif
            @if(isset($unites))
                @include('administration.reference-data.modals.unite', compact('unites'))
            @endif
        @endif
    </div>

</x-app-layout>
