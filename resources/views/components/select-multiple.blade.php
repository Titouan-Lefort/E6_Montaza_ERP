{{--
/**
 * Multiple Select Component
 *
 * A customizable multi-select dropdown component that allows selecting multiple options from a list.
 * Uses Alpine.js for interactivity and Tailwind CSS for styling.
 *
 * Props:
 * @param array $options - Array of options available for selection (associative array with id => name)
 * @param array $selected - Array of pre-selected option IDs
 * @param bool $searchable - Whether the dropdown should include a search functionality
 * @param string $placeholder - Text to display when no options are selected
 * @param string $class - Additional CSS classes for the component
 * @param string $emptyMessage - Message to display when no options are available
 * @param string $name - Name attribute for the hidden input field
 * @param string $id - ID attribute for the hidden input field
 *
 * Features:
 * - Multiple item selection
 * - Search functionality (optional)
 * - Selected items displayed as tags with remove option
 * - Dropdown with toggle functionality
 * - Dark mode support
 * - Filter options to exclude already selected items
 * - Hidden input field to store selected values for form submission
 */
--}}
@props([
    'options' => [],
    'selected' => [],
    'searchable' => false,
    'placeholder' => 'Sélectionner une ou plusieurs options',
    'class' => '',
    'emptyMessage' => 'Aucune option disponible',
    'name' => '',
    'id' => '',
])

