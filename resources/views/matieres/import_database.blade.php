<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Importer une base de données de matières (XLSX)
        </h2>
    </x-slot>
    <div class="max-w-4xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded shadow text-gray-800 dark:text-gray-200">
        <form action="{{ route('matieres.import.database.preview') }}" method="POST" enctype="multipart/form-data">
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

            <div class="mb-6 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="font-bold text-blue-800 dark:text-blue-200 mb-1">Information importante</h3>
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            Cette fonction permet d'importer une base de données complète de matières depuis un fichier Excel (XLSX).
                            Après l'upload, vous pourrez prévisualiser les données et voir les éventuelles erreurs avant l'import définitif.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-semibold text-lg">Fichier XLSX</label>
                <x-dropzone-input id="file" name="file" required='true' accept=".xlsx,.xls" />
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Formats acceptés : .xlsx, .xls
                </p>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="btn">
                    <x-icon type="upload" class="mr-1" size="1" />
                    Prévisualiser l'import
                </button>
                <a href="{{ route('matieres.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>

        <div class="mt-8 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-inner border border-gray-200 dark:border-gray-600">
            <h3 class="font-bold text-lg mb-3 text-gray-800 dark:text-gray-100 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/>
                </svg>
                Format attendu du fichier XLSX
            </h3>
            <ul class="list-disc pl-6 space-y-2 text-gray-700 dark:text-gray-200">
                <li>
                    <span class="font-semibold">Structure du fichier :</span>
                    <ul class="list-circle pl-6 mt-1 space-y-1 text-sm">
                        <li><strong>Lignes 1-4 :</strong> Ignorées (informations complémentaires)</li>
                        <li><strong>Ligne 5 :</strong> En-têtes de colonnes</li>
                        <li><strong>Ligne 6 et suivantes :</strong> Données des matières</li>
                    </ul>
                </li>
                <li>
                    <span class="font-semibold">Colonnes reconnues :</span>
                    <ul class="list-circle pl-6 mt-1 space-y-1 text-sm">
                        <li><strong>ref interne</strong> (obligatoire) : Référence interne de la matière</li>
                        <li><strong>designation</strong> (obligatoire) : Désignation de la matière</li>
                        <li><strong>unite</strong> (obligatoire) : Unité de mesure (ml, u, kg, etc.)</li>
                        <li><strong>famille</strong> ou <strong>sous_famille</strong> (obligatoire) : Famille/Sous-famille</li>
                        <li><strong>matiere/materiau</strong> : Matériau (Acier, Inox, PE, etc.)</li>
                        <li><strong>standard/standars</strong> : Standard technique</li>
                        <li><strong>DN</strong> : Diamètre nominal</li>
                        <li><strong>ep/epaisseur</strong> : Épaisseur</li>
                        <li><strong>longueur</strong> : Conditionnement/valeur unitaire</li>
                        <li><strong>fournisseur</strong> : Nom du fournisseur</li>
                        <li><strong>prix</strong> : Prix unitaire (si fournisseur spécifié)</li>
                    </ul>
                </li>
                <li>
                    <span class="font-semibold">Traitement :</span>
                    <ul class="list-circle pl-6 mt-1 space-y-1 text-sm">
                        <li>Les matières avec une référence déjà existante seront ignorées</li>
                        <li>Les lignes avec des données invalides seront ignorées</li>
                        <li>Un rapport détaillé sera affiché à la fin de l'import</li>
                    </ul>
                </li>
                <li>
                    <span class="font-semibold">Correspondances automatiques :</span>
                    <span class="text-sm">Le système détecte automatiquement les unités, familles, matériaux, standards et fournisseurs existants</span>
                </li>
            </ul>
        </div>
    </div>
</x-app-layout>
