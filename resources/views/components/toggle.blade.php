@props(
[
    'id' => '',
    'name' => '',
    'class' => '',
    'onchange' => '',
    'label' => '',
    'checked' => '',
    'disabled' => '',
  ])

<label for="{{ $id }}"
    class="inline-flex items-center space-x-4 cursor-pointer dark:text-gray-100 text-gray-800 {{ $class }}">
    <span class="whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $label }}</span>
    <span class="relative">
        <input id="{{ $id }}" name="{{ $name }}" type="checkbox" class="hidden peer" onchange="{{ $onchange }}"
            {{ $checked ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }} />
        <div
            class="w-10 h-6 rounded-full shadow-inner bg-gray-400 dark:bg-gray-600 peer-checked:bg-violet-400 dark:peer-checked:bg-violet-600">
        </div>
        <div
            class="absolute inset-y-0 left-0 w-4 h-4 m-1 rounded-full shadow-sm peer-checked:right-0 peer-checked:left-auto bg-gray-800 dark:bg-gray-100">
        </div>
    </span>
</label>
