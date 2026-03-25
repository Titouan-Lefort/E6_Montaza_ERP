{{-- <svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <path d="M158 290.5s-93.5-54.5-93.5-136.5c0-41.5 33.5-75 75-75 20.5 0 38.5 8.5 51 22 12.5-13.5 30.5-22 51-22 41.5 0 75 33.5 75 75 0 82-93.5 136.5-93.5 136.5z" fill="currentColor"/>

</svg> --}}
<img src="{{ asset(\App\Models\Entite::where('id', '1')->value('logo')) }}" alt="logo" {{ $attributes }} />

