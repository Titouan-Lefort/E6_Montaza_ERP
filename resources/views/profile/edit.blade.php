<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mon Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8 px-4 sm:px-0 flex items-center justify-between">
                <div>
                     <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="p-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        {{ $user->first_name }} {{ $user->last_name }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2 ml-14">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                             {{ $user->role->name }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 px-4 sm:px-0">
                <!-- Left Column: Identity & Info -->
                <div class="space-y-8">
                    <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <!-- Right Column: Security & Danger Zone -->
                <div class="space-y-8">
                    <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                        <div class="max-w-xl">
                             @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    @if (Auth::user()->role_id == '1' && Auth::user()->id != $user->id)
                        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-xl shadow-lg border border-red-100 dark:border-red-900/30 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4 opacity-10">
                                <svg class="w-24 h-24 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="max-w-xl relative z-10">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
