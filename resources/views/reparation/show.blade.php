<x-app-layout>
    @section('title', 'Détails de la réparation')
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Détails de la réparation</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('reparation.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Retour
                </a>
                @if((Auth::id() === $reparation->user_id || Auth::user()->hasPermission('gerer_les_reparations')) && $reparation->status !== 'archived' && $reparation->status !== 'closed')
                    <a href="{{ route('reparation.edit', $reparation->id) }}" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="px-6 py-6 sm:p-8">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Réparation #{{ $reparation->id }}</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Demande créée le {{ $reparation->created_at->format('d/m/Y H:i') }} par {{ $reparation->user->name ?? '—' }}</p>

                    <div class="mt-6 grid grid-cols-1 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Matériel</h4>
                            <div class="mt-2 text-sm text-gray-900 dark:text-gray-100">
                                <div class="font-medium">{{ $reparation->materiel->reference ?? '-' }} — {{ $reparation->materiel->designation ?? '-' }}</div>
                                <div class="text-gray-500 dark:text-gray-400 mt-1">{{ $reparation->materiel->description ?? '-' }}</div>
                                <div class="mt-2 text-xs font-mono text-gray-600 dark:text-gray-300">S/N: {{ $reparation->materiel->numero_serie ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Description du problème</h4>
                            <div class="mt-2 text-sm text-gray-900 dark:text-gray-100">{{ $reparation->description }}</div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Statut</h4>
                                    @php
                                        $statusClass = match($reparation->status) {
                                            'pending' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            'in_progress' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                            'completed' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'archived' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                            'closed' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                            default => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                                        };
                                    @endphp
                                    <div class="mt-2">
                                        <span class="{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $reparation->status)) }}</span>
                                    </div>
                                </div>

                                <div class="text-sm text-gray-500 dark:text-gray-400 text-right">
                                    <div>Demandé par</div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $reparation->user->name ?? '-' }}</div>
                                    <div class="text-xs">{{ $reparation->user->email ?? '' }}</div>
                                </div>
                            </div>

                            @if((Auth::id() === $reparation->user_id || Auth::user()->hasPermission('gerer_les_reparations')) && $reparation->status !== 'archived' && $reparation->status !== 'closed')
                                <div class="mt-4 pt-4 border-t border-gray-300 dark:border-gray-600">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <form method="POST" action="{{ route('reparation.updateStatus', $reparation->id) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-100">
                                                <option value="pending" {{ $reparation->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                                <option value="in_progress" {{ $reparation->status === 'in_progress' ? 'selected' : '' }}>En cours</option>
                                                <option value="completed" {{ $reparation->status === 'completed' ? 'selected' : '' }}>Terminé (auto-archivé)</option>
                                                <option value="closed" {{ $reparation->status === 'closed' ? 'selected' : '' }}>Clos</option>
                                            </select>
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium">Changer</button>
                                        </form>

                                        <form method="POST" action="{{ route('reparation.archive', $reparation->id) }}" onsubmit="return confirm('Archiver cette réparation ?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-3 3v6m4-6v6" />
                                                </svg>
                                                Archiver
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
