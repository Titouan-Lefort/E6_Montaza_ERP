<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Changer le poste') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Changer le poste de cet utilisateur') }}
        </p>
    </header>
    <form method="post" action="{{ route('profile.update_admin') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        <input type="hidden" name="id" value="{{ $user->id }}">
        <x-select_id_role :entites="$entites" :user="$user" />
        <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        <button type="submit" class="btn">{{ __('Save') }}</button>
    </form>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Désactiver le compte') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Une fois ce compte désactivé, la connexion y sera impossible') }}
        </p>
    </header>

    <x-danger-button x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('Désactiver') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy', $user) }}" class="p-6">
            @csrf
            @method('delete')
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Êtes-vous sûr de vouloir désactiver ce compte') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ $user->first_name }} {{ $user->last_name }} - {{ $user->role->name }}
            </p>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Désactiver le compte') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
