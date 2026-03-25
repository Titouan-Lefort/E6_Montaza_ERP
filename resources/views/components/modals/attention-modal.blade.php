@props([
    'buttonText' => 'Action',
    'modalName' => 'attention-modal-'.Str::random(8),
    'title' => 'Êtes-vous sûr ?',
    'message' => 'Attention, voulez-vous vraiment faire cela ?',
    'customHtmlContent' => '',
    'confirmText' => 'Confirmer',
    'cancelText' => 'Annuler',
    'confirmAction' => '#',
])

<button x-data x-on:click="$dispatch('open-modal', '{{ $modalName }}')" class="btn" type="button">
    {{ $buttonText }}
</button>

<x-modal name="{{ $modalName }}">
    <div class="p-4">
        <a x-on:click="$dispatch('close')">
            <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
        </a>
        <h2 class="text-xl font-semibold mb-4">{{ $title }}</h2>
        <p class="mb-4">{{ $message }}</p>
        {{ $customHtmlContent }}
        <div class="flex justify-end gap-4">
            <button x-on:click="$dispatch('close')"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-sm">
                {{ $cancelText }}
            </button>
            @if($confirmAction === 'submit')
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-sm">
                    {{ $confirmText }}
                </button>
            @else
                <a href="{{ $confirmAction }}"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-sm">
                    {{ $confirmText }}
                </a>
            @endif
        </div>
    </div>
</x-modal>
