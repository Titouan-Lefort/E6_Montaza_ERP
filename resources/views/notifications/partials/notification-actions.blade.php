@php
    $random_number = rand(1, 1000);
@endphp
<div class="flex items-center lg:flex-col">
    @if (!isset($no_redirect))
        <a type="button" class="btn-select-top-left"
            href="{{ route('notifications.detail', ['id' => $notification->id]) }}" target="_blank">
            <x-icon type="open_in_new" size="1" class=" icons-no_hover" />
        </a>
    @endif
    <button type="button" class="btn-select-square" title="transférer" x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'transfer-notif-modal-{{ $notification->id }}-{{ $random_number }}')">
        <x-icon type="send" size="1" class="icons-no_hover" />
    </button>
    @if (!$notification->read)
        <button type="button" class="btn-select-bottom-right" onclick="marquerCommeLu({{ $notification->id }})"
            title="Marquer comme Lu">
            <x-icon type="read" size="1" class="icons-no_hover" />
        </button>
    @else
        @if (!isset($no_redirect))
            <button type="button" class="btn-select-bottom-right" onclick="marquerCommeNonLu({{ $notification->id }})"
                title="Marquer comme non Lu">
                <x-icon type="unread" size="1" class="icons-no_hover" />
            </button>
        @else
            <form method="POST" action="{{ route('notifications.nonlu', ['id' => $notification->id]) }}"
                class="inline" title="Marquer comme non Lu">
                @csrf
                <button type="submit" class="btn-select-bottom-right" title="Marquer comme non-lu">
                    <x-icon type="unread" size="1" class="icons-no_hover" />
                </button>
            </form>
        @endif
    @endif
</div>

    <x-modal name="transfer-notif-modal-{{ $notification->id }}-{{ $random_number }}" focusable :show="old('role_id_notif')">
        <form method="POST" action="{{ route('notifications.transfer') }}" x-show="show" class="p-6">
            @csrf
            <div class="p-8">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Transférer les notifications') }}
                </h2>
                <div class="mt-4">
                    <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                    <x-input-label for="role_id_notif" :value="__('Sélectionner le rôle à qui transférer')" />
                    <x-select_id_role :entites="$_entites" :selected_role="$notification->role->id" class="select"
                        name="role_id_notif" id="role_id_notif" />
                    </select>
                    <x-input-error :messages="$errors->get('role_id_notif')" class="mt-2" />
                </div>
                <div class="mt-4 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Annuler') }}
                    </x-secondary-button>
                    <x-primary-button class="ml-3">
                        {{ __('Transférer') }}
                    </x-primary-button>
                </div>
            </div>
        </form>
    </x-modal>
