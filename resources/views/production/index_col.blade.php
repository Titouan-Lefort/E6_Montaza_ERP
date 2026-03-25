<div class="bg-white dark:bg-gray-800 flex flex-col p-4 text-gray-800 dark:text-gray-200 h-full">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold mb-1">
            <a href="{{ route('production.index') }}" class="hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                {{ __('Production') }}
            </a>
        </h1>
        <div>
            <a href="{{ route('production.index') }}" class="btn mb-2">Voir tout</a>
        </div>
    </div>

    <p class="text-lg mb-2">
        Affaires en cours
    </p>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr>
                <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">Code</th>
                <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">Nom</th>
                <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($affaires as $affaire)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" onclick="window.location='{{ route('production.show', $affaire) }}'">
                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">{{ $affaire->code }}</td>
                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">{{ $affaire->nom }}</td>
                    <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white" style="background-color: {{ $affaire->statut_color }};">
                            {{ $affaire->statut_label }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
