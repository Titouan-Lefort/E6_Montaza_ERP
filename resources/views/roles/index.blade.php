<x-app-layout>
    <x-slot name="header_nav">
        @include('permissions.navigation')
    </x-slot>

    <div class="py-12" id="container">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                <div class=" p-6 text-gray-900 dark:text-gray-100 ">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        {{ __('Poste') }}
                    </h2>
                    <form action="{{ route('roles.update', ['role' => $role]) }}" method="post">
                        @csrf
                        @method('PATCH')
                        @isset($role)
                            <x-select_id_role :selected="$role->id" :entites="$entites" class="max-w-md select" trashed="true" />
                        @else
                            <x-select_id_role :entites="$entites" class="max-w-md select" trashed="true" />
                        @endisset
                        <div class="mt-4 m-w-3/4">
                            <x-input-label for="entite_id" :value="__('Raison sociale')" />
                            <select id="entite_id" name="entite_id" class="select" required
                                {{ $role->trashed() ? 'disabled' : '' }}>
                                @foreach ($entites as $entite)
                                    <option value="{{ $entite->id }}"
                                        {{ $role->entite_id == $entite->id ? 'selected' : '' }}>
                                        {{ $entite->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4 m-w-3/4">
                            <x-input-label for="role_name" :value="__('Nom')" />
                            <x-text-input id="role_name" class="block mt-1 w-full" type="text" name="role_name"
                                :disabled="$role->trashed()" :value="old('role_name', $role->name ?? '')" required autofocus />
                        </div>

                        <button class="btn mt-4" {{ $role->trashed() ? 'disabled' : '' }}>
                            {{ __('Modifier') }}
                        </button>


                    </form>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                <div class=" p-6 text-gray-900 dark:text-gray-100 ">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        {{ $users->count() }}

                        {{ __('Utilisateur' . ($users->count() > 1 ? 's' : '')) }}
                    </h2>
                    <p class="text-sm text-red-600 dark:text-red-400 mb-4">
                        @if ($role->trashed())
                            @if ($users->count() > 1)
                                {{ __('Attention: ces comptes ne fonctionneront plus si leur poste a été désactivé. Veuillez soit réactiver le poste, soit changer le poste de ces comptes.') }}
                            @else
                                {{ __('Attention: ce compte ne fonctionnera plus si son poste a été désactivé. Veuillez soit réactiver le poste, soit changer le poste de ce compte.') }}
                            @endif
                        @endif
                    </p>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-linear-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800">
                            <th scope="col">Prénom</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Action</th>
                        </thead>
                        <tbody
                            class="{{ request()->get('show_deleted') ? 'bg-gray-100 dark:bg-gray-900' : 'bg-white dark:bg-gray-800' }} divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($users as $user)
                                <tr>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                        {{ $user->first_name }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                        {{ $user->last_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form action="{{ route('profile.update_admin') }}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex items-center">
                                                <input type="hidden" name="id" value="{{ $user->id }}">
                                                <x-select_id_role placeholder="changer le poste" :entites="$entites"
                                                    class="block max-w-md select-left" />
                                                <button type="submit" class="btn-select-right">
                                                    <x-icon type="send" size="1" class=" icons-no_hover" />
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
            @if (!$role->undeletable)
                @if ($role->deleted_at)
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg  p-6 text-gray-900 dark:text-gray-100 ">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Activer le poste') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Une fois ce poste activé, les utilisateurs pourront y être affectés') }}
                            </p>
                        </header>

                        <x-danger-button x-data="" class="mt-6"
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-role-activation')">{{ __('Activer') }}</x-danger-button>

                        <x-modal name="confirm-role-activation" focusable>
                            <form method="post" action="{{ route('roles.restore', ['role' => $role]) }}"
                                class="p-6">
                                @csrf
                                @method('patch')
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Êtes-vous sûr de vouloir activer ce poste') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $role->name }}
                                </p>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Cancel') }}
                                    </x-secondary-button>

                                    <x-danger-button class="ms-3">
                                        {{ __('Activer le poste') }}
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                    </div>
                @else
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg  p-6 text-gray-900 dark:text-gray-100 ">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Désactiver le poste') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Une fois ce poste désactivé, la connexion de ses utilisateurs sera impossible') }}
                            </p>
                        </header>

                        <x-danger-button x-data="" class="mt-6"
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-role-deletion')">{{ __('Désactiver') }}</x-danger-button>

                        <x-modal name="confirm-role-deletion" focusable>
                            <form method="post" action="{{ route('roles.destroy', $role) }}" class="p-6">
                                @csrf
                                @method('delete')
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Êtes-vous sûr de vouloir Désactiver ce poste') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $role->name }}
                                </p>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Cancel') }}
                                    </x-secondary-button>

                                    <x-danger-button class="ms-3">
                                        {{ __('Désactiver le poste') }}
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                    </div>
                @endif
            @endif
        </div>
    </div>
    </div>
    <script>
        document.querySelector('#role_id').addEventListener('change', function() {
            const roleId = this.value;
            const newUrl = `${window.location.origin}/postes/${roleId}`;
            window.location.href = newUrl;
            const container = document.getElementById('container');
            const containerHeight = container.offsetHeight;
            container.innerHTML = '<div id="loading-spinner" class="inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50" style="height: ' + containerHeight + 'px;"><div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div></div><style>.loader {border-top-color: #3498db;animation: spinner 1.5s linear infinite;}@keyframes spinner {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}</style>';
        });
    </script>
</x-app-layout>
