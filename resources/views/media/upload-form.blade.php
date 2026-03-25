<!-- filepath: c:\Users\prepaetude\Homestead\code\montaza\resources\views\media\upload-form.blade.php -->
@php
    use App\Models\Media;
@endphp
<x-guest-layout>
    <div class="py-2">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 text-center">
            Téléchargement de pièces jointes
        </h2>

        <div class="mb-6 text-center">
            <p class="text-gray-700 dark:text-gray-300">
                Ajoutez des pièces jointes à
                <strong class="font-medium">{{ $model }}</strong>
                @if (isset($entity->reference))
                    : <span class="font-medium">{{ $entity->reference }}</span>
                @endif
            </p>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 p-3 rounded-md">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ url('/media/upload/' . $model . '/' . $id . '/' . $token . '?signature=' . request()->query('signature') . '&expires=' . request()->query('expires')) }}"
            method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Sélecteur de type de média -->
            <div>
                <x-input-label for="media_type_id" :value="__('Type de média')" />
                <x-select-custom name="media_type_id" id="media_type_id"
                    class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600">
                    @foreach (\App\Models\MediaType::all() as $media_type)
                        <x-opt value="{{ $media_type->id }}">
                            <div
                                class="text-center w-full px-2 text-xs leading-5 flex rounded-full font-bold items-center justify-center
                                    {{ $media_type?->background_color_light ? 'bg-[' . $media_type->background_color_light . ']' : 'bg-gray-100' }}
                                    {{ $media_type?->text_color_light ? 'text-[' . $media_type->text_color_light . ']' : 'text-gray-800' }}
                                    {{ $media_type?->background_color_dark ? 'dark:bg-[' . $media_type->background_color_dark . ']' : 'dark:bg-gray-700' }}
                                    {{ $media_type?->text_color_dark ? 'dark:text-[' . $media_type->text_color_dark . ']' : 'dark:text-gray-200' }}">
                                {{ $media_type->nom ?? 'N/A' }}
                            </div>
                        </x-opt>
                    @endforeach
                </x-select-custom>
            </div>

            <div>
                <x-input-label for="files" :value="__('Sélectionnez des fichiers')" />
                <div class="mt-1">
                    <input id="files" type="file" name="files[]" multiple
                        accept="{{ implode(',', Media::AUTHORIZED_FILE_EXTENSIONS) }}" class="input-file w-full"
                        value="{{ old('files') }}" required>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    {{ implode(' ', Media::AUTHORIZED_FILE_EXTENSIONS) }} (max. 5MB)
                </p>
            </div>

            <div id="preview" class="grid grid-cols-3 gap-2 mt-4"></div>

            <div class="flex items-center justify-center mt-6">
                <button type="submit" class="btn">
                    Télécharger
                </button>
            </div>
        </form>
    </div>

    <script>
        // Fonction pour formater la taille du fichier
        function formatFileSize(bytes) {
            if (bytes < 1024) {
                return bytes + ' B';
            } else if (bytes < 1024 * 1024) {
                return (bytes / 1024).toFixed(1) + ' KB';
            } else {
                return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
            }
        }
        document.getElementById('files').addEventListener('change', function() {
            const preview = document.getElementById('preview');
            preview.innerHTML = '';

            if (this.files) {
                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    const div = document.createElement('div');
                    div.className =
                        'relative aspect-square bg-gray-100 dark:bg-gray-750 rounded-lg overflow-hidden flex items-center justify-center';

                    // Image preview
                    if (file.type.startsWith('image/')) {
                        reader.onload = e => {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'h-full w-full object-cover';
                            div.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                    // Video preview (mp4)
                    else if (file.type === 'video/mp4') {
                        reader.onload = e => {
                            const video = document.createElement('video');
                            video.src = e.target.result;
                            video.controls = true;
                            video.className = 'h-full w-full object-cover';
                            div.appendChild(video);
                        };
                        reader.readAsDataURL(file);
                    }
                    // Audio preview (mp3)
                    else if (file.type === 'audio/mpeg') {
                        reader.onload = e => {
                            const audio = document.createElement('audio');
                            audio.src = e.target.result;
                            audio.controls = true;
                            audio.className = 'w-full';
                            div.appendChild(audio);
                        };
                        reader.readAsDataURL(file);
                    }
                    // PDF preview (icon + name)
                    else if (file.type === 'application/pdf') {
                        const icon = document.createElement('div');
                        icon.innerHTML =
                            '<svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.828A2 2 0 0 0 19.414 7.414l-4.828-4.828A2 2 0 0 0 12.172 2H6zm7 1.414L18.586 9H15a2 2 0 0 1-2-2V3.414z"/></svg>';
                        div.appendChild(icon);
                    }
                    // Word document preview (icon)
                    else if (
                        file.type === 'application/msword' ||
                        file.type ===
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    ) {
                        const icon = document.createElement('div');
                        icon.innerHTML =
                            '<svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.828A2 2 0 0 0 19.414 7.414l-4.828-4.828A2 2 0 0 0 12.172 2H6zm7 1.414L18.586 9H15a2 2 0 0 1-2-2V3.414z"/></svg>';
                        div.appendChild(icon);
                    }
                    // Excel document preview (icon)
                    else if (
                        file.type === 'application/vnd.ms-excel' ||
                        file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ) {
                        const icon = document.createElement('div');
                        icon.innerHTML =
                            '<svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.828A2 2 0 0 0 19.414 7.414l-4.828-4.828A2 2 0 0 0 12.172 2H6zm7 1.414L18.586 9H15a2 2 0 0 1-2-2V3.414z"/></svg>';
                        div.appendChild(icon);
                    }
                    // CSV preview (icon)
                    else if (file.type === 'text/csv') {
                        const icon = document.createElement('div');
                        icon.innerHTML =
                            '<svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 24 24"><rect width="20" height="20" x="2" y="2" rx="2"/><text x="6" y="16" font-size="10" fill="white">CSV</text></svg>';
                        div.appendChild(icon);
                    }
                    // Plain text preview (show first lines)
                    else if (file.type === 'text/plain') {
                        reader.onload = e => {
                            const text = document.createElement('pre');
                            text.textContent = e.target.result.substring(0, 100) + (e.target.result
                                .length > 100 ? '...' : '');
                            text.className =
                                'text-xs p-2 w-full h-full overflow-auto bg-gray-200 dark:bg-gray-800 rounded';
                            div.appendChild(text);
                        };
                        reader.readAsText(file);
                    }
                    // Default icon for other types
                    else {
                        const icon = document.createElement('div');
                        icon.innerHTML =
                            '<svg class="w-8 h-8 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                        div.appendChild(icon);
                    }

                    const caption = document.createElement('div');
                    caption.className =
                        'absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 ';
                    caption.textContent = file.name + ' (' + formatFileSize(file.size) + ')';
                    div.appendChild(caption);

                    preview.appendChild(div);
                });
            }
        });
    </script>
</x-guest-layout>
