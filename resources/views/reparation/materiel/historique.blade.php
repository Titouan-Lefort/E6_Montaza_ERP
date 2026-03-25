 <x-app-layout>
    @section('title', 'Historique du matériel')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Matériels
            </h2>
            <div class="flex flex-wrap gap-2 items-center">
                <a href="{{ route('reparation.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour aux réparations
                </a>
                <a href="{{ route('reparation.materiel.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Accéder au matériel actuel
                </a>
            </div>
        </div>
    </x-slot>
    <div class="py-8 ">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 ">
            <div class="bg-white dark:bg-gray-800 sm:rounded-lg shadow-md">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead
                                class="bg-linear-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800 text-gray-700 dark:text-gray-100">
                                <tr c>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Référence</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Désignation</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Numéro de série</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Date d'acquisition</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-100" id="body_table">
                                @forelse ($materiels as $materiel)
                                    <tr>
                                        <td class="text-left py-3 px-4">{{ $materiel->reference ?? '-'}}</td>
                                        <td class="text-left py-3 px-4">{{ $materiel->designation ?? '-'}}</td>
                                        <td class="text-left py-3 px-4">{{ $materiel->numero_serie ?? '-'}}</td>
                                        <td class="text-left py-3 px-4">{{ $materiel->status ?? '-'}}</td>
                                        <td class="text-left py-3 px-4">{{ $materiel->acquisition_date ?? '-'}}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 px-4">Aucun matériel disponible.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4 flex justify-center items-center pb-3 pagination">
                    <div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
              document.addEventListener('alpine:init', () => {
            window.addEventListener('open-modal', function(e) {
                if (e.detail === 'create-materiel-modal') {
                    const modalBody = document.getElementById('create-materiel-modal-body');
                    fetch("{{ route('reparation.materiel.create') }}")
                        .then(response => response.text())
                        .then(html => {
                            modalBody.innerHTML = html;
                            attachCreateFormListener();
                        });
                }
            });
        });

    </script>
</x-app-layout>

