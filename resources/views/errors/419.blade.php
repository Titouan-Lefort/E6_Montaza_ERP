<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div
        class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0 flex-col">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                    419 </div>

                <div class="ml-4 text-lg text-gray-500 uppercase tracking-wider">
                    PAGE Expirée </div>

            </div>

        </div>
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 mt-10">
            <a href="{{ url()->previous() }}" class="btn bg-white dark:bg-gray-800 rounded-full p-4 text-base">
                {{ __('Retour') }}
            </a>
        </div>
        <a href="{{ route('accueil') }}" class="text-blue-500 hover:text-blue-700 underline">retour à l'accueil</a>

    </div>
</body>

</html>
