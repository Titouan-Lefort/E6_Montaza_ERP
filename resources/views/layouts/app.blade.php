<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

{{-- Vérifie si l'utilisateur est connecté et s'il n'a pas de poste --}}
@if (Auth::check())
    @if (!Auth::user()->role)
        {{ Auth::logout() }}
        <script>
            window.location.href = "{{ route('login') }}";
        </script>
    @endif
@endif

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="circle-wrapper">
        <div id="circle" class="circle-effect" style="display:none"></div>
        </div>
        {{-- Notifications modernes --}}
        @php
            $notifType = null;
            $notifMsg = null;
            if (session('success')) {
                $notifType = 'success';
                $notifMsg = session('success');
            } elseif (session('status')) {
                $notifType = 'success';
                $notifMsg = session('status');
            } elseif (session('error')) {
                $notifType = 'error';
                $notifMsg = session('error');
            }
        @endphp

        @if ($notifMsg)
            <div id="flash-message"
                class="fixed top-6 left-1/2 transform -translate-x-1/2 z-100 min-w-[300px] max-w-[90vw] px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 transition-all duration-500
                    @if ($notifType === 'success') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100 @endif
                    @if ($notifType === 'error') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100 @endif
                    opacity-0 pointer-events-none"
                role="alert">
                <svg class="w-6 h-6 flex-shrink-0
                    @if ($notifType === 'success') text-green-500 dark:text-green-200 @endif
                    @if ($notifType === 'error') text-red-500 dark:text-red-200 @endif
                "
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    @if ($notifType === 'success')
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    @endif
                </svg>
                <span class="flex-1">{!! $notifMsg !!}</span>
                <button onclick="hideFlashMessage()"
                    class="ml-3 text-lg font-bold focus:outline-none hover:opacity-70 transition-opacity">&times;</button>
            </div>
            <script>
                // Animation d'apparition/disparition
                window.addEventListener('DOMContentLoaded', function() {
                    const flash = document.getElementById('flash-message');
                    if (flash) {
                        setTimeout(() => {
                            flash.classList.remove('opacity-0', 'pointer-events-none');
                            flash.classList.add('opacity-100');
                        }, 100);
                        // Disparition auto après 4s sauf si survolé
                        let hideTimeout = setTimeout(hideFlashMessage, 4000);
                        flash.addEventListener('mouseenter', () => clearTimeout(hideTimeout));
                        flash.addEventListener('mouseleave', () => {
                            hideTimeout = setTimeout(hideFlashMessage, 2000);
                        });
                    }
                });

                function hideFlashMessage() {
                    const flash = document.getElementById('flash-message');
                    if (flash) {
                        flash.classList.remove('opacity-100');
                        flash.classList.add('opacity-0', 'pointer-events-none');
                        setTimeout(() => {
                            if (flash) flash.remove();
                        }, 500);
                    }
                }
            </script>
        @endif
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow-sm flex ">
                <a href="{{ url()->previous() }}" onclick="window.history.go(-1); return false;"
                    class="flex px-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 items-center">
                    <x-icon size="1" type="arrow_back" class="fill-gray-500 dark:fill-gray-300" />
                </a>
                <div class="w-19/20 ml-0 py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset
        @isset($header_nav)
            <header class="bg-white dark:bg-gray-800 shadow-sm flex ">
                <a href="{{ url()->previous() }}" onclick="window.history.go(-1); return false;"
                    class="flex p-4 sm:p-6 lg:p-8 px-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 items-center">
                    <x-icon size="1" type="arrow_back" class="fill-gray-500 dark:fill-gray-300" />
                </a>
                <div class="w-19/20 ml-0 mt-10 px-4 sm:px-6 lg:px-8">
                    {{ $header_nav }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    <script>
        function updateDateInputs(input) {
            // Parcourir chaque input
            // Vérifier si la valeur correspond au format 00XX-XX-XX
            const regex = /^00(\d{2})-(\d{2})-(\d{2})$/;
            if (regex.test(input.value)) {
                // Remplacer 00 par 20 dans l'année
                input.value = input.value.replace(regex, '20$1-$2-$3');
            }
        }

        function showFlashMessageFromJs(contenu, duree = 3000, type = 'success') {
            // Supprime les notifications existantes
            document.querySelectorAll('#flash-message').forEach(function(element) {
                element.remove();
            });

            // Crée la notification
            const flashMessage = document.createElement('div');
            flashMessage.id = 'flash-message';
            flashMessage.className =
                'fixed top-6 left-1/2 transform -translate-x-1/2 z-1000 min-w-[300px] max-w-[90vw] px-4 py-3 rounded-md shadow-md flex items-center gap-3 transition-all duration-500 opacity-0 pointer-events-none ' +
                (type === 'error' ?
                    'bg-red-600 text-white dark:bg-red-700' :
                    'bg-green-600 text-white dark:bg-green-700'
                );
            flashMessage.innerHTML = `
                <svg class="w-5 h-5 flex-shrink-0 ${type === 'error' ? 'text-white' : 'text-white'}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    ${type === 'error'
                        ? '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>'
                    }
                </svg>
                <span class="flex-1 font-medium">${contenu}</span>
                <button onclick="hideFlashMessage()" class="ml-3 text-lg font-bold focus:outline-none hover:opacity-80 transition-opacity">&times;</button>
            `;
            document.body.appendChild(flashMessage);

            setTimeout(() => {
                flashMessage.classList.remove('opacity-0', 'pointer-events-none');
                flashMessage.classList.add('opacity-100');
            }, 100);

            let hideTimeout = setTimeout(hideFlashMessage, duree);
            flashMessage.addEventListener('mouseenter', () => clearTimeout(hideTimeout));
            flashMessage.addEventListener('mouseleave', () => {
                hideTimeout = setTimeout(hideFlashMessage, 1200);
            });
        }

        function hideFlashMessage() {
            const flash = document.getElementById('flash-message');
            if (flash) {
                flash.classList.remove('opacity-100');
                flash.classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => {
                    if (flash) flash.remove();
                }, 500);
            }
        }

        function copyToClipboard(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function() {
                    showFlashMessageFromJs('Texte copié avec succès', 2000, 'success');
                }, function(err) {
                    showFlashMessageFromJs('Erreur lors de la copie du texte', 3000, 'error');
                });
            } else {
                const textArea = document.createElement('textarea');
                textArea.style.position = 'fixed';
                textArea.style.opacity = '0';
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    showFlashMessageFromJs('Texte copié avec succès', 2000, 'success');
                } catch (err) {
                    showFlashMessageFromJs('Erreur lors de la copie du texte', 3000, 'error');
                }
                document.body.removeChild(textArea);
            }
        }

        if (window.opener || window.name === "_blank" || history.length <= 1) {
            // Affiche l'animation du cercle
            const circle = document.getElementById("circle");
            if (circle) {
                circle.style.display = "block";
                setTimeout(() => {
                    circle.classList.add("animate");
                }, 10);
                setTimeout(() => {
                    circle.remove();
                }, 1300);
            }
        }
    </script>
    @livewireScripts
</body>

</html>
