{{-- ATTENTION A BIEN METTRE LA CLASS 'SCRIPT' AUX SCRIPTS POUR QU'IL SOIT RECONNUS --}}

<div class="grid grid-cols-3 gap-6 ">
    @foreach ($_shortcuts as $shortcut)
        @if ($shortcut->shortcut->modal)
            @php
                $modal = $shortcut->shortcut->modal;
                $modal = json_decode($modal);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $modal = null;
                }
            @endphp
            <x-tooltip position="top" class="">
                <x-slot:slot_item>
                    <a class="btn mx-auto p-2 hover:bg-gray-50 dark:hover:bg-gray-800 hover:cursor-pointer"
                        title="{{ $shortcut->shortcut->title }}" x-data=""
                        @click.prevent="$dispatch('open-modal', '{{ $modal->title }}')">
                        @php $iconComponent = 'icons.' . $shortcut->shortcut->icon; @endphp
                        <x-dynamic-component :component="$iconComponent" size="1.5" class="icons-no_hover" />
                    </a>
                </x-slot:slot_item>
                <x-slot:slot_tooltip>
                    {{ $shortcut->shortcut->title }}
                </x-slot:slot_tooltip>
            </x-tooltip>
            {{-- Modal --}}
            <x-modal name="{{ $modal->title }}" maxWidth="5xl">
                <script>
                    document.addEventListener('open-modal', event => {
                        const modalTitle = event.detail;
                        if (modalTitle === '{{ $modal->title }}') {
                            const containerModal = document.getElementById('{{ $modal->title }}');
                            containerModal.innerHTML =
                                '<div id="loading-spinner" class=" m-6 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-32 w-full"><div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div></div><style>.loader {border-top-color: #3498db;animation: spinner 1.5s linear infinite;}@keyframes spinner {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}</style>';

                            fetch('{{ route($modal->route) }}')
                                .then(response => response.text())
                                .then(html => {
                                    const modalContent = document.getElementById('{{ $modal->title }}');
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
                                        console.error('Modal content element not found');
                                    }
                                })
                                .catch(error => {
                                    console.error('Erreur de chargement:', error);
                                });
                        }
                    });
                </script>
                <div class="modal-content" id="{{ $modal->title }}">
                    <!-- Content will be loaded here -->
                </div>
            </x-modal>
        @else
            <x-tooltip position="top" class="">
                <x-slot:slot_item>
                    <a href="{{ route($shortcut->shortcut->url) }}"
                        class="btn mx-auto p-2 hover:bg-gray-50 dark:hover:bg-gray-800"
                        title="{{ $shortcut->shortcut->title }}">
                        @php $iconComponent = 'icons.' . $shortcut->shortcut->icon; @endphp
                        <x-dynamic-component :component="$iconComponent" size="1.5" class="icons-no_hover" />
                    </a>
                </x-slot:slot_item>
                <x-slot:slot_tooltip>
                    {{ $shortcut->shortcut->title }}
                </x-slot:slot_tooltip>
            </x-tooltip>
        @endif
    @endforeach
</div>
