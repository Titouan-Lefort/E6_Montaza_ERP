<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn bg-red-600 hover:bg-red-500 active:bg-red-700 text-gray-100 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
