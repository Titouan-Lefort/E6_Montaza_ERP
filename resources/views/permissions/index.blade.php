<x-app-layout>
    <x-slot name="header_nav">
        @include('permissions.navigation')
    </x-slot>

    <div class="py-12" id="container">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                <div class=" p-6 text-gray-900 dark:text-gray-100 ">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        {{ __('Poste') }}
                    </h2>
                    <form action="{{ route('permissions.edit') }}" method="post">
                        @csrf
                        @method('PUT')
                        @isset($role)
                            <x-select_id_role :selected="$role->id" :entites="$entites" class="max-w-md select" />
                        @else
                            <x-select_id_role :entites="$entites" class="max-w-md select" />
                        @endisset


                        <div class="mt-6">

                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                {{ __('Permissions') }}
                            </h2>

                            <div class="mb-4">
                                <button type="button" id="toggle-all" class="btn">
                                    Tout cocher
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($permissions as $permission)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="permission-{{ $permission->id }}"
                                            value="{{ $permission->id }}" id="permission-{{ $permission->id }}"
                                            class="mr-2 permission-checkbox"
                                            @php
                                                if (isset($role)) {
                                                    foreach ($role->permissions as $role_permission) {
                                                        if ($role_permission->id == $permission->id) {
                                                            echo 'checked';
                                                        }
                                                    }
                                                } @endphp>
                                        <label for="permission-{{ $permission->id }}"
                                            class="text-gray-900 dark:text-gray-100">{{ str_replace('_', ' ', $permission->name) }}
                                            <small class="text-gray-500 dark:text-gray-400"><br/>{{ $permission->description }}</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn">
                                    Mettre à jour les permissions
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        document.querySelector('#role_id').addEventListener('change', function() {
            const roleId = this.value;
            const newUrl = `${window.location.origin}/permissions/${roleId}`;
            window.location.href = newUrl;
            const container = document.getElementById('container');
            const containerHeight = container.offsetHeight;
            container.innerHTML = '<div id="loading-spinner" class="inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50" style="height: ' + containerHeight + 'px;"><div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div></div><style>.loader {border-top-color: #3498db;animation: spinner 1.5s linear infinite;}@keyframes spinner {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}</style>';

        });

        document.getElementById('toggle-all').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });

            this.textContent = allChecked ? 'Tout cocher' : 'Tout décocher';
        });

        // Vérifier l'état initial au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const toggleButton = document.getElementById('toggle-all');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            toggleButton.textContent = allChecked ? 'Tout décocher' : 'Tout cocher';
        });
    </script>
</x-app-layout>
