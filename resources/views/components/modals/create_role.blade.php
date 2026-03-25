<x-modal name="create-role-modal" focusable :show="old('role_name')">
    <form method="POST" action="{{ route('permissions.role.store') }}" x-show="show" class="p-2">
        @csrf
        <a x-on:click="$dispatch('close')">
            <x-icons.close  class="float-right mb-1 icons" size="1.5" unfocus/>
            </a>
        <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Créer un nouveau rôle') }}
        </h2>
        <div class="mt-4">
            <x-input-label for="entite_id" :value="__('Raison sociale')" />
            <select id="entite_id" name="entite_id" class="select" required>
                @foreach($entites as $entite)
                    <option value="{{ $entite->id }}">{{ $entite->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('entite_id')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="role_name" :value="__('Nom du Poste')" />
            <x-text-input id="role_name" class="block mt-1 w-full" type="text" name="role_name" required autofocus value="{{old('role_name')}}"/>
            <x-input-error :messages="$errors->get('role_name')" class="mt-2" />
        </div>
        <div class="mt-4 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Annuler') }}
            </x-secondary-button>
            <x-primary-button class="ml-3">
                {{ __('Créer') }}
            </x-primary-button>
        </div>
    </div>
    </form>
</x-modal>
