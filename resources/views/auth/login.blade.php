<x-guest-layout>
    <!-- Session Status -->
    <noscript>
        <div class="mb-4 text-red-600 bg-orange-100 border-l-4 border-orange-500 dark:text-red-100 dark:bg-red-700 dark:border-red-600 p-4" role="alert">
            {{ __('JavaScript est requis pour se connecter. Veuillez activer JavaScript dans les paramètres de votre navigateur et réessayer.') }}
        </div>
    </noscript>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" onkeydown="if(event.key === 'Enter' && event.target.type !== 'checkbox') { event.preventDefault(); this.querySelector('button[type=submit]').click(); }">
        @csrf
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                {{ __('Log in') }}
            </h2>
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded-sm dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-xs focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 text-base">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'javaScriptValidation';
        input.value = 'true';
        document.querySelector('form').appendChild(input);
        });
    </script>
</x-guest-layout>
