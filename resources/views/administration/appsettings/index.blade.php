<x-app-layout>
    @section('title', 'Paramètres de l\'application')

    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('administration.index') }}"
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                {{ __('Administration') }}
            </a>
            <span class="text-xl text-gray-800 dark:text-gray-200">></span>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                {{ __('Paramètres de l\'application') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <form action="{{ route('administration.appsettings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-6">
                    {{ __('Paramètres de messagerie') }}
                </h2>

                <!-- Expéditeur -->
                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">
                        {{ __('Informations de l\'expéditeur') }}
                    </h3>
                    <div class="flex gap-4">
                        <div class="mb-4">
                            <x-input-label for="mail_from_address" value="Adresse Mail de l'expéditeur" />
                            <div class="flex items-center">
                                <x-text-input id="mail_from_address" type="email" name="mail_from_address"
                                    value="{{ old('mail_from_address', $settings->mail_from_address) }}" required
                                    placeholder="support@montaza.com" />
                                <x-tooltip position="right">
                                    <x-slot name="slot_item">
                                        <x-icons.question class="icons" size="1" />
                                    </x-slot>
                                    <x-slot name="slot_tooltip">
                                        <p class="text-sm font-bold">Adresse e-mail utilisée comme expéditeur par défaut.
                                        </p>
                                        <p class="text-sm">Exemple : support@montaza.com.</p>
                                    </x-slot>
                                </x-tooltip>
                            </div>
                            @error('mail_from_address')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <x-input-label for="mail_from_name" value="Nom de l'expéditeur" />
                            <div class="flex items-center">
                                <x-text-input id="mail_from_name" type="text" name="mail_from_name"
                                    value="{{ old('mail_from_name', $settings->mail_from_name) }}" required
                                    placeholder="Service Client Atlantis Montaza" />
                                <x-tooltip position="right">
                                    <x-slot name="slot_item">
                                        <x-icons.question class="icons" size="1" />
                                    </x-slot>
                                    <x-slot name="slot_tooltip">
                                        <p class="text-sm font-bold">Nom visible de l’expéditeur dans les e-mails.</p>
                                        <p class="text-sm">Exemple : Service Client Atlantis Montaza.</p>
                                    </x-slot>
                                </x-tooltip>
                            </div>
                            @error('mail_from_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Configuration SMTP -->
                <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">
                        {{ __('Configuration SMTP') }}
                    </h3>
                    <div class="flex gap-4">

                        <div class="mb-4">
                            <x-input-label for="mail_host" value="Hôte SMTP" />
                            <div class="flex items-center">
                                <x-text-input id="mail_host" type="text" name="mail_host"
                                    value="{{ old('mail_host', $settings->mail_host) }}" required
                                    placeholder="smtp.gmail.com" />
                                <x-tooltip position="right">
                                    <x-slot name="slot_item">
                                        <x-icons.question class="icons" size="1" />
                                    </x-slot>
                                    <x-slot name="slot_tooltip">
                                        <p class="text-sm font-bold">Serveur SMTP utilisé pour l'envoi des e-mails.</p>
                                        <p class="text-sm">Exemple : smtp.gmail.com.</p>
                                    </x-slot>
                                </x-tooltip>
                            </div>
                            @error('mail_host')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <x-input-label for="mail_port" value="Port SMTP" />
                            <div class="flex items-center">
                                <x-text-input id="mail_port" type="number" name="mail_port"
                                    value="{{ old('mail_port', $settings->mail_port) }}" required
                                    placeholder="587" />
                                <x-tooltip position="right">
                                    <x-slot name="slot_item">
                                        <x-icons.question class="icons" size="1" />
                                    </x-slot>
                                    <x-slot name="slot_tooltip">
                                        <p class="text-sm font-bold">Port utilisé pour le serveur SMTP.</p>
                                        <p class="text-sm">Exemples : 587 (TLS), 465 (SSL).</p>
                                    </x-slot>
                                </x-tooltip>
                            </div>
                            @error('mail_port')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex gap-4">

                        <div class="mb-4">
                            <x-input-label for="mail_username" value="Nom d'utilisateur SMTP" />
                            <div class="flex items-center">
                                <x-text-input id="mail_username" type="text" name="mail_username"
                                    value="{{ old('mail_username', $settings->mail_username) }}" required
                                    placeholder="utilisateur@gmail.com" />
                                <x-tooltip position="right">
                                    <x-slot name="slot_item">
                                        <x-icons.question class="icons" size="1" />
                                    </x-slot>
                                    <x-slot name="slot_tooltip">
                                        <p class="text-sm font-bold">Nom d'utilisateur pour s’authentifier auprès du serveur
                                            SMTP.</p>
                                        <p class="text-sm">Souvent l'adresse e-mail utilisée.</p>
                                    </x-slot>
                                </x-tooltip>
                            </div>
                            @error('mail_username')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <x-input-label for="mail_password" value="Mot de passe SMTP" />
                            <div class="flex items-center">
                                <x-text-input id="mail_password" type="password" name="mail_password"
                                    value="{{ old('mail_password', $settings->mail_password) }}" required
                                    placeholder="Mot de passe SMTP" />
                                <x-tooltip position="right">
                                    <x-slot name="slot_item">
                                        <x-icons.question class="icons" size="1" />
                                    </x-slot>
                                    <x-slot name="slot_tooltip">
                                        <p class="text-sm font-bold">Mot de passe ou token pour s’authentifier sur le serveur
                                            SMTP.</p>
                                        <p class="text-sm">Exemple : un mot de passe ou un token généré.</p>
                                    </x-slot>
                                </x-tooltip>
                            </div>
                            @error('mail_password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="mt-6 text-right">
                    <button type="submit" class="btn btn-primary">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
