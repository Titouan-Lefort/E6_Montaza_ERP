<x-app-layout>
    @section('title', 'Demandes de prix et commandes')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {!! __('Demandes de prix et commandes') !!}
            </h2>

        </div>

    </x-slot>
    <div class="py-8 ">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 ">
            <div class=" overflow-hidden sm:rounded-lg grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div id="ddp_container" class="bg-white dark:bg-gray-800 rounded-md overflow-hidden shadow-md">
                    <div id="loading-spinner"
                        class=" mt-8 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-dvh w-full">
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
                </div>
                <div id="cde_container" class="bg-white dark:bg-gray-800 rounded-md overflow-hidden shadow-md">
                    <div id="loading-spinner"
                        class=" mt-8 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-dvh w-full">
                        <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/colddp')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('ddp_container').innerHTML = data;
                });
        });
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/colcde')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('cde_container').innerHTML = data;
                });
        });
    </script>


</x-app-layout>
