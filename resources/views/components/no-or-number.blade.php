@props([
    'name' => '',
    'id' => null,
    'value' => null,
    'disabled' => false,
    'required' => false,
    'onlyNumber' => false,
    'width' => 'md',
    'class' => '',
    'placeholder' => 'Entrez un nombre',
    'oninput' => '',
    'dont_delete_value' => false,
])

@php
    $id ??= $name;
    $isNon = $value === 'non';

    $sizeConfig = [
        'xs' => [
            'container' => 'w-20',
            'button' => 'px-2 py-2 text-xs',
            'input' => 'px-2 pb-2 pt-3 text-xs'
        ],
        'sm' => [
            'container' => 'w-28',
            'button' => 'px-3 py-2 text-sm',
            'input' => 'px-3 pb-2 pt-3 text-sm'
        ],
        'md' => [
            'container' => 'w-36',
            'button' => 'px-4 py-2 text-sm',
            'input' => 'px-4 pb-2 pt-3 text-sm'
        ],
        'lg' => [
            'container' => 'w-48',
            'button' => 'px-5 py-2 text-base',
            'input' => 'px-5 pb-2 pt-3 text-base'
        ],
        'xl' => [
            'container' => 'w-64',
            'button' => 'px-6 py-2 text-lg',
            'input' => 'px-6 pb-2 pt-3 text-lg'
        ],
        'full' => [
            'container' => 'w-full',
            'button' => 'px-4 py-2 text-sm',
            'input' => 'px-4 pb-2 pt-3 text-sm'
        ],
        'auto' => [
            'container' => 'w-auto',
            'button' => 'px-4 py-2 text-sm',
            'input' => 'px-4 pb-2 pt-3 text-sm'
        ]
    ];

    $config = $sizeConfig[$width] ?? $sizeConfig['md'];
@endphp

<div class="flex {{ $config['container'] }} {{ $class }}">
    <button
        type="button"
        id="{{ $id }}-non-button"
        class="{{ $isNon ? 'bg-indigo-600 text-white font-bold shadow-sm' : ($onlyNumber ? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600'  ) }} {{ $config['button'] }} rounded-l-md transition-all duration-200 {{ ($disabled || $onlyNumber) ? '' : 'cursor-pointer' }}"
        {{ ($disabled || $onlyNumber) ? 'disabled' : '' }}
        onclick="window.noOrNumberHandlers.toggleNon('{{ $id }}', '{{ $name }}', {{ $required ? 'true' : 'false' }}, '{{ $oninput }}', {{ $dont_delete_value ? 'true' : 'false' }})"
    >
        NON
    </button>
    <input
        type="number"
        name="{{ $isNon ? '' : $name }}"
        id="{{ $id }}"
        value="{{ $value !== 'non' ? $value : '' }}"
        placeholder="{{ $placeholder }}"
        {{ $disabled || $isNon ? 'disabled' : '' }}
        {{ !$isNon && $required ? 'required' : '' }}
        oninput="window.noOrNumberHandlers.handleInput('{{ $id }}', '{{ $oninput }}')"
        class="block w-full {{ $config['input'] }} border-y border-r border-gray-300 dark:border-gray-600 rounded-r-md font-medium text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 transition-all duration-200 {{ $isNon ? 'bg-gray-100 dark:bg-gray-800 opacity-50' : 'bg-white dark:bg-gray-900' }} focus:border-indigo-600"
    >
</div>

<script class="SCRIPT">
    if (!window.noOrNumberHandlers) {
        window.noOrNumberHandlers = {};

        window.noOrNumberHandlers.handleInput = (id, customOninput) => {
            if (customOninput) {
                eval(customOninput);
            }
        };

        window.noOrNumberHandlers.toggleNon = (id, name, required, customOninput, dontDeleteValue = false) => {
            const input = document.getElementById(id);
            const button = document.getElementById(id+'-non-button');
            let hiddenInput = document.getElementById(id+'-hidden');
            const nonSelected = input.disabled;

            if (!nonSelected) {
                // Set to NON
                const currentValue = input.value;
                if (!dontDeleteValue) {
                    input.value = '';
                }
                // Store the current value if we want to preserve it
                if (dontDeleteValue && currentValue) {
                    input.setAttribute('data-preserved-value', currentValue);
                }

                input.disabled = true;
                input.required = false;
                input.name = ''; // Remove name to prevent form submission
                input.classList.add('bg-gray-100', 'dark:bg-gray-800', 'opacity-50');
                input.classList.remove('bg-white', 'dark:bg-gray-900');

                button.classList.add('bg-indigo-600', 'text-white', 'font-bold', 'shadow-sm');
                button.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-300', 'dark:hover:bg-gray-600');

                // Create hidden input with 'non' value
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = name;
                    hiddenInput.value = 'non';
                    hiddenInput.id = id+'-hidden';
                    if (required) hiddenInput.required = true;
                    input.parentNode.appendChild(hiddenInput);
                } else {
                    hiddenInput.value = 'non';
                }
            } else {
                // Set to number input
                input.disabled = false;
                input.name = name; // Restore name for form submission
                if (required) input.required = true;

                // Restore preserved value if it exists
                if (dontDeleteValue) {
                    const preservedValue = input.getAttribute('data-preserved-value');
                    if (preservedValue) {
                        input.value = preservedValue;
                        input.removeAttribute('data-preserved-value');
                    }
                }

                input.classList.remove('bg-gray-100', 'dark:bg-gray-800', 'opacity-50');
                input.classList.add('bg-white', 'dark:bg-gray-900');

                button.classList.remove('bg-indigo-600', 'text-white', 'font-bold', 'shadow-sm');
                button.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-300', 'dark:hover:bg-gray-600');

                // Remove hidden input if exists
                if (hiddenInput) {
                    hiddenInput.remove();
                }
            }

            // Trigger custom oninput if provided
            if (customOninput) {
                eval(customOninput);
            }
        };
    }

    // Initialize state on page load
    document.addEventListener('DOMContentLoaded', () => {
        const id = '{{ $id }}';
        const isNon = {{ $isNon ? 'true' : 'false' }};
        const required = {{ $required ? 'true' : 'false' }};
        const dontDeleteValue = {{ $dont_delete_value ? 'true' : 'false' }};
        const input = document.getElementById(id);

        if (isNon) {
            input.disabled = true;
            input.required = false;
            input.classList.add('bg-gray-100', 'dark:bg-gray-800', 'opacity-50');

            // Create hidden input for 'non' value - always create when isNon is true
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = '{{ $name }}';
            hiddenInput.value = 'non';
            hiddenInput.id = id+'-hidden';
            if (required) hiddenInput.required = true;
            input.parentNode.appendChild(hiddenInput);
        }
    });
</script>
