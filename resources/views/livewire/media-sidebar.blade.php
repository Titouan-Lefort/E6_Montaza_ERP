@php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Media;
@endphp

<div>
    <div class="fixed top-1/2 left-0 transform -translate-y-1/2" x-data>
        <button @click="$dispatch('open-volet', 'media-manager')"
            onclick="window.refreshTextareas();delayedRefreshTextareas()"
            class="btn-select-right flex items-center px-2 py-8 bg-gray-200 dark:bg-gray-800 shadow-lg hover:bg-gray-300 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-700">
            <span class=" whitespace-nowrap font-medium transform rotate-90 inline-block w-1  -mt-6 mb-20">Pièces
                jointes</span>
            <x-icon :size="1" type="arrow_back" class="-rotate-180" />
        </button>

    </div>

    <!-- Volet modal pour la gestion complète des médias -->
    <x-volet-modal name="media-manager" maxWidth="3xl" position="left">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-2">
                    <x-icons.attachement class="w-8 h-8 text-gray-600 dark:text-gray-300" />
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Gestion des Pièces jointes</h2>
                </div>
                <button @click="$dispatch('close-volet', 'media-manager');" class="text-gray-400 hover:text-gray-500"
                    onclick="window.delayedRefreshTextareas()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Onglets -->
            <div x-data="{ tab: 'files' }" class="mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <nav class="flex space-x-4">
                        <button @click="tab = 'files'; $wire.refreshMediaList();"
                            onclick="window.delayedRefreshTextareas(1000);window.delayedRefreshTextareas(2000);window.delayedRefreshTextareas(3000);window.delayedRefreshTextareas(4000);"
                            :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': tab === 'files', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': tab !== 'files' }"
                            class="pb-3 px-1 border-b-2 font-medium text-sm">
                            Fichiers
                        </button>
                        <button @click="tab = 'upload';"
                            :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': tab === 'upload', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': tab !== 'upload' }"
                            class="pb-3 px-1 border-b-2 font-medium text-sm">
                            Ajouter des fichiers
                        </button>
                        <button @click="tab = 'qrcode';"
                            :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': tab === 'qrcode', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': tab !== 'qrcode' }"
                            class="pb-3 px-1 border-b-2 font-medium text-sm">
                            QR Code
                        </button>
                    </nav>
                    <button wire:click="refreshMediaList"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                        title="Recharger les médias">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Contenu des onglets -->
                <div x-show="tab === 'files'" class="mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if (count($mediaList ?? []) > 0)

                            @foreach ($mediaList as $media)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700">
                                        <div class="flex items-center space-x-2">
                                            @php
                                                $extension = strtolower(pathinfo($media->original_filename ?? $media->filename, PATHINFO_EXTENSION));
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
                                                <x-dynamic-component :component="'icons.' . $iconName" class="w-5 h-5 icons" />
                                            <span class="font-medium truncate text-gray-700 dark:text-gray-200"
                                                title="{{ $media->original_filename ?? $media->filename }}">
                                                {{ Str::limit($media->original_filename ?? $media->filename, 25) }}
                                            </span>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('media.download', $media->id) }}" target="_blank"
                                                class="text-blue-500 hover:text-blue-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                    </path>
                                                </svg>
                                            </a>
                                            <button wire:click="deleteMedia({{ $media->id ?? 0 }})"
                                                class="text-red-500 hover:text-red-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Aperçu du fichier -->
                                    <div class="p-3">
                                        @php
                                            $extension = strtolower(pathinfo($media->original_filename ?? $media->filename, PATHINFO_EXTENSION));
                                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'heic', 'heif']);
                                            $isVideo = in_array($extension, ['mp4', 'mpeg', 'mov', 'avi', 'wmv']);
                                            $isPdf = $extension === 'pdf';
                                            $isDocument = in_array($extension, ['doc', 'docx']);
                                            $isSpreadsheet = in_array($extension, ['xls', 'xlsx', 'csv']);
                                            $isAudio = $extension === 'mp3';
                                            $isText = $extension === 'txt';

                                            // Couleurs par type de fichier
                                            $colors = [
                                                'pdf' => ['bg' => 'bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30', 'text' => 'text-red-600 dark:text-red-400', 'fill' => 'fill-red-600 dark:fill-red-400', 'label' => 'text-red-700 dark:text-red-300'],
                                                'doc' => ['bg' => 'bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30', 'text' => 'text-blue-600 dark:text-blue-400', 'fill' => 'fill-blue-600 dark:fill-blue-400', 'label' => 'text-blue-700 dark:text-blue-300'],
                                                'docx' => ['bg' => 'bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30', 'text' => 'text-blue-600 dark:text-blue-400', 'fill' => 'fill-blue-600 dark:fill-blue-400', 'label' => 'text-blue-700 dark:text-blue-300'],
                                                'xls' => ['bg' => 'bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30', 'text' => 'text-green-600 dark:text-green-400', 'fill' => 'fill-green-600 dark:fill-green-400', 'label' => 'text-green-700 dark:text-green-300'],
                                                'xlsx' => ['bg' => 'bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30', 'text' => 'text-green-600 dark:text-green-400', 'fill' => 'fill-green-600 dark:fill-green-400', 'label' => 'text-green-700 dark:text-green-300'],
                                                'csv' => ['bg' => 'bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30', 'text' => 'text-green-600 dark:text-green-400', 'fill' => 'fill-green-600 dark:fill-green-400', 'label' => 'text-green-700 dark:text-green-300'],
                                                'mp3' => ['bg' => 'bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30', 'text' => 'text-purple-600 dark:text-purple-400', 'fill' => 'fill-purple-600 dark:fill-purple-400', 'label' => 'text-purple-700 dark:text-purple-300'],
                                                'mp4' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30', 'text' => 'text-indigo-600 dark:text-indigo-400', 'fill' => 'fill-indigo-600 dark:fill-indigo-400', 'label' => 'text-indigo-700 dark:text-indigo-300'],
                                                'mpeg' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30', 'text' => 'text-indigo-600 dark:text-indigo-400', 'fill' => 'fill-indigo-600 dark:fill-indigo-400', 'label' => 'text-indigo-700 dark:text-indigo-300'],
                                                'mov' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30', 'text' => 'text-indigo-600 dark:text-indigo-400', 'fill' => 'fill-indigo-600 dark:fill-indigo-400', 'label' => 'text-indigo-700 dark:text-indigo-300'],
                                                'avi' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30', 'text' => 'text-indigo-600 dark:text-indigo-400', 'fill' => 'fill-indigo-600 dark:fill-indigo-400', 'label' => 'text-indigo-700 dark:text-indigo-300'],
                                                'wmv' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30', 'text' => 'text-indigo-600 dark:text-indigo-400', 'fill' => 'fill-indigo-600 dark:fill-indigo-400', 'label' => 'text-indigo-700 dark:text-indigo-300'],
                                                'txt' => ['bg' => 'bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700', 'text' => 'text-gray-600 dark:text-gray-400', 'fill' => 'fill-gray-600 dark:fill-gray-400', 'label' => 'text-gray-700 dark:text-gray-300'],
                                                'jpg' => ['bg' => 'bg-pink-50 dark:bg-pink-900/20 hover:bg-pink-100 dark:hover:bg-pink-900/30', 'text' => 'text-pink-600 dark:text-pink-400', 'fill' => 'fill-pink-600 dark:fill-pink-400', 'label' => 'text-pink-700 dark:text-pink-300'],
                                                'jpeg' => ['bg' => 'bg-pink-50 dark:bg-pink-900/20 hover:bg-pink-100 dark:hover:bg-pink-900/30', 'text' => 'text-pink-600 dark:text-pink-400', 'fill' => 'fill-pink-600 dark:fill-pink-400', 'label' => 'text-pink-700 dark:text-pink-300'],
                                                'png' => ['bg' => 'bg-cyan-50 dark:bg-cyan-900/20 hover:bg-cyan-100 dark:hover:bg-cyan-900/30', 'text' => 'text-cyan-600 dark:text-cyan-400', 'fill' => 'fill-cyan-600 dark:fill-cyan-400', 'label' => 'text-cyan-700 dark:text-cyan-300'],
                                                'gif' => ['bg' => 'bg-orange-50 dark:bg-orange-900/20 hover:bg-orange-100 dark:hover:bg-orange-900/30', 'text' => 'text-orange-600 dark:text-orange-400', 'fill' => 'fill-orange-600 dark:fill-orange-400', 'label' => 'text-orange-700 dark:text-orange-300'],
                                                'heic' => ['bg' => 'bg-teal-50 dark:bg-teal-900/20 hover:bg-teal-100 dark:hover:bg-teal-900/30', 'text' => 'text-teal-600 dark:text-teal-400', 'fill' => 'fill-teal-600 dark:fill-teal-400', 'label' => 'text-teal-700 dark:text-teal-300'],
                                                'heif' => ['bg' => 'bg-teal-50 dark:bg-teal-900/20 hover:bg-teal-100 dark:hover:bg-teal-900/30', 'text' => 'text-teal-600 dark:text-teal-400', 'fill' => 'fill-teal-600 dark:fill-teal-400', 'label' => 'text-teal-700 dark:text-teal-300'],
                                                'default' => ['bg' => 'bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/30', 'text' => 'text-yellow-600 dark:text-yellow-400', 'fill' => 'fill-yellow-600 dark:fill-yellow-400', 'label' => 'text-yellow-700 dark:text-yellow-300']
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
                                                'mp3' => 'mp3', // Pas d'icône mp3 spécifique, on utilise attachement
                                                'mp4' => 'mp4', // Pas d'icône mp4 spécifique
                                                'mpeg' => 'mpeg',
                                                'mov' => 'mov',
                                                'avi' => 'avi',
                                                'wmv' => 'wmv',
                                                'txt' => 'txt',
                                                'jpg' => 'jpg', // Pas d'icône jpg spécifique
                                                'jpeg' => 'jpeg',
                                                'png' => 'png',
                                                'gif' => 'gif',
                                                'heic' => 'heic',
                                                'heif' => 'heif'
                                            ];

                                            $iconName = $iconMap[$extension] ?? 'attachement';
                                        @endphp

                                        @if ($isImage)
                                            <div class="relative">
                                                <a href="{{ route('media.show', $media->id) }}" target="_blank"
                                                    class="block">
                                                    <img src="{{ route('media.show', $media->id) }}"
                                                        alt="{{ $media->original_filename ?? $media->filename }}"
                                                        class="w-full h-32 object-cover object-center rounded">
                                                </a>
                                                <div class="absolute top-2 right-2 {{ $color['bg'] }} rounded px-2 py-1">
                                                    <span class="text-xs font-bold {{ $color['label'] }}">{{ strtoupper($extension) }}</span>
                                                </div>
                                            </div>
                                        @elseif ($isVideo)
                                            <div class="relative">
                                                <video controls class="w-full h-32 object-cover object-center rounded">
                                                    <source src="{{ route('media.show', $media->id) }}" type="video/{{ $extension }}">
                                                    Votre navigateur ne supporte pas la balise vidéo.
                                                </video>
                                                <div class="absolute top-2 right-2 {{ $color['bg'] }} rounded px-2 py-1">
                                                    <span class="text-xs font-bold {{ $color['label'] }}">{{ strtoupper($extension) }}</span>
                                                </div>
                                            </div>
                                        @elseif ($isAudio)
                                            <div class="relative">
                                                <div class=" {{ $color['bg'] }} rounded px-2 py-1 w-fit float-right">
                                                    <span class="text-xs font-bold {{ $color['label'] }}">{{ strtoupper($extension) }}</span>
                                                </div>
                                                <audio controls class="w-full">
                                                    <source src="{{ route('media.show', $media->id) }}" type="audio/{{ $extension }}">
                                                    Votre navigateur ne supporte pas l'élément audio.
                                                </audio>

                                            </div>
                                        @else
                                            <a href="{{ route('media.show', $media->id) }}" target="_blank"
                                                class="block {{ $color['bg'] }} h-32 flex items-center justify-center transition-colors rounded">
                                                <div class="text-center">
                                                    <x-icons.{{ $iconName }} class="w-16 h-16 {{ $color['text'] }} {{ $color['fill'] }} mx-auto" />
                                                    <p class="text-sm mt-1 {{ $color['label'] }}">{{ strtoupper($extension) }}</p>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                    <!-- Informations sur le fichier -->
                                    <div class="px-3 pb-3">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            <p>Ajouté par: {{ $media->user->first_name }}
                                                {{ $media->user->last_name }}</p>
                                            <p>Date:
                                                {{ $media->created_at ? $media->created_at->format('d/m/Y H:i') : 'N/A' }}
                                            </p>
                                            <p>Taille:
                                                {{ $media->size ? formatNumberBytes($media->size) : 'N/A' }}
                                            </p>
                                            <x-select-custom id="media_type_{{ $media->id }}"
                                                name="mediaTypes.{{ $media->id }}"
                                                selected="{{ $media->media_type_id }}"
                                                wire:model="mediaTypes.{{ $media->id }}" class="mt-2"
                                                onchange="updateTypeMedia({{ $media->id }})">
                                                @foreach ($mediaTypes as $media_type)
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
                                            @once
                                                <script>
                                                    function updateTypeMedia(id) {
                                                        const mediaId = id; // Récupère l'ID de la société
                                                        const value = document.getElementsByName('mediaTypes.' + mediaId)[0].value; // Récupère la valeur sélectionnée
                                                        console.log('Mise à jour du type de média pour l\'ID:', mediaId, 'avec la valeur:', value);
                                                        // Envoie la requête AJAX avec fetch
                                                        fetch('/media/' + mediaId + '/type/save', {
                                                                method: 'PATCH', // Utilise la méthode PATCH pour mettre à jour
                                                                headers: {
                                                                    'Content-Type': 'application/json',
                                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Envoie le token CSRF pour la sécurité
                                                                },
                                                                body: JSON.stringify({
                                                                    media_type_id: value, // Envoie l'ID du type de média
                                                                }),
                                                            })
                                                            .then(response => response.json()) // Récupère la réponse en JSON
                                                            .then(data => {
                                                                if (!(data.message == 'type inchangé')) {
                                                                    showFlashMessageFromJs(data.message, 2000);
                                                                }
                                                            })
                                                            .catch(error => {
                                                                console.error('Erreur lors de la mise à jour du type', error);
                                                                showFlashMessageFromJs('Erreur lors de la mise à jour du type', 2000, 'error');
                                                            });
                                                    }
                                                </script>
                                            @endonce
                                            @include('../media.commentaire', ['media' => $media])
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-2 text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="mt-2 text-gray-500 dark:text-gray-400">Aucun document associé à cette
                                    commande</p>
                            </div>
                        @endif
                    </div>
                    <!-- PDF DE CDE -->
                    @if (isset($model) && $model == 'cde' && $modelId)
                        @php
                            $cde = App\Models\Cde::find($modelId);
                            $cdeannee = explode('-', $cde->code)[1];
                            $pdf = $cde->code . '.pdf';
                        @endphp
                        <div
                            class="flex justify-between items-center border-b border-gray-300 dark:border-gray-700 mt-6 mb-4 text-gray-700 dark:text-gray-200">
                            <h1 class="text-3xl font-bold mb-6 text-left">PDF de la commande</h1>
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('cde.pdfs.download', $cde->id) }}" class="btn-sm">Télécharger le
                                    PDF</a>
                                <a href="{{ route('cde.pdfs.pdfdownload_sans_prix', $cde->id) }}"
                                    class="btn-sm">Télécharger le PDF sans prix</a>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4">

                            <div class="flex flex-col gap-2 bg-gray-100 dark:bg-gray-700 p-4 rounded-md hover:scale-105 cursor-pointer transition-all relative"
                                id="pdf-{{ $pdf }}" title="Ouvrir le PDF">
                                <h2
                                    class="text-xl font-semibold text-gray-700 dark:text-gray-200  border border-gray-300 dark:border-gray-700 pb-2 hover">
                                    {{ explode('_', $pdf)[count(explode('_', $pdf)) - 1] }}</h2>
                                <div style="background-color: rgba(0,0,0,0); height: 409px; width: 285px; margin-bottom: 15px;"
                                    class="absolute bottom-4"></div>
                                <object
                                    data="{{ route('cde.pdfshow', ['cde' => $cde, 'annee' => $cdeannee, 'nom' => $pdf]) }}"
                                    type="application/pdf" height="424px" width="300px">
                                    <p>Il semble que vous n'ayez pas de plugin PDF pour ce navigateur. Pas de
                                        problème...
                                        vous
                                        pouvez <a
                                            href="{{ route('cde.pdfshow', ['cde' => $cde, 'annee' => $cdeannee, 'nom' => $pdf]) }}">cliquer
                                            ici pour télécharger le fichier PDF.</a></p>
                                </object>
                            </div>

                        </div>
                        <script>
                            document.querySelectorAll('[id^="pdf-"]').forEach(function(element) {
                                element.addEventListener('click', function() {
                                    const pdfUrl = element.querySelector('object').data;
                                    window.open(pdfUrl, '_blank');
                                });
                            });
                        </script>
                    @endif
                    <!-- PDF DE DDP -->
                    @if (isset($model) && $model == 'ddp' && $modelId)
                        @php
                            $ddp = App\Models\Ddp::find($modelId);
                            $ddpannee = explode('-', $ddp->code)[1];
                            $pdfs = Storage::files('DDP/' . $ddpannee);
                            $pdfs = array_filter($pdfs, function ($file) use ($ddp) {
                                return strpos(basename($file), $ddp->code) === 0;
                            });
                            $pdfs = array_map(function ($file) use ($ddpannee) {
                                return str_replace('DDP/' . $ddpannee . '/', '', $file);
                            }, $pdfs);

                        @endphp
                        <div
                            class="flex justify-between items-center border-b border-gray-300 dark:border-gray-700 mt-6 mb-4 text-gray-700 dark:text-gray-200">
                            <h1 class="text-3xl font-bold mb-6 text-left ">PDF de la Demande de prix</h1>
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('ddp.pdfs.download', $ddp->id) }}" class="btn-sm">Télécharger tous
                                    les PDF</a>

                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4">

                            @foreach ($pdfs as $pdf)
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
                                    <object
                                        data="{{ route('ddp.pdfshow', ['ddp' => $ddp, 'annee' => $ddpannee, 'nom' => $pdf]) }}"
                                        type="application/pdf" height="424px" width="300px">
                                        <p>Il semble que vous n'ayez pas de plugin PDF pour ce navigateur. Pas de
                                            problème... vous
                                            pouvez <a
                                                href="{{ route('ddp.pdfshow', ['ddp' => $ddp, 'annee' => $ddpannee, 'nom' => $pdf]) }}">cliquer
                                                ici pour télécharger le fichier PDF.</a></p>
                                    </object>
                                </div>
                            @endforeach
                        </div>
                        <script>
                            document.querySelectorAll('[id^="pdf-"]').forEach(function(element) {
                                element.addEventListener('click', function() {
                                    const pdfUrl = element.querySelector('object').data;
                                    window.open(pdfUrl, '_blank');
                                });
                            });
                        </script>
                    @endif
                </div>
{{--
########  #######  ########  ##     ## ##     ## ##          ###    #### ########  ########
##       ##     ## ##     ## ###   ### ##     ## ##         ## ##    ##  ##     ## ##
##       ##     ## ##     ## #### #### ##     ## ##        ##   ##   ##  ##     ## ##
######   ##     ## ########  ## ### ## ##     ## ##       ##     ##  ##  ########  ######
##       ##     ## ##   ##   ##     ## ##     ## ##       #########  ##  ##   ##   ##
##       ##     ## ##    ##  ##     ## ##     ## ##       ##     ##  ##  ##    ##  ##
##        #######  ##     ## ##     ##  #######  ######## ##     ## #### ##     ## ########


########    ##    ##     ## ########  ##        #######     ###    ########
##     ##   ##    ##     ## ##     ## ##       ##     ##   ## ##   ##     ##
##     ##         ##     ## ##     ## ##       ##     ##  ##   ##  ##     ##
##     ##         ##     ## ########  ##       ##     ## ##     ## ##     ##
##     ##         ##     ## ##        ##       ##     ## ######### ##     ##
##     ##         ##     ## ##        ##       ##     ## ##     ## ##     ##
########           #######  ##        ########  #######  ##     ## ########
 --}}
                <div x-show="tab === 'upload'" class="mt-4">
                    <!-- Messages de succès/erreur -->
                    @if (session()->has('success'))
                        <div class="mb-4 p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                            <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-4 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                            <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                        </div>
                    @endif

                    <!-- Sélecteur de type de média -->
                    <div class="mb-4">
                        <label for="media_type_select"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Type de média
                        </label>
                        <x-select-custom wire:model="selectedMediaTypeId" id="media_type_select" class="w-fit">
                            @foreach ($mediaTypes as $media_type)
                                <x-opt value="{{ $media_type->id }}">
                                    <div
                                        class="text-center px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center
                                    {{ $media_type->background_color_light ? 'bg-[' . $media_type->background_color_light . ']' : 'bg-gray-100' }}
                                    {{ $media_type->text_color_light ? 'text-[' . $media_type->text_color_light . ']' : 'text-gray-800' }}
                                    {{ $media_type->background_color_dark ? 'dark:bg-[' . $media_type->background_color_dark . ']' : 'dark:bg-gray-700' }}
                                    {{ $media_type->text_color_dark ? 'dark:text-[' . $media_type->text_color_dark . ']' : 'dark:text-gray-200' }}">
                                        {{ $media_type->nom }}
                                    </div>
                                </x-opt>
                            @endforeach
                        </x-select-custom>
                    </div>

                    <div
                        class="border-dashed border-2 border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">Glissez-déposez votre fichier ici ou cliquez
                            pour sélectionner</p>

                        <!-- Zone d'upload avec gestion des événements Livewire -->
                        <div x-data="{
                            uploading: false,
                            progress: 0,
                            handleDrop(e) {
                                e.preventDefault();
                                const files = e.dataTransfer.files;
                                if (files.length) {
                                    @this.uploadMultiple('files', files);
                                }
                            }
                        }"
                            x-on:dragover.prevent="$el.classList.add('bg-gray-100', 'dark:bg-gray-700')"
                            x-on:dragleave.prevent="$el.classList.remove('bg-gray-100', 'dark:bg-gray-700')"
                            x-on:drop="handleDrop" x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                            class="mt-4 cursor-pointer transition-colors duration-200 ease-in-out pb-4">
                            <label class="block">
                                <span class="sr-only">Choisir des fichiers</span>
                                <input type="file" wire:model="files"
                                    accept="{{ implode(',', Media::AUTHORIZED_FILE_EXTENSIONS) }}" multiple
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600" />
                            </label>

                            <!-- Barre de progression -->
                            <div x-show="uploading" class="mt-4">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                                    <div class="bg-blue-600 h-2.5 rounded-full" x-bind:style="`width: ${progress}%`">
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400"
                                    x-text="`Téléchargement en cours... ${progress}%`"></p>
                            </div>
                        </div>

                        <!-- Message d'erreur -->
                        @error('files.*')
                            <div class="mt-2 text-red-500 text-sm">{{ $message }}</div>
                        @enderror

                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            <p>Formats acceptés: {{ implode(' ', Media::AUTHORIZED_FILE_EXTENSIONS) }}</p>
                            <p>Taille maximale: 5MB</p>
                        </div>
                    </div>
                </div>
{{--
 #######   ########        ######   #######  ########  ########
##     ##  ##     ##      ##    ## ##     ## ##     ## ##
##     ##  ##     ##      ##       ##     ## ##     ## ##
##     ##  ########       ##       ##     ## ##     ## ######
##  ## ##  ##   ##        ##       ##     ## ##     ## ##
##    ###  ##    ##       ##    ## ##     ## ##     ## ##
 ###### ## ##     ##       ######   #######  ########  ########

                 --}}
                <div x-show="tab === 'qrcode'" class="mt-4">
                    <div class="text-center">
                        @if ($qrUrl)
                            <div class="bg-white p-4 rounded-lg inline-block mb-4">
                                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->merge(public_path('img/montaza.png'), 0.3, true)->size(300)->generate($qrUrl)) !!} ">
                            </div>
                            <p class="mb-4 text-gray-600 dark:text-gray-400">Scannez ce code QR pour télécharger des
                                documents depuis un autre appareil</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                Le lien expire dans <span x-data="{ timeLeft: $wire.qrDuration ?? 3600 }" x-init="setInterval(() => timeLeft > 0 ? timeLeft-- : clearInterval(this), 1000)">
                                    <span x-text="`${Math.floor(timeLeft / 3600)}h ${Math.floor((timeLeft % 3600) / 60)}m ${timeLeft % 60}s`"></span>
                                </span>
                            </p>
                        @else
                            <p class="mb-4 text-gray-600 dark:text-gray-400">Générez un code QR pour télécharger des
                                documents depuis un autre appareil</p>
                            <!-- Sélecteur de durée de validité -->
                            <div class="mb-4">
                                <label for="qr_duration" class="text-gray-700 dark:text-gray-300 text-sm mr-2">Durée de validité :</label>
                                <select id="qr_duration" wire:model="qrDuration" class="select">
                                    <option value="600">10 minutes</option>
                                    <option value="1800">30 minutes</option>
                                    <option value="3600">1 heure</option>
                                    <option value="14400">4 heures</option>
                                    <option value="86400">1 jour</option>
                                </select>
                            </div>
                            <button wire:click="generateQrCode"
                                class="px-4 py-2 mb-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                Générer un QR Code
                            </button>
                        @endif
                        <p class="mb-4 text-gray-600 dark:text-gray-400">L'appareil doit être connecté au réseau Wifi
                            de l'entreprise pour fonctionner</p>
                    </div>
                </div>
            </div>
        </div>
    </x-volet-modal>
</div>
