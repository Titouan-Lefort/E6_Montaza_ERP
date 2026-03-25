{{-- filepath: c:\Users\prepaetude\Homestead\code\montaza\resources\views\components\opt.blade.php --}}
@props([
    'value' => '',
    'selected' => false,
    'disabled' => false,
])

<div data-option
     data-value="{{ $value }}"
     @if($selected) data-selected @endif
     @if($disabled) data-disabled @endif>
    {{ $slot }}
</div>
