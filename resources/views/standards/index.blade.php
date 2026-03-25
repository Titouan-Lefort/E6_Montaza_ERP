<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('matieres.index') }}"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">Matière</a>
                    >>
                    {!! __('Standards') !!}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-300 leading-tight ml-1 mt-1">
                    {{ $versions_count }} standards
                </p>
            </div>
            <div class="flex gap-4 ">
                <button class="ml-auto btn" x-data x-on:click.prevent="$dispatch('open-modal','add-standard')">
                    {{ __('Ajouter un standard') }}
                </button>
                <button class="ml-auto btn" x-data x-on:click.prevent="$dispatch('open-modal','add-dossier')">
                    {{ __('Ajouter un dossier') }}
                </button>
            </div>

            <x-modal name="add-standard" focusable :show="count($errors) > 0 || isset($create) && $create">
                <div class="p-4">
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('standards.store') }}" class="p-4 flex flex-col gap-4"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="flex justify-between">
                            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                Ajouter un standard
                            </h1>
                            <a x-on:click="$dispatch('close')">
                                <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                            </a>
                        </div>
                        <x-input-label for="dossier" value="Dossier" class="w-1/4" />
                        <div class="flex w-full">
                            <select name="dossier" id="dossier" class="select-left w-full" required>
                                @foreach ($folders as $folder)
                                    <option value="{{ $folder->id }}"
                                        {{ old('dossier') == $folder->id ? 'selected' : '' }}>
                                        {{ $folder->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn-select-right" type="button"
                                x-on:click.prevent="$dispatch('open-modal','add-dossier')">
                                +
                            </button>
                        </div>
                        @error('dossier')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <x-input-label for="file" value="Fichier" class="w-1/4" />
                        <input type="file" name="file" id="file" accept=".pdf" required
                            class="input-file" />
                        @error('file')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <x-input-label for="version" value="Version" class="w-1/4" />
                        <select name="version" id="version" class="select-left" required>
                            @foreach (range('A', 'Z') as $letter)
                                <option value="{{ $letter }}" {{ old('version') == $letter ? 'selected' : '' }}>
                                    {{ $letter }}
                                </option>
                            @endforeach
                        </select>
                        @error('version')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click.prevent="$dispatch('close')">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <button class="ms-3 btn" type="submit">
                                {{ __('Ajouter') }}
                            </button>
                        </div>
                    </form>
                    <script>
                        document.getElementById('dossier').addEventListener('change', function() {
                            checkFileAndRemoveVersions();
                        });

                        document.getElementById('file').addEventListener('change', function() {
                            checkFileAndRemoveVersions();
                        });

                        function checkFileAndRemoveVersions() {
                            const dossierId = document.getElementById('dossier').value;
                            const fileInput = document.getElementById('file');
                            const file = fileInput.files[0];

                            if (dossierId && file) {
                                fetch(`/matieres/standards/${dossierId}/${file.name}/versions/json`)
                                    .then(response => response.json())
                                    .then(data => {
                                        const alphabet = [...'ABCDEFGHIJKLMNOPQRSTUVWXYZ'];
                                        const availableVersions = alphabet.filter(letter => !data.includes(letter));

                                        const versionSelect = document.getElementById('version');
                                        while (versionSelect.firstChild) {
                                            versionSelect.removeChild(versionSelect.firstChild);
                                        }
                                        availableVersions.forEach(version => {
                                            const option = document.createElement('option');
                                            option.value = version;
                                            option.textContent = version;
                                            versionSelect.appendChild(option);
                                        });
                                    });
                            }
                        }
                    </script>
                </div>
            </x-modal>
            <x-modal name="add-dossier" focusable :show="count($errors) > 0">
                <div class="p-4">
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('standards.store_dossier') }}" class=" flex flex-col gap-4 ">
                        @csrf
                        <div class="flex justify-between">
                            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                Ajouter un dossier
                            </h1>
                            <a x-on:click="$dispatch('close')">
                                <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                            </a>
                        </div>
                        <x-input-label for="nom" value="Nom du dossier" class="w-1/4" />
                        <x-text-input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                            class="input" />
                        @error('nom')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click.prevent="$dispatch('close')">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <button class="ms-3 btn" type="submit">
                                {{ __('Ajouter') }}
                            </button>
                        </div>
                    </form>
                </div>
            </x-modal>
        </div>


    </x-slot>

    <div class="py-8 text-gray-800 dark:text-gray-200 ">
        <div class="container mx-auto bg-white dark:bg-gray-800 p-4 h-100vh" id="standards-loading">
            <tr>
                <td colspan="100">
                    <div id="loading-spinner"
                        class=" mt-8 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-32 w-full">
                        <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32">
                        </div>
                    </div>
                    <style>
                        .loader {
                            border-top-color: #3498db;
                            animation: spinner 1.5s linear infinite;
                        }

                        @keyframes spinner {
                            0% {
                                transform: rotate(0deg);
                            }

                            100% {
                                transform: rotate(360deg);
                            }
                        }
                    </style>
            </tr>
            </td>

        </div>
        <div id="standards-container" class="container mx-auto bg-white dark:bg-gray-800 p-4 hidden" x-data="{ allOpen: false }">
            <div class="flex mb-2">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {!! __('Standards') !!}
                </h2>
                <button class="ml-auto btn"
                    @click="
                allOpen = !allOpen;
                $refs.folderList.querySelectorAll('[x-data]').forEach(el => {
                    Alpine.$data(el).open = allOpen
                })                 ">

                    <span x-text="allOpen ? 'Fermer tout' : 'Ouvrir tout'"></span>
                </button>
            </div>
            <ul class=" pl-5 " x-ref="folderList">

                @foreach ($folders as $folder)
                    <li x-data="{ open: false }" class="">
                        <div @click="open = !open"
                            class="cursor-pointer flex items-center hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm group">

                            <x-icons.folder show="!open" class="w-6 h-6 icons-no_hover" />
                            <x-icons.open-folder show="open" class="w-6 h-6 icons-no_hover" />
                            <strong class="text-lg ml-1"> {{ $folder->nom }}</strong>
                            <button class="ml-auto"
                                onclick="deleteDossier('{{ $folder->id }}','{{ $folder->nom }}')"
                                x-on:click.prevent="$dispatch('open-modal','delete-dossier')">
                                <x-icons.close
                                    class="w-8 h-8 dark:group-hover:fill-gray-200 group-hover:fill-gray-500 fill-white dark:fill-gray-800 " />
                            </button>

                        </div>
                        <ul x-show="open"
                            class="list-inside ml-5 mt-2 transition-all duration-300 ease-in-out overflow-hidden bg-gray-100 dark:bg-gray-900 rounded-sm">
                            @foreach ($folder->standards as $standard)
                                @foreach ($standard->versions as $version)
                                    <li
                                        class="text-gray-700 dark:text-gray-300 pl-8 flex border-l border-gray-500 dark:border-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-r group">
                                        <x-icons.pdf class="w-6 h-6" /><a href="{{ $version->path() }}"
                                            class="lien" target="_blank">
                                            {{ $version->standard->nom }} - {{ $version->version }}
                                        </a>
                                        <button class="ml-auto"
                                            onclick="deleteStandard('{{ $version->id }}','{{ $version->standard->nom }}')"
                                            x-on:click.prevent="$dispatch('open-modal','delete-standard')">
                                            <x-icons.close
                                                class="w-6 h-6 dark:fill-gray-900 mr-2 dark:group-hover:fill-gray-200 group-hover:fill-gray-500 fill-gray-100" />
                                        </button>
                                    </li>
                                @endforeach
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
            <x-modal name="delete-standard" focusable>
                <a x-on:click="$dispatch('close')">
                    <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                </a>
                <form method="post" action="{{ route('standards.destroy') }}" class="p-4">
                    @csrf
                    @method('DELETE')

                    <input type="hidden" name="id" id="id-delete-standard" value="0" />
                    <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        Supression</h1>
                    <h2 class="text-base font-normal text-gray-900 dark:text-gray-100">
                        Êtes-vous sûr de vouloir supprimer <strong class="underline" id="standard-name"></strong>
                        définitivement ?
                    </h2>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click.prevent="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="ms-3" type="submit">
                            {{ __('Delete') }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
            <x-modal name="delete-dossier" focusable>
                <a x-on:click="$dispatch('close')">
                    <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                </a>
                <form method="post" action="{{ route('standards.destroy_dossier') }}" class="p-4">
                    @csrf
                    @method('DELETE')

                    <input type="hidden" name="id" id="id-delete-dossier" value="0" />
                    <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        Supression</h1>
                    <h2 class="text-base font-normal text-gray-900 dark:text-gray-100">
                        Êtes-vous sûr de vouloir supprimer <strong class="underline" id="dossier-name"></strong>
                        définitivement ?
                    </h2>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click.prevent="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="ms-3" type="submit">
                            {{ __('Delete') }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
    <script>
        function deleteStandard(id, name) {
            document.getElementById('id-delete-standard').value = id;
            document.getElementById('standard-name').textContent = name;
        }

        function deleteDossier(id, name) {
            document.getElementById('id-delete-dossier').value = id;
            document.getElementById('dossier-name').textContent = name;
        }
        document.addEventListener('DOMContentLoaded', function() {
            const loadingDiv = document.getElementById('standards-loading');
            const containerDiv = document.getElementById('standards-container');
            if (loadingDiv) {
                loadingDiv.remove();
                containerDiv.classList.remove('hidden');
            }
        });
    </script>

</x-app-layout>
