@props([
    'buttonText' => 'Supprimer',
    'customButton' => false,
    'modalTitle' => 'Supprimer',
    'confirmButtonText' => 'Confirmer',
    'cancelButtonText' => 'Annuler',
    'modalName' => 'deleteModal-' . Str::random(8) . '-' . Str::random(8) . '-' . Str::random(8),
    'formAction' => '',
    'errorName' => 'delete',
    'userInfo' => 'Cette action est irréversible. Êtes-vous sûr de vouloir supprimer cet élément ?',
    'onSubmit' => null,
])
{{--
exemple usage:
 <x-boutons.supprimer modalTitle="Supprimer le contact"
                                                confirmButtonText="Confirmer la suppression" cancelButtonText="Annuler"
                                                modalName="delete-contact-modal-{{ $contact->id }}"
                                                errorName="delete-contact-{{ $contact->id }}"
                                                onSubmit="deleteContact({{ $contact->id }})"
                                                userInfo="Êtes-vous sûr de vouloir supprimer ce contact ? Cette action est irréversible.">
                                                <x-slot:customButton>
                                                    <button type="button"
                                                        class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </x-slot:customButton>
                                            </x-boutons.supprimer>
 --}}
@if ($customButton == false)
    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $modalName }}')">
        {!! $buttonText !!}
    </x-danger-button>
@else
    <div x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $modalName }}')" class="inline-block">
        {!! $customButton !!}
    </div>
@endif

<x-modal name="{{ $modalName }}" :show="$errors->has($errorName)" focusable>
    <form method="post" action="{{ $formAction }}" class="p-6"
        @if($onSubmit)
        x-on:submit.prevent="{{ $onSubmit }}"
        @endif>
        @csrf
        @method('delete')
        <a x-on:click="$dispatch('close')">
            <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
        </a>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {!! $modalTitle !!}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {!! $userInfo !!}
        </p>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {!! $cancelButtonText !!}
            </x-secondary-button>

            <x-danger-button class="ms-3">
                {!! $confirmButtonText !!}
            </x-danger-button>
        </div>
    </form>
</x-modal>
