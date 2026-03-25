<x-app-layout>
    @section('title', 'Icons')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administration') }}
        </h2>
    </x-slot>

    <div class="py-12 text-gray-500 dark:text-gray-500">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg p-4 flex flex-wrap gap-4">
                @php
                    $iconComponents = collect(File::files(resource_path('views/components/icons')))->map(function (
                        $file,
                    ) {
                        return str_replace('.blade.php', '', $file->getFilename());
                    });
                    $oldtypes = [
                        'edit',
                        'error_icon',
                        'restore',
                        'arrow_back',
                        'send',
                        'bell',
                        'read',
                        'open_in_new',
                        'unread',
                        'arrow_forward',
                        'contact',
                        'copy',
                    ];
                @endphp
                <div class="w-full flex justify-start">
                    <h1>
                        <span class="text-3xl font-bold mb-6 text-left">{{ __('New Icons') }}</span>
                    </h1>
                </div>
                @foreach ($iconComponents as $icon)
                    <div class="flex flex-col items-center">
                        <x-dynamic-component :component="'icons.' . $icon" class="w-12 h-12 icons" />
                        <span class="text-sm mt-2">{{ $icon }}</span>
                    </div>
                @endforeach
                <div class="w-full flex justify-start">
                    <h1>
                        <span class="text-3xl font-bold mb-6 text-left">{{ __('Old Icons') }}</span>
                    </h1>
                </div>
                @foreach ($oldtypes as $icon)
                    <div class="flex flex-col items-center">
                        <x-icon :type="$icon" class="w-12 h-12 icons" />
                        <span class="text-sm mt-2">{{ $icon }}</span>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>
