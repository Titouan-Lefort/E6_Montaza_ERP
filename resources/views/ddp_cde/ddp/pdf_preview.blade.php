<x-app-layout>
    @section('title', 'Validation - ' . $ddp->code)
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('ddp.index') }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Demandes de prix</a>
                >>
                <a href="{{ route('ddp.show', $ddp->id) }}"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Créer une demande de prix') !!}</a>
                >> Validation
            </h2>
            <a href="{{ route('ddp.annuler', $ddp->id) }}" class="btn">Annuler la ddp</a>
        </div>
    </x-slot>
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">

        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
            <div class="flex justify-between items-center">
                <div class="flex items-center mb-12">
                    <h1 class="text-3xl font-bold  text-left mr-2">{{ $ddp->nom }} - Récapitulatif</h1>
                    <div class="text-center w-fit px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center"
                        style="background-color: {{ $ddp->statut->couleur }}; color: {{ $ddp->statut->couleur_texte }}">
                        {{ $ddp->statut->nom }}</div>
                </div>
                <a href="{{ route('ddp.pdfs.download', $ddp) }}" class="btn">Télécharger tous les PDF</a>

            </div>
            <div class="flex flex-wrap gap-4">
                {{-- @dd($pdfs) --}}
                @foreach ($pdfs as $pdf)
                    @php
                        $ddpannee = explode('-', $ddp->code)[1];
                    @endphp
                    <div class="flex flex-col gap-2 bg-gray-100 dark:bg-gray-700 p-4 rounded-md hover:scale-105 cursor-pointer transition-all relative"
                        id="pdf-{{ $pdf }}" title="Ouvrir le PDF dans un autre onglet">
                        <div class="flex justify-between items-center mb-2">
                            <h2
                                class="text-xl font-semibold text-gray-700 dark:text-gray-200  border border-gray-300 dark:border-gray-700 pb-2 hover">
                                {{ explode('_', $pdf)[count(explode('_', $pdf)) - 1] }}</h2>
                            <a href="{{ route('ddp.pdfdownload', ['ddp' => $ddp, 'annee' => $ddpannee, 'nom' => $pdf]) }}"
                                class="" title="Télécharger le PDF">
                                <x-icons.download size="2" class="icons" />
                            </a>
                        </div>
                        <div style="background-color: rgba(0,0,0,0); height: 409px; width: 285px; margin-bottom: 15px;"
                            class="absolute bottom-4"></div>
                        <object data="{{ route('ddp.pdfshow', ['ddp' => $ddp, 'annee' => $ddpannee, 'nom' => $pdf]) }}"
                            type="application/pdf" height="424px" width="300px">
                            <p>Il semble que vous n'ayez pas de plugin PDF pour ce navigateur. Pas de problème... vous
                                pouvez <a
                                    href="{{ route('ddp.pdfshow', ['ddp' => $ddp, 'annee' => $ddpannee, 'nom' => $pdf]) }}">cliquer
                                    ici pour télécharger le fichier PDF.</a></p>
                        </object>
                    </div>
                @endforeach
            </div>
            <div>
                <div class="flex justify-between items-center border-b border-gray-300 dark:border-gray-700 mt-6 mb-4">
                    <h1 class="text-3xl font-bold mb-6 text-left">Mails</h1>
                    <a href="{{ route('ddp.skipmails', $ddp) }}" class="btn">Passer cette étape</a>
                </div>
                <div>
                    <form action="{{ route('ddp.sendmails', $ddp) }}" method="POST" id="mailtemplate-form">
                        @csrf
                        <div class="mb-4">
                            <div class="p-4 rounded-md bg-white dark:bg-gray-900 shadow-md">
                                {{-- Afficher l'expéditeur --}}
                                <div class="flex flex-wrap mb-3 pb-3 border-b border-gray-300 dark:border-gray-600">
                                    <div class="pr-4 py-2 font-semibold">De :</div>
                                    <div
                                        class="flex items-center gap-2 text-gray-800 dark:text-gray-100 px-4 py-2 rounded-full text-sm shadow-sm bg-gray-100 dark:bg-gray-800">
                                        <x-icons.new-contact size="1" class="text-gray-500 dark:text-gray-300" />
                                        <span class="font-medium">{{ Auth::user()->getName() }}</span>
                                        <span class="text-gray-500 dark:text-gray-400">&lt;{{ Auth::user()->email }}&gt;</span>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <div class="pr-4 py-2">À :</div>
                                    @foreach ($ddp->SocieteContacts() as $destinataire)
                                        <div
                                            class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 px-4 py-2 rounded-full text-sm shadow-sm">
                                            <x-icons.mail size="1" class="text-gray-500 dark:text-gray-300" />
                                            <span>{{ $destinataire->email }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <x-input-label for="email_subject" :value="__('Objet du mail')" />
                            <x-text-input id="sujet" class="block mt-1 w-full" type="text" name="sujet"
                                required autofocus value="{{ $mailtemplate->sujet }}" />
                        </div>
                        <div class="mb-8">
                            <x-input-label for="email_body" :value="__('Contenu du mail')" />
                            <div id="editor-container" style="height: 150px;" class=""></div>
                            <textarea name="contenu" id="contenu" hidden></textarea>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('Signature') }}
                                <x-tooltip position="top" class="">
                                    <x-slot:slot_item>
                                        <x-icons.question />
                                    </x-slot:slot_item>
                                    <x-slot:slot_tooltip>
                                        <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">Signature utilisée pour
                                            les mails</p>
                                        <a href="{{ route('mailtemplates.index') }}" class="btn">
                                            <x-icons.edit class="icons mr-2" /> Modifier la signature
                                        </a>
                                    </x-slot:slot_tooltip>

                                </x-tooltip>
                            </h3>
                        </div>
                        <img src="data:image/png;base64,{{ $signature = base64_encode(file_get_contents(Storage::path('signature/signature.png'))) }}"
                            alt="" class="max-w-full h-auto mb-8">
                        <div class="flex justify-between items-center">
                            <a href="{{ route('ddp.cancel_validate', $ddp->id) }}" class="btn h-fit">Retour</a>
                            <button type="submit" class="btn float-right -mt-6">Envoyer les mails</button>

                        </div>
                    </form>

                </div>

            </div>
        </div>
        <script>
            document.querySelectorAll('[id^="pdf-"]').forEach(function(element) {
                element.addEventListener('click', function(event) {
                    if (event.target.tagName.toLowerCase() !== 'a') {
                        const pdfUrl = element.querySelector('object').data;
                        window.open(pdfUrl, '_blank');
                    }
                });
            });
        </script>
        <!-- Initialisation de Quill -->
        <script>
            var quill = new Quill('#editor-container', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'underline'], // Gras, Souligné
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }], // Listes
                        ['clean'] // Effacer le formatage
                    ]
                }
            });
            document.addEventListener('DOMContentLoaded', function() {
                quill.root.innerHTML = @json($mailtemplate->contenu);
            });
            // Synchroniser le contenu de Quill avec le textarea lors de l'envoi du formulaire
            document.querySelector('#mailtemplate-form').onsubmit = function(event) {
                event.preventDefault(); // Empêche l'envoi du formulaire

                var contenu = quill.root.innerHTML;
                contenu = contenu.replace(/</g, 'CHEVRON-GAUCHE').replace(/>/g, 'CHEVRON-DROIT');
                document.querySelector('#contenu').value = contenu;

                // Vérifiez si le contenu est vide
                if (contenu.trim() === '') {
                    alert('Le contenu ne peut pas être vide.');
                    return false;
                }

                // Si tout est bon, soumettez le formulaire
                this.submit();
            };
        </script>
        <style>
            .ql-toolbar {
                background-color: #aaaaaa;
                /* Fond clair */
                border: 1px solid #e5e7eb;
                /* Bordure claire */
                border-top-left-radius: 0.375rem;
                border-top-right-radius: 0.375rem;
                /* Coins arrondis */
            }
        </style>

    </div>
</x-app-layout>
