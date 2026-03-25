<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
    @if (Auth::user()->hasPermission('gerer_les_permissions'))

    <x-nav-link :href="route('permissions')" :active="request()->routeIs('permissions')">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">
            {{ __('Permissions') }}
        </h2>
    </x-nav-link>
    @endif
    @if (Auth::user()->hasPermission('gerer_les_postes'))
        <x-nav-link :href="route('roles')" :active="request()->routeIs('roles')">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">
                {{ __('Postes') }}
            </h2>
        </x-nav-link>
    @endif

    <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-start sm:items-center">
        @if (Auth::user()->hasPermission('gerer_les_postes'))
        <button type="button" class="btn mb-4" x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'create-role-modal')">
            Créer un Poste
        </button>
    @endif

        <x-modals.create_role :entites="$entites" />
    </div>
</div>
