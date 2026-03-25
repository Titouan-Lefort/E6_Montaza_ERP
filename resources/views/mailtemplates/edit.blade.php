<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier un modèle de Mail') }}
        </h2>
    </x-slot>

    <!-- Importation de Quill CSS et JS -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 w-full">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg p-4 text-gray-700 dark:text-gray-300">
                @if ($errors->any())
                    <div class="alert alert-danger text-red-500">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('mailtemplates.update', $mailtemplate->id) }}" method="POST"
                    id="mailtemplate-form">
                    @csrf
                    @method('PATCH')
                    <h1 class="text-3xl font-bold mb-6 text-left">{{ $mailtemplate->nom }}</h1>
                    <div class="w-1/2">
                        <x-input-label value="Sujet" />
                        <x-text-input id="sujet" class="block mt-1 w-full" type="text" name="sujet" required
                            autofocus value="{{ $mailtemplate->sujet }}" />
                    </div>
                    <div class="mt-4">
                        <x-input-label value="Contenu" />
                        <div id="editor-container" style="height: 150px;" class=""></div>
                        <textarea name="contenu" id="contenu" hidden></textarea>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('mailtemplates.index') }}" class="btn float-left">Annuler</a>
                        <button class="btn float-right" type="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
</x-app-layout>
