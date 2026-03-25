<x-app-layout>
    @section('title', 'Notes de commandes')

    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('administration.index') }}"
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                {{ __('Administration') }}
            </a>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                >>
            </h2>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Notes de commande') }}
            </h2>
            <select name="choix_entreprise" id="choix_entreprise" class="select w-1/3 ml-12" onchange="change_entreprise()">
                @foreach ($entites as $entite_select)
                    <option value="{{ $entite_select->id }}" {{ $entite_select->id == $entite->id ? 'selected' : '' }}>
                        {{ $entite_select->name }}
                    </option>
                @endforeach
            </select>
            <a href="{{ route('administration.cdeNote.create', $entite->id) }}" class="btn ml-2">
                {{ __('Créer une note') }}
            </a>
        </div>
    </x-slot>
    @vite('resources/js/sortable.js')

    <div class="py-12">
        <div id="info-container"
            class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 max-w-7xl mx-auto">
            <h3 class="font-medium text-lg my-4">Notes de commande</h3>
            <div id="notes-list" class="grid grid-cols-1 gap-2 mb-4">
                @foreach ($cde_notes as $note)
                    <div data-id="{{ $note->id }}">
                        <div class="flex items-center  rounded-md border border-gray-200 dark:border-gray-600 ">
                            <div class="p-3 cursor-grab active:cursor-grabbing">
                                <x-icons.re-order size="2" />
                            </div>
                            <a class=" p-3 pl-0 flex justify-between items-center w-full hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors group"
                                href="{{ route('administration.cdeNote.show', $note->id) }}"
                                title="Modifier la note de commande">
                                <span class=" border-l-1 pl-2"> {{ $note->contenu }}<small
                                        class="text-gray-500 dark:text-gray-400"><br>{{ $note->is_checked ? '✓ précoché' : '' }}</small></span>

                                <div><x-icons.edit-note size="2"
                                        class="dark:group-hover:fill-gray-100 dark:fill-gray-800" /></div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <script>
        function change_entreprise() {
            var id_entreprise = document.getElementById('choix_entreprise').value;
            window.location.href = '/administration/cde-notes/' + id_entreprise;
            document.getElementById('info-container').innerHTML =
                '<div id="loading-spinner" class="m-6 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-32 w-full"><div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div></div><style>.loader {border-top-color: #3498db;animation: spinner 1.5s linear infinite;}@keyframes spinner {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}</style>';
        }
        document.addEventListener('DOMContentLoaded', () => {

            const notesList = document.getElementById('notes-list');

            Sortable.create(notesList, {
                animation: 150,
                onEnd: function() {
                    let order = [];
                    notesList.querySelectorAll('div[data-id]').forEach(el => {
                        order.push(el.getAttribute('data-id'));
                    });

                    fetch('{{ route('administration.cdeNote.updateOrder') }}', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                order: order
                            })
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Erreur de réorganisation');
                            return response.json();
                        })
                        .then(data => {
                            showFlashMessageFromJs('Ordre mis à jour avec succès', 2000, 'success');
                        })
                        .catch(error => {
                            showFlashMessageFromJs(
                                'Une erreur est survenue pendant la réorganisation', 2000,
                                'error');
                            console.error(error);
                        });
                }
            });
        });
    </script>
</x-app-layout>
