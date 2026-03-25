@props([
    'name',
    'show' => false,
    'position' => 'right', // Options: left, right, top, bottom
    'maxWidth' => '4xl',
    'maxHeight' => '4xl',
])

@php
$maxWidthClasses = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    'full' => 'sm:max-w-full',
][$maxWidth];

$maxHeightClasses = [
    'sm' => 'sm:max-h-sm',
    'md' => 'sm:max-h-md',
    'lg' => 'sm:max-h-lg',
    'xl' => 'sm:max-h-xl',
    '2xl' => 'sm:max-h-2xl',
    '3xl' => 'sm:max-h-3xl',
    '4xl' => 'sm:max-h-4xl',
    '5xl' => 'sm:max-h-5xl',
    'full' => 'sm:max-h-full',
][$maxHeight];

// Define positioning and animation based on the position prop
$positions = [
    'left' => [
        'panel' => 'fixed inset-y-0 left-0 h-full flex',
        'width' => $maxWidthClasses,
        'enter' => 'transform transition ease-in-out duration-300',
        'enterStart' => '-translate-x-full',
        'enterEnd' => 'translate-x-0',
        'leave' => 'transform transition ease-in-out duration-300',
        'leaveStart' => 'translate-x-0',
        'leaveEnd' => '-translate-x-full',
    ],
    'right' => [
        'panel' => 'fixed inset-y-0 right-0 h-full flex',
        'width' => $maxWidthClasses,
        'enter' => 'transform transition ease-in-out duration-300',
        'enterStart' => 'translate-x-full',
        'enterEnd' => 'translate-x-0',
        'leave' => 'transform transition ease-in-out duration-300',
        'leaveStart' => 'translate-x-0',
        'leaveEnd' => 'translate-x-full',
    ],
    'top' => [
        'panel' => 'fixed inset-x-0 top-0 w-full flex flex-col',
        'height' => $maxHeightClasses,
        'enter' => 'transform transition ease-in-out duration-300',
        'enterStart' => '-translate-y-full',
        'enterEnd' => 'translate-y-0',
        'leave' => 'transform transition ease-in-out duration-300',
        'leaveStart' => 'translate-y-0',
        'leaveEnd' => '-translate-y-full',
    ],
    'bottom' => [
        'panel' => 'fixed inset-x-0 bottom-0 w-full flex flex-col',
        'height' => $maxHeightClasses,
        'enter' => 'transform transition ease-in-out duration-300',
        'enterStart' => 'translate-y-full',
        'enterEnd' => 'translate-y-0',
        'leave' => 'transform transition ease-in-out duration-300',
        'leaveStart' => 'translate-y-0',
        'leaveEnd' => 'translate-y-full',
    ],
];

$positionClasses = $positions[$position] ?? $positions['right'];
@endphp

<div
    x-data="{
        show: @js($show),
        focusables() {
            // All focusable element types...
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...$el.querySelectorAll(selector)]
                // All non-disabled elements...
                .filter(el => ! el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
            {{ $attributes->has('focusable') ? 'setTimeout(() => firstFocusable().focus(), 100)' : '' }}
        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    })"
    x-on:open-volet.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-volet.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    x-show="show"
    class="fixed inset-0 overflow-hidden z-200"
    style="display: {{ $show ? 'block' : 'none' }};"
>
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-gray-500/50 dark:bg-gray-900/50 bg-opacity-75"></div>
    </div>

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 overflow-hidden">
            <div class="{{ $positionClasses['panel'] }} overflow-hidden pointer-events-auto">
                <div
                    x-show="show"
                    @if(isset($positionClasses['width']))
                    class="w-screen {{ $positionClasses['width'] }} bg-white dark:bg-gray-800 shadow-xl overflow-y-auto"
                    @else
                    class="h-screen {{ $positionClasses['height'] }} bg-white dark:bg-gray-800 shadow-xl overflow-y-auto"
                    @endif
                    x-transition:enter="{{ $positionClasses['enter'] }}"
                    x-transition:enter-start="{{ $positionClasses['enterStart'] }}"
                    x-transition:enter-end="{{ $positionClasses['enterEnd'] }}"
                    x-transition:leave="{{ $positionClasses['leave'] }}"
                    x-transition:leave-start="{{ $positionClasses['leaveStart'] }}"
                    x-transition:leave-end="{{ $positionClasses['leaveEnd'] }}"
                >
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
