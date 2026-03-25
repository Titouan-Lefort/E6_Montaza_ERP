<x-app-layout>
    @php
        $data = json_decode($notification->data, true);
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notification') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ Auth::user()->role->name }}
        </p>
        <span
            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
            Système
        </span>
        <span
            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">

            {{ $notification->created_at->format('d/m/Y H:i') }}
        </span>
        <small class="text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}
        </small>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Détails de la notification') }}
                    </h3>
                    <div class="flex flex-col sm:flex-col md:flex-col lg:flex-row justify-between">

                        <div class="mt-4">
                            <div class="notification-content">
                                <h2 class="text-lg font-semibold text-wrap text-gray-800 dark:text-gray-200">{!! $data['title'] ?? 'N/A' !!}</h2>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 text-wrap">
                                    {!! $data['message'] ?? 'N/A' !!}
                                </p>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {!! $data['action_requise'] ?? 'Aucune action requise' !!}
                                </p>
                                @if (isset($data['action'], $data['action']['route_nom'],$data['action']['route_data'], $data['action']['label']))
                                    <a href="{{ route($data['action']['route_nom'],$data['action']['route_data']) }}"
                                        class="btn">
                                        {!! $data['action']['label'] !!}
                                    </a>

                                @endif
                            </div>
                        </div>
                        @include('notifications.partials.notification-actions', [
                            'notification' => $notification,
                            'no_redirect' => true
                        ])

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
