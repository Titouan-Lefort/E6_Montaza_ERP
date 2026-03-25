 <x-app-layout>
    @section('title', 'Production')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {!! __('Suivi de Production') !!}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($affaires as $affaire)
                    <a href="{{ route('production.show', $affaire) }}" class="block group">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200 border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $affaire->statut_color }}-100 text-{{ $affaire->statut_color }}-800 dark:bg-{{ $affaire->statut_color }}-900 dark:text-{{ $affaire->statut_color }}-200">
                                            {{ $affaire->statut_label }}
                                        </span>
                                        <h3 class="mt-2 text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ $affaire->nom }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $affaire->code }}</p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500 dark:text-gray-400">Budget</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ number_format($affaire->budget, 2, ',', ' ') }} €</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500 dark:text-gray-400">Réalisé</span>
                                        <span class="font-medium {{ $affaire->total_ht > $affaire->budget ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format($affaire->total_ht, 2, ',', ' ') }} €
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>{{ $affaire->cdes->count() }} Commandes</span>
                                    <span>{{ $affaire->materiels->where('pivot.statut', '!=', 'termine')->count() }} Matériels</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>


