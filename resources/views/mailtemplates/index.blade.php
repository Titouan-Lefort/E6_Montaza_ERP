<x-app-layout>
    <x-slot name="header">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('administration.index') }}"
                class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Administration') !!}</a>
                >> {{ __('Modèles de Mails') }}
            </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg p-4 ">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-2">
                    {{ __('Modèles de Mails') }}
                </h2>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-linear-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nom du Modèle
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Sujet
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="{{ request()->get('show_deleted') ? 'bg-gray-100 dark:bg-gray-900' : 'bg-white dark:bg-gray-800' }} divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($mailtemplates as $template)
                            <tr>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $template->nom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $template->sujet }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('mailtemplates.edit', $template->id) }}" class=""
                                        title="modifier">
                                        <x-icon size="2" type="edit" class="icons ml-2" />
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    {{ __('Signature') }}
                </h2>
                <div class="pl-4 flex flex-wrap gap-2">
                    @if(Storage::exists('signature/signature.png'))
                        <img src="data:image/png;base64,{{ $signature }}" alt="Signature" class="max-w-full h-auto">
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-300">{{ __('Aucune signature disponible.') }}</p>
                    @endif


                    <form method="POST" action="{{ route('mailtemplates.uploadSignature') }}" enctype="multipart/form-data">
                        @csrf
                        <label for="signature" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Télécharger une nouvelle signature') }}
                        </label>
                        <input type="file" name="signature" id="signature" class=" input-file" accept="png">
                        <button type="submit" class="mt-2 btn">
                            {{ __('enregistrer') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
