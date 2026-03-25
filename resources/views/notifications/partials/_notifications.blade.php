@foreach ($notifications as $notification)
    <tr id="{{ 'notification-' . $notification->id }}" class="{{ $notification->type }}">
        <td>
            <div class="flex flex-col sm:flex-col md:flex-col lg:flex-row justify-between">
                <div class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                    @php
                        $data = json_decode($notification->data, true);
                    @endphp
                    <div>
                        <div class="notification-content">
                            @if (isset($specifyType) && $specifyType)
                                @if ($notification->type == 'system')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        Système
                                    </span>
                                @endif
                                @if ($notification->type == 'stock')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        Stock
                                    </span>
                                @endif
                            @endif
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">

                                {{ $notification->created_at->format('d/m/Y H:i') }}
                            </span>
                            <small
                                class="text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}
                            </small>

                            <h3 class="text-lg font-semibold text-wrap">{{ $data['title'] ?? 'N/A' }}</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 text-wrap">
                                {!! $data['message'] ?? 'N/A' !!}
                            </p>
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ $data['action_requise'] ?? '' }}

                            </p>
                            @if (isset($data['action'], $data['action']['route_nom'],$data['action']['route_data'], $data['action']['label']))
                                    <a href="{{ route($data['action']['route_nom'],$data['action']['route_data']) }}"
                                        class="btn">
                                        {!! $data['action']['label'] !!}
                                    </a>

                                @endif
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                    @include('notifications.partials.notification-actions', ['notification' => $notification])
                </div>
        </td>
    </tr>
    </tr>
@endforeach
