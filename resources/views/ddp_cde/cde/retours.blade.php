<x-app-layout>
    @section('title', 'Retours - ' . $cde->code)
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('cde.index') }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Commandes</a>
                    >>
                    <a href="{{ route('cde.show', $cde->id) }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Créer une demande de prix') !!}</a>
                    >> Retours
                </h2>

            </div>
            <a href="{{ route('cde.pdfs.download', $cde) }}" class="btn">Télécharger le PDF</a>
            <a href="{{ route('cde.pdfs.pdfdownload_sans_prix', $cde) }}" class="btn">Télécharger le PDF sans prix</a>
            <a href="{{ route('cde.annuler', $cde->id) }}" class="btn">Annuler la commande</a>

        </div>
    </x-slot>
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md"
            id="retour-container">
            <div class="flex justify-between items-center mb-6 flex-wrap ">
                <div class="flex items-center mb-12 flex-wrap ">
                    <h1 class="text-3xl font-bold  text-left mr-2">{{ $cde->nom }} - Livraison</h1>
                    <div class="text-center w-fit px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                        style="background-color: {{ $cde->statut->couleur }}; color: {{ $cde->statut->couleur_texte }}">
                        {{ $cde->statut->nom }}</div>
                </div>
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 flex items-center hidden"
                        title="Demande de prix en cours d'enregistrement" id="save-status-0">Enregistrement
                        en
                        cours...<x-icons.progress-activity size="2" /></h1>
                    <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 {{ isset($cde) ? '' : 'hidden' }}"
                        title="Demande de prix enregistré avec succès" id="save-status-1">Enregistré</h1>
                    <h1 class="text-xl font-semibold text-gray-500 dark:text-gray-400 {{ isset($cde) ? 'hidden' : '' }}"
                        title="Demande de prix non enregistrée" id="save-status-2">Non-enregistré</h1>
                    <button class="" id="refresh">
                        <x-icons.refresh size="2" class="icons" />
                    </button>
                </div>
            </div>
            <div class="flex flex-col gap-1 mr-8 -mt-15">
                @if ($cde->societeContacts->isNotEmpty() && $cde->societeContacts->first()->societe)
                    <div class="text-sm font-semibold">
                        {{ $cde->societeContacts->first()->societe->raison_sociale }}
                    </div>
                @endif
            </div>
            <small class="text-gray-500 dark:text-gray-400 block  mb-2">Remplissez les dates de livraison de
                chaque matière</small>

            <style>
                /* Style pour centrer le texte de la première ligne */
                .ht-center-first-row {
                    text-align: center;
                }

                thead {
                    background: none;
                }

                .rowHeader {
                    text-align: left !important;
                }

                .row-header-left {
                    text-align: left !important;
                }
            </style>
            <div class="flex flex-col gap-4">
                <div class="">
                    <h1
                        class="text-xl font-semibold text-gray-700 dark:text-gray-200 border-b border-gray-500 pb-2 mb-4 w-4/5">
                        Retour de commande</h1>
                    <div id="handsontable-container" class="ht-theme-main-dark-auto "></div>
                </div>
                {{-- Affichage des changements de livraison --}}
                @include('ddp_cde.cde.partials.changement_livraison')

                <div>
                    @include('ddp_cde.cde.partials.commentaire')
                </div>
                <div class="flex justify-between items-center mt-6  w-full">
                    <a href="{{ route('cde.cancel_validate', $cde->id) }}" class="btn float-right">Retour</a>
                    <a href="{{ route('cde.terminer', $cde->id) }}" class="btn float-right">Terminer</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

        document.addEventListener('DOMContentLoaded', function() {

            const container = document.getElementById('handsontable-container');
            const rowHeaders = [
                @foreach ($cde->cdeLignes as $cdeLigne)
                    "<span title='{{ $cdeLigne->ref_interne }} {{ $cdeLigne->designation }}' class='text-xs'>{{ $cdeLigne->ref_interne }} {{ $cdeLigne->designation }}<span>",
                @endforeach
                'Total',
            ];

            const colHeaders = [
                'Statut',
                'Quantité',
                'PU HT',
                'Expédition',
                'Date livraison réelle',
                'Non livré',
            ];

            const mode_livraison = @json($typeExpedition);

            const columns = [{
                    type: 'select',
                    selectOptions: ['En cours', 'Annulée'],
                },
                {
                    type: 'numeric',
                },
                {
                    type: 'numeric',
                },
                {
                    type: 'select',
                    selectOptions: mode_livraison,
                },
                {
                    type: 'date',
                    dateFormat: 'DD/MM/YYYY',
                    correctFormat: true,
                    datePickerConfig: {
                        firstDay: 1,
                        showWeekNumber: true,
                        numberOfMonths: 1,
                        i18n: {
                            previousMonth: 'Mois précédent',
                            nextMonth: 'Mois suivant',
                            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                                'Septembre', 'Octobre', 'Novembre', 'Décembre'
                            ],
                            weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam']
                        }
                    },
                },
                {
                    type: 'checkbox',
                    className: 'htCenter',
                }
            ];

            const data = @json($data);

            const hot = new Handsontable(container, {
                data: data,
                language: 'fr-FR',
                licenseKey: 'non-commercial-and-evaluation',
                rowHeaders: rowHeaders,
                colHeaders: colHeaders,
                rowHeaderWidth: Math.min(
                    rowHeaders.reduce((maxLength, header) => Math.max(maxLength, header.length), 0) *
                    10,
                    Math.min(window.innerWidth *
                        0.4) // Limite maximale de largeur ajustée selon la taille de l'écran
                ),
                columns: columns,
                manualColumnResize: true,
                manualRowResize: true,
                contextMenu: false,
                preventOverflow: 'horizontal',
                autoColumnSize: false,
                className: 'htLeft',
                afterGetRowHeader: function(row, th) {
                    th.classList.add('row-header-left');
                },

                colWidths: [100, 100, 100, 120, 200, 100],
                cells: function(row, col, prop) {
                    var cellProperties = {};
                    datarows = this.instance.getData();
                    if (datarows[row][0] === 'Annulée' && col !== 0) {
                        cellProperties.readOnly = true;
                    }
                    // Si "Non livré" est coché (colonne 5), la date (colonne 4) est vide et readOnly ?
                    // On peut le gérer dans afterChange pour vider la date
                    return cellProperties;
                },
            });

            const debouncedSaveChanges = debounce(saveChanges, 500);
            hot.addHook('afterChange', function(changes, source) {
                if (source === 'loadData') return;

                if (changes) {
                    changes.forEach(([row, prop, oldValue, newValue]) => {
                        // Si on coche "Non livré" (index 5), on vide la date (index 4)
                        if (prop === 5 && newValue === true) {
                            hot.setDataAtCell(row, 4, null);
                        }
                        // Si on met une date (index 4), on décoche "Non livré" (index 5)
                        if (prop === 4 && newValue !== null && newValue !== '') {
                            hot.setDataAtCell(row, 5, false);
                        }
                    });
                    debouncedSaveChanges();
                }
            });

            document.querySelector('#refresh').addEventListener('click', saveChanges);

            function debounce(func, delay) {
                let timer;
                return function(...args) {
                    clearTimeout(timer);
                    timer = setTimeout(() => func.apply(this, args), delay);
                };
            }

            function saveChanges() {
                const saveStatus0 = document.getElementById('save-status-0');
                const saveStatus1 = document.getElementById('save-status-1');
                const saveStatus2 = document.getElementById('save-status-2');
                saveStatus0.classList.remove('hidden');
                saveStatus1.classList.add('hidden');
                saveStatus2.classList.add('hidden');

                const exportPlugin = hot.getPlugin('exportFile');
                const exportedString = exportPlugin.exportAsString('csv', {
                    bom: false,
                    columnDelimiter: ',',
                    columnHeaders: false,
                    exportHiddenColumns: true,
                    exportHiddenRows: true,
                    rowDelimiter: '\r\n',
                    rowHeaders: false,
                });

                fetch('/cde/{{ $cde->id }}/save-retours', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            data: exportedString
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        saveStatus0.classList.add('hidden');
                        saveStatus1.classList.remove('hidden');
                        saveStatus2.classList.add('hidden');
                    })
                    .catch((error) => {
                        saveStatus0.classList.add('hidden');
                        saveStatus1.classList.add('hidden');
                        saveStatus2.classList.remove('hidden');
                        console.error('Error:', error);
                    });
            }
        });
    </script>
    <livewire:media-sidebar :model="'cde'" :model-id="$cde->id" />



</x-app-layout>
