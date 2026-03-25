@props(['slot_item', 'slot_tooltip', 'position' => 'auto', 'class' => ''])

<div x-data="tooltip('{{ $position }}')"
     x-init="init()"
     class="relative inline-block {{ $class }}"
     @mouseenter="!isMobile && handleInteraction($event)"
     @mouseleave="!isMobile && hideTooltip()"
     @click="isMobile && handleInteraction($event)"
     @touchstart.passive="isMobile && handleInteraction($event)">
    {{ $slot_item }}

    <template x-teleport="body">
        <div x-ref="tooltip"
             x-show="show"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             :style="style"
             :class="[tooltipClass, { 'invisible': isPositioning }]"
             class="z-[9999] relative pointer-events-auto"
             @mouseenter="enterTooltip()"
             @mouseleave="leaveTooltip()"
             @click.stop>
            <div class="p-3 rounded-lg border shadow-lg bg-white border-gray-200 text-gray-800 text-sm leading-relaxed
                        dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 w-fit">
                {{ $slot_tooltip }}

                <!-- Bouton fermer pour mobile -->
                <template x-if="isMobile">
                    <button @click="hide()"
                            class="absolute -top-2 -right-2 w-6 h-6 bg-gray-500 hover:bg-gray-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                        ×
                    </button>
                </template>
            </div>

            <!-- Flèche du tooltip -->
            <div class="absolute w-2 h-2 bg-white border dark:bg-gray-800 dark:border-gray-600 transform rotate-45"
                 :class="{
                     'tooltip-arrow-top': tooltipClass.includes('top'),
                     'tooltip-arrow-bottom': tooltipClass.includes('bottom'),
                     'tooltip-arrow-left': tooltipClass.includes('left'),
                     'tooltip-arrow-right': tooltipClass.includes('right')
                 }"></div>
        </div>
    </template>
</div>


