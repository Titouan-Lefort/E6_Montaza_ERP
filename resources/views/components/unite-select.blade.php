@props(['disabled' => false, 'required' => false, 'selected' => null, 'placeholder' => 'Sélectionner une unité'])

@php
    // Définir l'ordre souhaité des types
    $typeOrder = ['Unité', 'Longueur', 'Poids', 'Surface', 'Volume', 'Temps', 'Puissance'];

    // Récupérer et grouper les unités
    $unites = \App\Models\Unite::orderBy('type', 'ASC')->orderBy('short', 'ASC')->get()->groupBy('type');
@endphp

<select {{ $disabled ? 'disabled' : '' }} {{ $required ? 'required' : '' }}
    {!! $attributes->merge(['class' => 'bg-gray-100 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-xs select']) !!}>
    <option value="" disabled {{ !$selected ? 'selected' : '' }}>{{ $placeholder }}</option>

    @foreach ($typeOrder as $type)
        @if(isset($unites[$type]))
            <optgroup label="{{ ucfirst($type) }}">
                @foreach ($unites[$type] as $unite)
                    <option value="{{ $unite->id }}"
                            title="{{ $unite->full }}"
                            {{ $selected == $unite->id ? 'selected' : '' }}>
                        {{ $unite->short }}
                    </option>
                @endforeach
            </optgroup>
        @endif
    @endforeach

    {{-- Afficher les types non listés dans $typeOrder à la fin --}}
    @foreach ($unites as $type => $unitesParType)
        @if(!in_array($type, $typeOrder))
            <optgroup label="{{ ucfirst($type) }}">
                @foreach ($unitesParType as $unite)
                    <option value="{{ $unite->id }}"
                            title="{{ $unite->full }}"
                            {{ $selected == $unite->id ? 'selected' : '' }}>
                        {{ $unite->short }}
                    </option>
                @endforeach
            </optgroup>
        @endif
    @endforeach
</select>
