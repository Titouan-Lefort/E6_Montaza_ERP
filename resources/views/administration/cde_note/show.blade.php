<x-app-layout>
    @section('title', 'Modifier la Note de Commande')

    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('administration.index') }}"
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                {{ __('Administration') }}
            </a>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                >>
            </h2>
            <a href="{{ route('administration.cdeNote.index', $entite) }}"
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                {{ __('Notes de commande') }}
            </a>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-sm">
                >>
            </h2>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Modifier la Note') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div
            class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-medium text-lg mb-4">Modifier la Note de Commande</h3>
                <button x-data x-on:click="$dispatch('open-modal', 'confirm-delete')" class="btn bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-sm">
                    Supprimer
                </button>
                <x-modal name="confirm-delete" :show="$errors->any()">
                    <div class="p-4">
                        <a x-on:click="$dispatch('close')">
                            <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
                        </a>
                        <h2 class="text-xl font-semibold mb-4">Voulez-vous vraiment Supprimer cette note ?</h2>
                        @if ($cde_note->cdes)
                            <p class="mb-4">Cette note sera supprimée de toutes ces commandes :</p>
                            <table class="table-auto w-full mb-4">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left">Code</th>
                                        <th class="px-4 py-2 text-left">Nom</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cde_note->cdes as $cde)
                                        <tr>
                                            <td class="border px-4 py-2">{{ $cde->code }}</td>
                                            <td class="border px-4 py-2">{{ $cde->nom }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                        <p class="mb-4">Cette action est irréversible.</p>
                        <div class="flex justify-end gap-4">
                            <button x-on:click="$dispatch('close')"
                                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-sm">
                                Annuler
                            </button>
                            <form action="{{ route('administration.cdeNote.destroy', $cde_note->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-sm">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </x-modal>
            </div>
            <form action="{{ route('administration.cdeNote.update', $cde_note->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label for="contenu"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contenu</label>
                    <textarea id="contenu" name="contenu" rows="8" class="textarea">{{ old('contenu', $cde_note->contenu) }}</textarea>
                    @error('contenu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="entite_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Entité</label>
                    <select id="entite_id" name="entite_id" class="select">
                        @foreach ($entites as $entit)
                            <option value="{{ $entit->id }}"
                                {{ $entit->id == $cde_note->entite_id ? 'selected' : '' }}>
                                {{ $entit->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('entite_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <x-toggle id="is_checked" name="is_checked" label=" Précocher la note de commande"
                        checked="{{ $cde_note->is_checked ? 'checked' : '' }}" />
                    @error('is_checked')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between mt-4">
                    <a href="{{ route('administration.cdeNote.index', $entite) }}" class="btn">Retour</a>
                    <button type="submit" class="btn">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
