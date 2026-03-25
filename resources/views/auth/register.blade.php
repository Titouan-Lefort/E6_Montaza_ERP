<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Créer un compte') }}
        </h2>
    </x-slot>
    <div class="justify-center flex items-center">

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <!-- Last Name -->
                <div class="mt-4">
                    <x-input-label for="last_name" :value="__('Nom')" />
                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name"
                        :value="old('last_name')" required autocomplete="family-name" />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>
                <!-- First Name -->
                <div class="mt-4">
                    <x-input-label for="first_name" :value="__('Prénom')" />
                    <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name"
                        :value="old('first_name')" required autocomplete="given-name" />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>
                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <!-- Phone Number -->
                <div class="mt-4">
                    <x-input-label for="phone" :value="__('Numéro de téléphone')" />
                    <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone"
                        :value="old('phone')" required autocomplete="tel" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <!-- Role -->
                <div class="mt-4">
                    <x-input-label for="role_id" :value="__('Poste')" />
                    <div class="flex">
                        <x-select_id_role :entites="$entites" class="select" />
                        {{-- <button type="button" class="btn-select-right" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-role-modal')">
                            +
                        </button> --}}

                    </div>
                    <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                </div>
                <div class="flex items-center justify-center mt-4">


                    <button class="btn ms-4">
                        {{ __('Créer') }}
                    </button>
                </div>

            </form>
            {{-- <x-modal name="create-role-modal" focusable :show="old('role_name')">
                <form method="POST" action="{{ route('role.store') }}" x-show="show" class="p-6">
                    @csrf
                    <div class="p-8">
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
            </x-modal> --}}
        </div>
    </div>
</x-app-layout>
