<a href="{{ url()->previous() }}" {{ $attributes->merge(['class' => 'btn']) }}>
    <x-icon size="1" type="arrow_back" class="fill-gray-500 dark:fill-gray-300" />
    {{ __(' Retour') }}
</a>
