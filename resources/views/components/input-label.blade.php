@props(['value', 'optionnel' => false, 'help' => null])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700 dark:text-gray-300 flex items-center gap-2']) }}>
    <span>{{ $value ?? $slot }}</span>

    @if ($optionnel)
        <small class="text-gray-500 font-normal">(Optionnel)</small>
    @endif

    @if($help)
        <x-help-icon :text="$help" />
    @endif
</label>

