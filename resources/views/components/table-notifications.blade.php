<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <tbody @isset($scrollInfini)
    id="notification-list-{{ $tab }}"
    @endisset
        class="divide-y divide-gray-200 dark:divide-gray-700">
        @if ($notifications && $notifications->count() > 0)
            @php
                $firstNotificationreaded = $notifications->first()->read ?? null;
            @endphp
            @if (!$firstNotificationreaded)
                @if (!isset($specifyType))
                    @php
                        $firstNotificationType = $notifications->first()->type ?? null;
                    @endphp

                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                            <div class="flex items center justify-center">
                                <form action="{{ route('notifications.luall') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ $firstNotificationType }}">
                                    <button type="submit" class="btn">
                                        Marquer tout comme lu
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                            <div class="flex items
                            center justify-center">
                                <form action="{{ route('notifications.luall') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn">
                                        Marquer tout comme lu
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endif
            @endif

            @include('notifications.partials._notifications', $notifications)
        @else
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                    <div class="flex items-center justify-center">
                        <p class="text-gray-500 dark:text-gray-400">Aucune nouvelle notification.</p>
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
</table>
@isset($scrollInfini)
    <div id="loading-message" style="display: none;">
        <p>Chargement des notifications...</p>
    </div>
@endisset
