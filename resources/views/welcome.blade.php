<x-guest-layout>
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ __('Bienvenue') }}</h1>

        <div class="w-full">
            @if (Auth::check())
                <a href="{{ route('accueil') }}" class="block w-full">
                     <x-primary-button class="w-full justify-center py-3 text-base">
                        {{ __('Accéder au site') }}
                    </x-primary-button>
                </a>
            @else
                <div class="flex flex-col gap-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Veuillez vous connecter pour continuer</p>
                    <a href="{{ route('login') }}" class="block w-full">
                        <x-primary-button class="w-full justify-center py-3 text-base">
                            {{ __('Se connecter') }}
                        </x-primary-button>
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>
