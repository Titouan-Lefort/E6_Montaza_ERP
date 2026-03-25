@props(['column', 'route' => '', 'class' => 'px-4 py-2'])

<th class="{{ $class }}">
    <a href="{{ route($route, array_merge(request()->query(), ['sort' => $column, 'direction' => request('sort') == $column && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
       class="flex items-center text-gray-900 dark:text-gray-100 hover:text-gray-600 dark:hover:text-gray-300">
        {{ $slot }}
        @if(request('sort') == $column)
            @if(request('direction') == 'asc')
                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 10l5-5 5 5H5z"/>
                </svg>
            @else
                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M15 10l-5 5-5-5h10z"/>
                </svg>
            @endif
        @else
            <svg class="w-4 h-4 ml-1 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                <path d="M7 10l3-3 3 3H7z"/>
            </svg>
        @endif
    </a>
</th>