<div x-data="selectMultiple({
    options: {{ json_encode($options) }},
    selected: {{ json_encode($selected) }},
    searchable: {{ json_encode($searchable) }}
})" class="relative {{ $class }}" id="{{ $id }}-container">
    {{-- Hidden input to store selected values --}}
    <input type="hidden" name="{{ $name }}" id="{{ $id }}"
        x-bind:value="JSON.stringify(selectedItems)" />

    <div class="w-full">
        <div class="flex flex-col items-center relative">
            <div class="w-full">
                <div class="flex border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-md overflow-hidden">
                    <div class="flex flex-auto p-1 flex-wrap bg-white dark:bg-gray-900">
                        <template x-for="(itemId, index) in selectedItems" :key="index">
                            <div
                                class="flex justify-center items-center m-1 font-medium py-1 px-2 rounded-full
                                        bg-gray-100 text-gray-700 border border-gray-300
                                        dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700">
                                <div class="text-xs font-normal leading-none max-w-full flex-initial"
                                    x-html="getOptionLabel(itemId)"></div>
                                <div class="flex flex-auto flex-row-reverse">
                                    <div @click="removeItem(itemId)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-x cursor-pointer hover:text-gray-400 rounded-full w-4 h-4 ml-2">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <p>
                            <span x-show="selectedItems.length === 0"
                                class="text-gray-400 dark:text-gray-600 text-sm font-normal leading-none max-w-full flex-initial cursor-default">
                                {{ $placeholder }}
                            </span>
                        </p>
                        <div class="flex-1" x-show="searchable">
                            <input x-model="search" :placeholder="placeholder" @input="filterOptions()"
                                class="bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800 dark:text-gray-200">
                        </div>
                    </div>
                    <div
                        class="text-gray-400 dark:text-gray-600 w-8 border-l border-gray-200 dark:border-gray-700 flex items-center bg-gray-200 dark:bg-gray-900">
                        <button @click="toggleDropdown()"
                            class="cursor-pointer w-full h-full text-gray-600 dark:text-gray-400 outline-none focus:outline-none flex items-center justify-center"
                            type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather w-4 h-4"
                                :class="{ 'feather-chevron-up': open, 'feather-chevron-down': !open }">
                                <polyline x-show="open" points="18 15 12 9 6 15"></polyline>
                                <polyline x-show="!open" points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div x-show="open" @click.away="open = false"
                class="absolute shadow top-full bg-white dark:bg-gray-900 z-40 w-fit right-0 rounded max-h-select mt-1 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col w-full">
                    <template x-if="filteredOptions.length === 0">
                        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                            {{ $emptyMessage }}
                        </div>
                    </template>
                    <template x-for="(option, index) in filteredOptions" :key="index">
                        <div class="cursor-pointer w-full border-gray-100 dark:border-gray-800 rounded-t border-b hover:bg-gray-100 dark:hover:bg-gray-800"
                            @click="toggleOption(option.id)">
                            <div class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative">
                                <div class="w-full items-center flex">
                                    <div class="mx-2 leading-5 text-sm text-gray-800 dark:text-gray-200" x-html="option.name">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script class="SCRIPT">
    document.addEventListener('alpine:init', () => {
        Alpine.data('selectMultiple', ({
            options,
            selected,
            searchable
        }) => ({
            // Initialize component state properties
            options: Object.entries(options || {}).map(([id, name]) => ({
                id,
                name
            })),
            filteredOptions: [],
            selectedItems: Array.isArray(selected) ? selected.map(String) : [], // Convert to strings for consistent comparison
            search: '',
            open: false,
            searchable: searchable,
            placeholder: '{{ $placeholder }}',
            emptyMessage: '{{ $emptyMessage }}',

            /**
             * Initializes the component when it's mounted
             */
            init() {
                this.selectedItems = this.selectedItems.map(String); // Ensure IDs are strings for consistent comparison
                this.filterSelectedOptions(); // Apply filtering immediately on init

                // Auto-select if there's only one option and nothing is selected yet
                if (this.options.length === 1 && this.selectedItems.length === 0) {
                    this.selectedItems.push(String(this.options[0].id));
                    this.triggerChangeEvent();
                }
            },

            /**
             * Toggles the dropdown menu open/closed state
             */
            toggleDropdown() {
                this.open = !this.open;
                if (this.open) {
                    this.filterSelectedOptions();
                    document.getElementById('{{ $id }}').focus();
                }
            },

            /**
             * Handles option selection/deselection when clicked
             */
            toggleOption(optionId) {
                optionId = String(optionId); // Convert to string for consistent comparison

                if (this.isSelected(optionId)) {
                    this.removeItem(optionId);
                } else {
                    this.selectedItems.push(optionId);
                    this.filterSelectedOptions();
                }

                this.triggerChangeEvent();

                this.search = '';
                if (this.searchable) {
                    this.filterOptions();
                }
            },

            /**
             * Checks if an option is currently selected
             */
            isSelected(optionId) {
                return this.selectedItems.includes(String(optionId));
            },

            /**
             * Gets the display label for a selected option by ID
             */
            getOptionLabel(optionId) {
                const option = this.options.find(opt => String(opt.id) === String(optionId));
                return option ? option.name : '';
            },

            /**
             * Removes an item from the selected items
             */
            removeItem(itemId) {
                itemId = String(itemId);
                this.selectedItems = this.selectedItems.filter(id => id !== itemId);
                this.filterSelectedOptions();
                this.triggerChangeEvent();
            },

            /**
             * Filters options based on search query
             */
            filterOptions() {
                if (!this.search) {
                    this.filterSelectedOptions();
                    return;
                }

                const searchTerm = this.search.toLowerCase();
                this.filteredOptions = this.options.filter(option =>
                    option.name.toLowerCase().includes(searchTerm) &&
                    !this.isSelected(option.id)
                );
            },

            /**
             * Filters out already selected options from the dropdown
             */
            filterSelectedOptions() {
                this.filteredOptions = this.options.filter(
                    option => !this.isSelected(option.id)
                );
            },

            // The rest of the methods remain unchanged
            triggerChangeEvent() {
                const input = document.getElementById('{{ $id }}');
                if (input) {
                    setTimeout(() => {
                        const event = new Event('change', {
                            bubbles: true
                        });
                        input.dispatchEvent(event);
                    }, 100);
                }
            },

            updateOptions(newOptions) {
                if (typeof newOptions === 'string') {
                    try {
                        newOptions = JSON.parse(newOptions);
                    } catch (error) {
                        console.error('Failed to parse options JSON:', error);
                    }
                }
                this.options = Object.entries(newOptions || {}).map(([id, name]) => ({
                    id,
                    name
                }));
                this.selectedItems = [];
                this.filterSelectedOptions();
                this.search = '';

                if (this.options.length === 1) {
                    this.selectedItems.push(String(this.options[0].id));
                }
                this.triggerChangeEvent();
            },

            clearSelected() {
                this.selectedItems = [];
                this.filterSelectedOptions();
                this.triggerChangeEvent();
            },
        }));
    });
</script>
