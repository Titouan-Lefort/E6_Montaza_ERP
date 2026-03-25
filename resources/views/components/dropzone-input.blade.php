@props(['id', 'name', 'class' => '', 'attributes' => '','accept' => '', 'old' => old($name ?? 'dropzone-file')])

{{-- Dropzone input for file upload --}}
{{-- This component creates a styled dropzone area for file uploads. --}}

<div class="flex items-center justify-center w-full">
    <label id="dropzone-label-{{ $id ?? 'dropzone-file' }}" for="{{ $id ?? 'dropzone-file' }}" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-700 dark:bg-gray-800 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600 {{ $class ?? '' }}">
        <div class="flex flex-col items-center justify-center pt-5 pb-6">
            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
            </svg>
            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 text-center"><span class="font-semibold">Cliquez pour télécharger</span> ou faites glisser et déposez</p>
            <p class="text-xs text-gray-500 dark:text-gray-400" id="file-name">
                @if($old)
                    <strong>{{ basename($old) }}</strong>
                @else
                    {{ $accept ?? ''}}
                @endif
            </p>
        </div>
        <input id="{{ $id ?? 'dropzone-file' }}" name="{{ $name ?? 'dropzone-file' }}" type="file" class="hidden" {{  $attributes }} onchange="displayFileName(event)" {{ $accept ? 'accept='.$accept : '' }} />
    </label>
</div>
<p id="file-name" class="mt-2 text-sm text-gray-500 dark:text-gray-400"></p>

<script>
    function displayFileName(event) {
        const input = event.target;
        const fileName = input.files[0] ? input.files[0].name : '';
        document.querySelectorAll('#file-name').forEach(el => {
            el.innerHTML = fileName ? '<strong>'+fileName+'</strong>' : '';
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('dropzone-label-{{ $id ?? 'dropzone-file' }}');
        const input = document.getElementById('{{ $id ?? 'dropzone-file' }}');
        // Affiche le old si présent
        @if($old)
        document.querySelectorAll('#file-name').forEach(el => {
            el.innerHTML = '<strong>{{ basename($old) }}</strong>';
        });
        @endif
        if(dropzone && input) {
            const accept = "{{ $accept }}";
            function isFileAccepted(file) {
                if (!accept) return true;
                const acceptList = accept.split(',').map(a => a.trim().toLowerCase());
                const fileName = file.name.toLowerCase();
                return acceptList.some(ext => {
                    if(ext.startsWith('.')) {
                        return fileName.endsWith(ext);
                    }
                    // gestion mimetype (ex: image/*)
                    if(ext.endsWith('/*')) {
                        return file.type.startsWith(ext.replace('/*','/'));
                    }
                    return file.type === ext;
                });
            }
            dropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropzone.classList.add('ring-2', 'ring-blue-500');
            });
            dropzone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dropzone.classList.remove('ring-2', 'ring-blue-500');
            });
            dropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropzone.classList.remove('ring-2', 'ring-blue-500');
                if(e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    const file = e.dataTransfer.files[0];
                    if(isFileAccepted(file)) {
                        input.files = e.dataTransfer.files;
                        displayFileName({target: input});
                        document.getElementById('dropzone-error-{{ $id ?? 'dropzone-file' }}')?.classList.add('hidden');
                    } else {
                        document.getElementById('dropzone-error-{{ $id ?? 'dropzone-file' }}').classList.remove('hidden');
                        input.value = '';
                        displayFileName({target: input});
                    }
                }
            });
        }
    });
</script>
<p id="dropzone-error-{{ $id ?? 'dropzone-file' }}" class="hidden text-red-500 text-sm mt-2">Type de fichier non autorisé.</p>
