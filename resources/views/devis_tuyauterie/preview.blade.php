<x-app-layout>
    @section('title', 'Prévisualisation Devis - ' . ($devis->reference_projet ?? $devis->id))
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('devis_tuyauterie.index') }}" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Devis</a>
                >>
                <a href="{{ route('devis_tuyauterie.show', $devis->id) }}" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Détail Devis</a>
                >> Prévisualisation
            </h2>
        </div>
    </x-slot>

    <div class="max-w-8xl py-4 mx-auto sm:px-4 lg:px-6">
        <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-6 rounded-md shadow-md">
            <div class="flex justify-between items-center flex-wrap mb-6">
                <h1 class="text-3xl font-bold text-left mr-2">Devis {{ $devis->reference_projet ?? $devis->id }}</h1>
                <div class="flex gap-2">
                    <button onclick="toggleEmailForm()" class="btn inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Envoyer par Email
                    </button>
                    <a href="{{ route('devis_tuyauterie.download_pdf', $devis->id) }}" class="btn inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Télécharger le PDF
                    </a>
                    <a href="{{ route('devis_tuyauterie.show', $devis->id) }}" class="btn inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Retour
                    </a>
                </div>
            </div>

            <!-- Section d'envoi par email -->
            <div id="emailForm" class="hidden mb-6 p-4 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700">
                <h3 class="text-lg font-semibold mb-4">Envoyer le devis par email</h3>

                @if(session('email_success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('email_success') }}
                    </div>
                @endif

                @if(session('email_error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('email_error') }}
                    </div>
                @endif

                {{-- Afficher l'expéditeur --}}
                <div class="mb-4 p-3 rounded-md bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Expéditeur :</span>
                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-100">
                            <x-icons.new-contact size="1" class="text-gray-500 dark:text-gray-300" />
                            <span class="font-medium">{{ Auth::user()->getName() }}</span>
                            <span class="text-gray-500 dark:text-gray-400">&lt;{{ Auth::user()->email }}&gt;</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('devis_tuyauterie.send_email', $devis->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="email_destinataire" class="block text-sm font-medium mb-2">
                                Email du destinataire <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   id="email_destinataire"
                                   name="email_destinataire"
                                   value="{{ old('email_destinataire', optional($devis->societeContact)->email ?? '') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100">
                            @error('email_destinataire')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="email_sujet" class="block text-sm font-medium mb-2">
                                Sujet <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="email_sujet"
                                   name="email_sujet"
                                   value="{{ old('email_sujet', 'Devis ' . ($devis->reference_projet ?: $devis->id)) }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100">
                            @error('email_sujet')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="email_message" class="block text-sm font-medium mb-2">
                                Message (optionnel)
                            </label>
                            <textarea id="email_message"
                                      name="email_message"
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100"
                                      placeholder="Message personnalisé à ajouter dans l'email...">{{ old('email_message') }}</textarea>
                            @error('email_message')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Envoyer
                            </button>
                            <button type="button" onclick="toggleEmailForm()" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Annuler
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="w-full h-screen">
                <object data="{{ route('devis_tuyauterie.pdf', $devis->id) }}" type="application/pdf" width="100%" height="100%">
                    <p>Il semble que vous n'ayez pas de plugin PDF pour ce navigateur. Pas de problème... vous pouvez <a href="{{ route('devis_tuyauterie.download_pdf', $devis->id) }}">cliquer ici pour télécharger le fichier PDF.</a></p>
                </object>
            </div>
        </div>
    </div>

    <script>
        function toggleEmailForm() {
            const form = document.getElementById('emailForm');
            form.classList.toggle('hidden');
        }

        // Afficher automatiquement le formulaire si message de succès ou erreur
        @if(session('email_success') || session('email_error') || $errors->has('email_destinataire') || $errors->has('email_sujet') || $errors->has('email_message'))
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('emailForm').classList.remove('hidden');
            });
        @endif
    </script>
</x-app-layout>
