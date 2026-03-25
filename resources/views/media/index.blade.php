<x-app-layout>
    @section('title', 'Gestion des Pièces jointes')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('administration.index') }}"
                class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">{!! __('Administration') !!}</a>
                >> {{ __('Gestion des Pièces jointes') }}
            </h2>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center">
                <form method="GET" action="{{ route('media.index') }}"
                    class="mr-4 mb-1 sm:mr-0 flex flex-col sm:flex-row items-start sm:items-center gap-2">

                    <x-select-custom name="type" id="type" class="" :selected="request('type')"
                        onchange="this.form.submit()">
                        <x-opt value="" selected>{{ __('Tous les types') }}</x-opt>
                        @foreach ($media_types as $media_type)
                            <x-opt value="{{ $media_type->id }}">
                                <div
                                    class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center
                                    {{ $media_type->background_color_light ? 'bg-[' . $media_type->background_color_light . ']' : 'bg-gray-100' }}
                                    {{ $media_type->text_color_light ? 'text-[' . $media_type->text_color_light . ']' : 'text-gray-800' }}
                                    {{ $media_type->background_color_dark ? 'dark:bg-[' . $media_type->background_color_dark . ']' : 'dark:bg-gray-700' }}
                                    {{ $media_type->text_color_dark ? 'dark:text-[' . $media_type->text_color_dark . ']' : 'dark:text-gray-200' }}">
                                    {{ $media_type->nom }}
                                </div>
                            </x-opt>
                        @endforeach
                    </x-select-custom>
                    <input type="text" name="search" placeholder="{{ __('Rechercher...') }}"
                        value="{{ request('search') }}"
                        oninput="debounceSubmit(this.form)"
                        class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    <div class="flex items-center ml-4 my-1">
                        <label for="nombre"
                            class="mr-2 text-gray-900 dark:text-gray-100">{{ __('Quantité') }}</label>
                        <input type="number" name="nombre" id="nombre"
                            value="{{ old('nombre', request('nombre', 50)) }}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 w-20 mr-2">
                    </div>
                    <button type="submit" class="mr-2 btn w-full sm:w-auto sm:mt-0 md:mt-0 lg:mt-0">
                        {{ __('Rechercher') }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Table des médias -->
                    <div class="overflow-x-auto">
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ __('Aperçu') }}</th>
                                    <th>{{ __('Nom du fichier') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Taille') }}</th>
                                    <th>{{ __('Ajouté par') }}</th>
                                    <th>{{ __('Date d\'ajout') }}</th>
                                    <th class="text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($groupedMedias as $modelType => $entities)
                                    <tr class="">
                                        <td colspan="8"
                                            class="   font-bold text-left text-gray-700 dark:text-gray-200 ">
                                            <div class="flex items-center justify-between mb-4 py-2 px-4 border-b border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700">


                                                {{ $modelType === 'App\\Models\\Ddp' ? __('Demandes de prix') : ($modelType === 'App\\Models\\Cde' ? __('Commandes') : \Str::afterLast($modelType, '\\') ?? __('Autre')) }}
                                                <button onclick="toggleModelTypeRows('{{ \Str::slug($modelType) }}')"
                                                    class="ml-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                                    <x-icons.chevron-down size="1.2"
                                                        class="model-type-{{ \Str::slug($modelType) }}-chevron icons" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach ($entities as $entityId => $group)
                                        @php
                                            $entity = $group->first()->mediaable ?? null;
                                            $entityLabel = $entity?->code ?? ($entity?->nom ?? $entityId);
                                            $entityType = $group->first()->mediaable_type ?? null;

                                            // Styles spécifiques pour ddp et cde
                                            $entityStyles = match ($entityType) {
                                                'App\Models\Cde'
                                                    => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800 border border-blue-200 dark:border-blue-700',
                                                'App\Models\Ddp'
                                                    => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200 hover:bg-emerald-200 dark:hover:bg-emerald-800 border border-emerald-200 dark:border-emerald-700',
                                                default
                                                    => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                            };
                                        @endphp
                                        <tr
                                            class="group-id-{{ $entityId }} model-type-{{ \Str::slug($modelType) }}">
                                            <td colspan="8"
                                                class="px-4 py-2 font-semibold text-left text-gray-600 dark:text-gray-300 border-b {{ $entityStyles }}">
                                                <div class="flex items-center justify-between">
                                                    <a
                                                        href="{{ route(strtolower(\Str::afterLast($modelType, '\\') . '.show'), $entityId) }}" target="_blank" class="flex items-center text-sm font-medium text-gray-900 dark:text-gray-100 hover:underline group">
                                                        @if ($entityLabel && $entityLabel != $entityId)
                                                            {{ $entityLabel }}
                                                            @else
                                                            {{ __('Aucun nom disponible') }}
                                                        @endif
                                                        <x-icons.open-in-new size="1.2"
                                                            class="ml-1 fill-none group-hover:fill-gray-700  dark:group-hover:fill-gray-200" />
                                                    </a>
                                                    <button onclick="toggleEntityRows('{{ $entityId }}')"
                                                        class="ml-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                                        <x-icons.chevron-down size="1.2"
                                                            class="group-id-{{ \Str::slug($entityId) }}-chevron icons" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach ($group as $media)
                                            <tr
                                                class="entity-{{ $entityId }} model-type-{{ \Str::slug($modelType) }} hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <!-- Aperçu du média -->
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex-shrink-0 h-16 w-16">
                                                        @php
                                                            $extension = strtolower(
                                                                pathinfo(
                                                                    $media->original_filename ?? $media->filename,
                                                                    PATHINFO_EXTENSION,
                                                                ),
                                                            );
                                                            $isImage = in_array($extension, [
                                                                'jpg',
                                                                'jpeg',
                                                                'png',
                                                                'gif',
                                                                'heic',
                                                                'heif',
                                                            ]);
                                                            $isVideo = in_array($extension, [
                                                                'mp4',
                                                                'mpeg',
                                                                'mov',
                                                                'avi',
                                                                'wmv',
                                                            ]);
                                                            $isAudio = $extension === 'mp3';

                                                            $colors = [
                                                                'pdf' => [
                                                                    'bg' =>
                                                                        'bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30',
                                                                    'text' => 'text-red-600 dark:text-red-400',
                                                                    'fill' => 'fill-red-600 dark:fill-red-400',
                                                                    'label' => 'text-red-700 dark:text-red-300',
                                                                ],
                                                                'doc' => [
                                                                    'bg' =>
                                                                        'bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30',
                                                                    'text' => 'text-blue-600 dark:text-blue-400',
                                                                    'fill' => 'fill-blue-600 dark:fill-blue-400',
                                                                    'label' => 'text-blue-700 dark:text-blue-300',
                                                                ],
                                                                'docx' => [
                                                                    'bg' =>
                                                                        'bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30',
                                                                    'text' => 'text-blue-600 dark:text-blue-400',
                                                                    'fill' => 'fill-blue-600 dark:fill-blue-400',
                                                                    'label' => 'text-blue-700 dark:text-blue-300',
                                                                ],
                                                                'xls' => [
                                                                    'bg' =>
                                                                        'bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30',
                                                                    'text' => 'text-green-600 dark:text-green-400',
                                                                    'fill' => 'fill-green-600 dark:fill-green-400',
                                                                    'label' => 'text-green-700 dark:text-green-300',
                                                                ],
                                                                'xlsx' => [
                                                                    'bg' =>
                                                                        'bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30',
                                                                    'text' => 'text-green-600 dark:text-green-400',
                                                                    'fill' => 'fill-green-600 dark:fill-green-400',
                                                                    'label' => 'text-green-700 dark:text-green-300',
                                                                ],
                                                                'csv' => [
                                                                    'bg' =>
                                                                        'bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30',
                                                                    'text' => 'text-green-600 dark:text-green-400',
                                                                    'fill' => 'fill-green-600 dark:fill-green-400',
                                                                    'label' => 'text-green-700 dark:text-green-300',
                                                                ],
                                                                'mp3' => [
                                                                    'bg' =>
                                                                        'bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30',
                                                                    'text' => 'text-purple-600 dark:text-purple-400',
                                                                    'fill' => 'fill-purple-600 dark:fill-purple-400',
                                                                    'label' => 'text-purple-700 dark:text-purple-300',
                                                                ],
                                                                'mp4' => [
                                                                    'bg' =>
                                                                        'bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30',
                                                                    'text' => 'text-indigo-600 dark:text-indigo-400',
                                                                    'fill' => 'fill-indigo-600 dark:fill-indigo-400',
                                                                    'label' => 'text-indigo-700 dark:text-indigo-300',
                                                                ],
                                                                'mpeg' => [
                                                                    'bg' =>
                                                                        'bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30',
                                                                    'text' => 'text-indigo-600 dark:text-indigo-400',
                                                                    'fill' => 'fill-indigo-600 dark:fill-indigo-400',
                                                                    'label' => 'text-indigo-700 dark:text-indigo-300',
                                                                ],
                                                                'mov' => [
                                                                    'bg' =>
                                                                        'bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30',
                                                                    'text' => 'text-indigo-600 dark:text-indigo-400',
                                                                    'fill' => 'fill-indigo-600 dark:fill-indigo-400',
                                                                    'label' => 'text-indigo-700 dark:text-indigo-300',
                                                                ],
                                                                'avi' => [
                                                                    'bg' =>
                                                                        'bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30',
                                                                    'text' => 'text-indigo-600 dark:text-indigo-400',
                                                                    'fill' => 'fill-indigo-600 dark:fill-indigo-400',
                                                                    'label' => 'text-indigo-700 dark:text-indigo-300',
                                                                ],
                                                                'wmv' => [
                                                                    'bg' =>
                                                                        'bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30',
                                                                    'text' => 'text-indigo-600 dark:text-indigo-400',
                                                                    'fill' => 'fill-indigo-600 dark:fill-indigo-400',
                                                                    'label' => 'text-indigo-700 dark:text-indigo-300',
                                                                ],
                                                                'txt' => [
                                                                    'bg' =>
                                                                        'bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700',
                                                                    'text' => 'text-gray-600 dark:text-gray-400',
                                                                    'fill' => 'fill-gray-600 dark:fill-gray-400',
                                                                    'label' => 'text-gray-700 dark:text-gray-300',
                                                                ],
                                                                'jpg' => [
                                                                    'bg' =>
                                                                        'bg-pink-50 dark:bg-pink-900/20 hover:bg-pink-100 dark:hover:bg-pink-900/30',
                                                                    'text' => 'text-pink-600 dark:text-pink-400',
                                                                    'fill' => 'fill-pink-600 dark:fill-pink-400',
                                                                    'label' => 'text-pink-700 dark:text-pink-300',
                                                                ],
                                                                'jpeg' => [
                                                                    'bg' =>
                                                                        'bg-pink-50 dark:bg-pink-900/20 hover:bg-pink-100 dark:hover:bg-pink-900/30',
                                                                    'text' => 'text-pink-600 dark:text-pink-400',
                                                                    'fill' => 'fill-pink-600 dark:fill-pink-400',
                                                                    'label' => 'text-pink-700 dark:text-pink-300',
                                                                ],
                                                                'png' => [
                                                                    'bg' =>
                                                                        'bg-cyan-50 dark:bg-cyan-900/20 hover:bg-cyan-100 dark:hover:bg-cyan-900/30',
                                                                    'text' => 'text-cyan-600 dark:text-cyan-400',
                                                                    'fill' => 'fill-cyan-600 dark:fill-cyan-400',
                                                                    'label' => 'text-cyan-700 dark:text-cyan-300',
                                                                ],
                                                                'gif' => [
                                                                    'bg' =>
                                                                        'bg-orange-50 dark:bg-orange-900/20 hover:bg-orange-100 dark:hover:bg-orange-900/30',
                                                                    'text' => 'text-orange-600 dark:text-orange-400',
                                                                    'fill' => 'fill-orange-600 dark:fill-orange-400',
                                                                    'label' => 'text-orange-700 dark:text-orange-300',
                                                                ],
                                                                'heic' => [
                                                                    'bg' =>
                                                                        'bg-teal-50 dark:bg-teal-900/20 hover:bg-teal-100 dark:hover:bg-teal-900/30',
                                                                    'text' => 'text-teal-600 dark:text-teal-400',
                                                                    'fill' => 'fill-teal-600 dark:fill-teal-400',
                                                                    'label' => 'text-teal-700 dark:text-teal-300',
                                                                ],
                                                                'heif' => [
                                                                    'bg' =>
                                                                        'bg-teal-50 dark:bg-teal-900/20 hover:bg-teal-100 dark:hover:bg-teal-900/30',
                                                                    'text' => 'text-teal-600 dark:text-teal-400',
                                                                    'fill' => 'fill-teal-600 dark:fill-teal-400',
                                                                    'label' => 'text-teal-700 dark:text-teal-300',
                                                                ],
                                                                'default' => [
                                                                    'bg' =>
                                                                        'bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/30',
                                                                    'text' => 'text-yellow-600 dark:text-yellow-400',
                                                                    'fill' => 'fill-yellow-600 dark:fill-yellow-400',
                                                                    'label' => 'text-yellow-700 dark:text-yellow-300',
                                                                ],
                                                            ];

                                                            $color = $colors[$extension] ?? $colors['default'];

                                                            // Mapping des icônes par extension
                                                            $iconMap = [
                                                                'pdf' => 'pdf',
                                                                'doc' => 'doc',
                                                                'docx' => 'docx',
                                                                'xls' => 'xls',
                                                                'xlsx' => 'xlsx',
                                                                'csv' => 'csv',
                                                                'mp3' => 'mp3',
                                                                'mp4' => 'mp4',
                                                                'mpeg' => 'mpeg',
                                                                'mov' => 'mov',
                                                                'avi' => 'avi',
                                                                'wmv' => 'wmv',
                                                                'txt' => 'txt',
                                                                'jpg' => 'jpg',
                                                                'jpeg' => 'jpeg',
                                                                'png' => 'png',
                                                                'gif' => 'gif',
                                                                'heic' => 'heic',
                                                                'heif' => 'heif',
                                                            ];

                                                            $iconName = $iconMap[$extension] ?? 'attachement';
                                                        @endphp

                                                        @if ($isImage)
                                                            <div class="relative">
                                                                <a href="{{ route('media.show', $media->id) }}"
                                                                    target="_blank" class="block w-16 h-16">
                                                                    <img src="{{ route('media.show', $media->id) }}"
                                                                        alt="{{ $media->original_filename ?? $media->filename }}"
                                                                        class="w-full h-full object-cover object-center rounded">
                                                                </a>
                                                                <div
                                                                    class="absolute -top-1 -right-1 {{ $color['bg'] }} rounded px-1 py-0.5">
                                                                    <span
                                                                        class="text-xs font-bold {{ $color['label'] }}">{{ strtoupper($extension) }}</span>
                                                                </div>
                                                            </div>
                                                        @elseif ($isVideo)
                                                            <div class="relative">
                                                                <a href="{{ route('media.show', $media->id) }}"
                                                                    target="_blank"
                                                                    class="block {{ $color['bg'] }} w-16 h-16 flex items-center justify-center transition-colors rounded">
                                                                    <div class="text-center">
                                                                        <x-dynamic-component :component="'icons.' . $iconName"
                                                                            class="w-8 h-8 {{ $color['text'] }} {{ $color['fill'] }} mx-auto" />
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        @elseif ($isAudio)
                                                            <a href="{{ route('media.show', $media->id) }}"
                                                                target="_blank"
                                                                class="block {{ $color['bg'] }} w-16 h-16 flex items-center justify-center transition-colors rounded relative">
                                                                <div class="text-center">
                                                                    <x-dynamic-component :component="'icons.' . $iconName"
                                                                        class="w-8 h-8 {{ $color['text'] }} {{ $color['fill'] }} mx-auto" />
                                                                </div>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('media.show', $media->id) }}"
                                                                target="_blank"
                                                                class="block {{ $color['bg'] }} w-16 h-16 flex items-center justify-center transition-colors rounded relative">
                                                                <div class="text-center">
                                                                    <x-dynamic-component :component="'icons.' . $iconName"
                                                                        class="w-8 h-8 {{ $color['text'] }} {{ $color['fill'] }} mx-auto" />
                                                                </div>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>

                                                <!-- Nom du fichier -->
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $media->original_filename ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $media->filename ?? 'N/A' }}
                                                    </div>
                                                    <div class="max-h-42 overflow-y-auto">
                                                        @include('media.commentaire', ['media' => $media])
                                                    </div>
                                                </td>
                                                <!-- Type de média -->
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    <div
                                                        class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center
                                                        {{ $media->mediaType?->background_color_light ? 'bg-[' . $media->mediaType->background_color_light . ']' : 'bg-gray-100' }}
                                                        {{ $media->mediaType?->text_color_light ? 'text-[' . $media->mediaType->text_color_light . ']' : 'text-gray-800' }}
                                                        {{ $media->mediaType?->background_color_dark ? 'dark:bg-[' . $media->mediaType->background_color_dark . ']' : 'dark:bg-gray-700' }}
                                                        {{ $media->mediaType?->text_color_dark ? 'dark:text-[' . $media->mediaType->text_color_dark . ']' : 'dark:text-gray-200' }}">
                                                        {{ $media->mediaType->nom ?? 'N/A' }}
                                                    </div>
                                                </td>

                                                <!-- Taille du fichier -->
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $media->size ? formatNumberBytes($media->size) : 'N/A' }}

                                                </td>
                                                <!-- Ajouté par -->
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $media->user?->first_name ?? 'N/A' }}
                                                    {{ $media->user?->last_name ?? '' }}
                                                </td>
                                                <!-- Date d'ajout -->
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $media->created_at?->format('d/m/Y H:i') ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex justify-end space-x-2">

                                                        <a href="#"
                                                            onclick="openEditModal({{ $media->id }}, '{{ addslashes($media->original_filename) }}', {{ $media->media_type_id ?? 'null' }})"
                                                            class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                                            title="Modifier">
                                                            <x-icons.edit size="1.2" />
                                                        </a>
                                                        <x-boutons.supprimer modalTitle="Supprimer la pièce jointe"
                                                            confirmButtonText="Confirmer la suppression"
                                                            cancelButtonText="Annuler"
                                                            formAction="{{ route('media.destroy', $media->id) }}"
                                                            modalName="delete-media-modal-{{ $media->id }}"
                                                            errorName="delete-media-{{ $media->id }}"
                                                            userInfo="Êtes-vous sûr de vouloir supprimer cette pièce jointe ? Cette action est irréversible.">
                                                            <x-slot:customButton>
                                                                <button type="button"
                                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                                    title="Supprimer">
                                                                    <x-icons.delete size="1.2" />
                                                                </button>
                                                            </x-slot:customButton>
                                                        </x-boutons.supprimer>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="8"
                                            class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            {{ __('Aucun pièce jointe trouvé.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if (isset($medias) && method_exists($medias, 'links'))
                        <div class="mt-6">
                            {{ $medias->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <!-- Modal d'édition -->
    <x-modal name="edit-media" maxWidth="md">
        <div class="p-4">
            <a x-on:click="$dispatch('close')">
                <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
            </a>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                {{ __('Modifier la pièce jointe') }}
            </h2>

            <form id="edit-media-form" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <x-input-label for="edit_original_filename" :value="__('Nom du fichier')" />
                    <x-text-input id="edit_original_filename" name="original_filename" type="text"
                        class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('original_filename')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="edit_media_type_id" :value="__('Type de média')" />
                    <x-select-custom name="media_type_id" id="edit_media_type_id" class="mt-1 block w-full">
                        <x-opt value="">{{ __('Sélectionner un type') }}</x-opt>
                        @foreach ($media_types as $media_type)
                            <x-opt value="{{ $media_type->id }}">
                                <div
                                    class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center
                                    {{ $media_type->background_color_light ? 'bg-[' . $media_type->background_color_light . ']' : 'bg-gray-100' }}
                                    {{ $media_type->text_color_light ? 'text-[' . $media_type->text_color_light . ']' : 'text-gray-800' }}
                                    {{ $media_type->background_color_dark ? 'dark:bg-[' . $media_type->background_color_dark . ']' : 'dark:bg-gray-700' }}
                                    {{ $media_type->text_color_dark ? 'dark:text-[' . $media_type->text_color_dark . ']' : 'dark:text-gray-200' }}">
                                    {{ $media_type->nom }}
                                </div>
                            </x-opt>
                        @endforeach
                    </x-select-custom>
                    <x-input-error :messages="$errors->get('media_type_id')" class="mt-2" />
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'edit-media')">
                        {{ __('Annuler') }}
                    </x-secondary-button>
                    <x-primary-button type="submit">
                        {{ __('Mettre à jour') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

    <script>
        function openEditModal(mediaId, originalFilename, mediaTypeId) {
            document.getElementById('edit_original_filename').value = originalFilename;
            document.getElementById('edit_media_type_id').value = mediaTypeId || '';
            document.getElementById('edit-media-form').action = '/media/' + mediaId;
            window.dispatchEvent(new CustomEvent('open-modal', {
                detail: 'edit-media'
            }));
        }

        function toggleEntityRows(entityId) {
            const rows = document.querySelectorAll(`.entity-${entityId}`);
            const parentGroup = document.querySelector(`.group-id-${entityId}`);
            const isParentHidden = rows[0]?.classList.contains('hidden');
            rows.forEach(row => {
                if (isParentHidden) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
            const chevron = document.querySelector(`.group-id-${entityId}-chevron`);
            if (chevron) {
                chevron.classList.toggle('rotate-180', !isParentHidden);
            }
        }

        function toggleModelTypeRows(modelTypeSlug) {
            const rows = document.querySelectorAll(`.model-type-${modelTypeSlug}`);
            const isParentHidden = rows[0]?.classList.contains('hidden');
            rows.forEach(row => {
                if (isParentHidden) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
            const chevron = document.querySelector(`.model-type-${modelTypeSlug}-chevron`);
            if (chevron) {
                chevron.classList.toggle('rotate-180', !isParentHidden);
            }
        }

        let timeout = null;
        function debounceSubmit(form) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                form.submit();
            }, 500);
        }
    </script>
</x-app-layout>
