{{-- filepath: c:\Users\prepaetude\Homestead\code\montaza\resources\views\components\select-custom.blade.php --}}
@props([
    'name' => '',
    'id' => '',
    'class' => '',
    'required' => false,
    'onchange' => '',
    'selected' => null,
])

<div
    x-data="{
        open: false,
        selected: '{{ $selected ?? '' }}',
        options: [],
        init() {
            this.options = Array.from($refs.optionsContainer.querySelectorAll('[data-option]')).map(el => ({
                value: el.dataset.value || '',
                selected: el.hasAttribute('data-selected'),
                disabled: el.hasAttribute('data-disabled'),
                content: el.innerHTML
            }));

            // Si une valeur est passée en prop, l'utiliser en priorité
            if (this.selected) {
                return;
            }

            // Sinon, prendre seulement le premier élément sélectionné
            const firstSelected = this.options.find(opt => opt.selected);
            if (firstSelected) {
                this.selected = firstSelected.value;
                this.options.forEach(opt => {
                    if (opt.value !== firstSelected.value) {
                        opt.selected = false;
                    }
                });
            }
        },
        select(value, disabled) {
            if (!disabled) {
                this.selected = value;
                this.open = false;
                // Mettre à jour explicitement la valeur de l'input
                this.$refs.hiddenInput.value = value;
                // Déclencher l'événement change
                this.$refs.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
        },
        getSelectedLabel() {
            const option = this.options.find(opt => opt.value === this.selected);
            return option ? option.content : (this.options.length > 0 ? this.options[0].content : 'Sélectionner...');

        },
        positionDropdown() {
            if (!this.open) return;

            const button = this.$refs.selectButton;
            const dropdown = this.$refs.dropdown;

            // Positionnement du dropdown par rapport au bouton
            const rect = button.getBoundingClientRect();
            dropdown.style.width = `${rect.width}px`;
            dropdown.style.left = `${rect.left}px`;
            dropdown.style.top = `${rect.bottom + window.scrollY}px`;
        }
    }"
    x-init="$nextTick(() => { $watch('open', value => { if(value) positionDropdown() }) })"
    @resize.window="if(open) positionDropdown()"
    class="relative w-full {{ $class }}"
>
    <input
        type="hidden"
        name="{{ $name }}"
        :value="selected"
        @if($required) required @endif
        @if($onchange) onchange="{{ $onchange }}" @endif
        x-ref="hiddenInput"
    >

    <button
        type="button"
        @click="open = !open"
        class="select"
        @if($id) id="{{ $id }}" @endif
        x-ref="selectButton"
    >
        <span x-html="getSelectedLabel()" class="truncate"></span>
        <svg class="w-4 h-4 ml-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown placé à l'extérieur dans le body -->
    <template x-teleport="body">
        <div
            x-show="open"
            @click.away="open = false"
            x-transition
            x-ref="dropdown"
            class="fixed z-1000 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-md shadow-lg overflow-auto"
            style="max-height: calc(100vh - 100px);"
        >
            <template x-for="option in options" :key="option.value">
                <div
                    @click="select(option.value, option.disabled)"
                    x-html="option.content"
                    :class="{
                        'opacity-50 cursor-not-allowed': option.disabled,
                        'hover:bg-blue-50 hover:text-blue-700 dark:hover:bg-gray-700 dark:hover:text-blue-300 cursor-pointer': !option.disabled,
                        'bg-blue-100 text-blue-700 dark:bg-gray-800 dark:text-blue-300': selected === option.value
                    }"
                    class="px-3 py-2 transition text-gray-800 dark:text-gray-200"
                ></div>
            </template>
        </div>
    </template>

    <div x-ref="optionsContainer" style="display: none;">
        {{ $slot }}
    </div>
</div>
