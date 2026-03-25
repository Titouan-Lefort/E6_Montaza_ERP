<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Importer des matières par CSV
        </h2>
    </x-slot>
    <div class="max-w-4xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded shadow text-gray-800 dark:text-gray-200">
        <form action="{{ route('matieres.import.preview') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($errors->any())
                <div class="mb-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="mb-4">
                <label class="block mb-2 font-semibold">Fichier CSV</label>
                <x-dropzone-input id="file" name="file" required='true' accept=".csv" />
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-semibold">Fournisseur (optionnel)</label>
                <select name="fournisseur_id" class="border rounded p-2 w-full select">
                    <option value="">Aucun</option>
                    @foreach(\App\Models\Societe::whereIn('societe_type_id', ['2','3'])->get() as $fournisseur)
                        <option value="{{ $fournisseur->id }}">{{ $fournisseur->raison_sociale }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn">Prévisualiser l'import</button>
            <a href="{{ route('matieres.import.example') }}" class="btn btn-secondary ml-2">Télécharger un exemple de CSV</a>
        </form>
        <div class="mt-8 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-inner border border-gray-200 dark:border-gray-600">
            <h3 class="font-bold text-lg mb-3 text-gray-800 dark:text-gray-100 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>
            Informations sur le format CSV
            </h3>
            <ul class="list-disc pl-6 space-y-1 text-gray-700 dark:text-gray-200">
            <li>
                <span class="font-semibold">Champs acceptés :</span>
                <span class="italic">ref_interne, designation, unite, sous_famille, dn, epaisseur, standard, ref_valeur_unitaire, material, <b>prix (optionnel)</b></span>
            </li>
            <li>
                Le séparateur du CSV doit être <span class="font-semibold text-blue-600 dark:text-blue-300">;</span>.
                <span class="italic">Ouvrez le fichier exemple pour voir le format exact.</span>
            </li>
            <li>
                <span class="font-semibold">Obligatoire :</span>
                <span class="italic">ref_interne</span> ou <span class="italic">designation</span> ou <span class="italic">unite</span>.
                Les autres champs sont optionnels.
            </li>
            <li>
                Le fournisseur est appliqué à toutes les matières importées si renseignés,
                la colonne <span class="font-semibold">prix</span> est présente dans le CSV et est utilisé seulement si un fournisseur est sélectionné.
            </li>
            </ul>
        </div>
    </div>
</x-app-layout>
