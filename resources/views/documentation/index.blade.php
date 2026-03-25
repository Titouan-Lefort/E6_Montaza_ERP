<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Documentation Utilisateur') }}
            </h2>
            <div class="flex items-center space-x-3 mt-3 sm:mt-0">
                <a href="{{ route('documentation.download', 'pdf') }}"
                   class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V13"></path>
                    </svg>
                    {{ __('PDF') }}
                </a>
                <a href="{{ route('documentation.download', 'docx') }}"
                   class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V13"></path>
                    </svg>
                    {{ __('DOCX') }}
                </a>
            </div>
        </div>
    </x-slot>
    <!-- Nouveau sommaire épuré -->
    <div id="sidebar" class="fixed left-0 top-20 bottom-0 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 z-30 md:block transition-transform duration-300">
        <div class="h-full flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Sommaire</h3>
                    <button id="hide-sidebar" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                </div>
                <!-- Barre de recherche -->
                <div class="relative">
                    <input type="text"
                           id="toc-search"
                           placeholder="Rechercher dans le sommaire..."
                           class="w-full px-3 py-2 pl-9 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <button id="clear-search" class="absolute right-2 top-2 p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 hidden">
                        <svg class="w-3 h-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <!-- Compteur de résultats -->
                <div id="search-results" class="text-xs text-gray-500 dark:text-gray-400 mt-2 hidden"></div>
            </div>
            <div class="flex-1 overflow-y-auto p-4">
                <nav id="toc"></nav>
                <!-- Message quand aucun résultat -->
                <div id="no-results" class="text-center py-8 hidden">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Aucun titre trouvé</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton "Retour en haut" -->
    <button id="back-to-top" class="fixed bottom-4 right-4 bg-gray-700 hover:bg-gray-900 dark:bg-gray-600 dark:hover:bg-gray-800 text-white p-3 rounded-full shadow-lg z-30 hidden" title="Retour en haut">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
        </svg>
    </button>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const backToTop = document.getElementById('back-to-top');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTop.classList.remove('hidden');
                } else {
                    backToTop.classList.add('hidden');
                }
            });
            backToTop.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>

    <!-- Bouton pour retourner tout en haut -->
    <button id="mobile-menu" class="fixed bottom-4 right-4 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white p-3 rounded-full shadow-lg z-30 md:hidden">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"></path>
        </svg>
    </button>

    <!-- Modal mobile -->
    <div id="mobile-overlay" class="fixed inset-0 z-40 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="absolute right-0 top-0 h-full w-72 bg-white dark:bg-gray-900 shadow-xl">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Navigation</h3>
                    <button id="close-mobile" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <!-- Barre de recherche mobile -->
                <div class="relative">
                    <input type="text"
                           id="mobile-toc-search"
                           placeholder="Rechercher..."
                           class="w-full px-3 py-2 pl-9 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div id="mobile-search-results" class="text-xs text-gray-500 dark:text-gray-400 mt-2 hidden"></div>
            </div>
            <div class="p-4 overflow-y-auto h-full">
                <nav id="mobile-toc"></nav>
                <div id="mobile-no-results" class="text-center py-8 hidden">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Aucun titre trouvé</p>
                </div>
            </div>
        </div>
    </div>

    <div id="content" class="md:ml-64 transition-all duration-300">
        <div class="py-6">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                @if($hasError)
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg font-medium text-red-800 dark:text-red-200">{{ __('Documentation non disponible') }}</h3>
                                <p class="text-red-700 dark:text-red-300 mt-1">{{ __('Le fichier de documentation n\'a pas pu être trouvé ou chargé.') }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <!-- Entête de la documentation -->
                        <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ __('Documentation Utilisateur') }}
                                    <small class="ml-2 text-gray-500 dark:text-gray-400 text-xs font-normal align-middle">
                                        version du 2 Mai 2025
                                    </small>
                                </h3>
                            </div>
                        </div>

                        <!-- Contenu de la documentation -->
                        <div class="documentation-content p-6 text-gray-800 dark:text-gray-200">
                            {!! $documentationContent !!}
                        </div>

                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .documentation-content {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.7;
            font-size: 16px;
        }
        .dark .documentation-content {
            background-color: #111827;
        }



        /* Images */
        .documentation-content img {
            max-width: 100%;
            height: auto;
            border: 1px solid #e5e7eb;
            margin: 24px 0;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        .documentation-content img:hover {
            transform: scale(1.02);
        }

        .dark .documentation-content img {
            border-color: #4b5563;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }

        /* Tableaux */
        .documentation-content table {
            border-collapse: collapse;
            width: 100%;
            margin: 24px 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .documentation-content th,
        .documentation-content td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .documentation-content th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }



        .dark .documentation-content th {
            background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
        }

        .dark .documentation-content th,
        .dark .documentation-content td {
            border-bottom-color: #4b5563;
        }



        /* Titres */
        .documentation-content h1,
        .documentation-content h2,
        .documentation-content h3,
        .documentation-content h4,
        .documentation-content h5,
        .documentation-content h6 {
            margin-top: 2em;
            margin-bottom: 0.75em;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .documentation-content h1 {
            font-size: 2.25em;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 0.5em;
            margin-bottom: 1em;
        }

        .documentation-content h2 {
            font-size: 1.875em;
            color: #1e40af;
        }

        .documentation-content h3 {
            font-size: 1.5em;
            color: #1d4ed8;
        }

        .documentation-content h4 {
            font-size: 1.25em;
            color: #2563eb;
        }


        .dark .documentation-content h1 {
            border-bottom-color: #60a5fa;
        }

        .dark .documentation-content h2 {
            color: #93c5fd;
        }

        .dark .documentation-content h3 {
            color: #60a5fa;
        }

        .dark .documentation-content h4 {
            color: #3b82f6;
        }

        /* Liens */
        .documentation-content a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px solid transparent;
            transition: all 0.2s ease-in-out;
        }

        .documentation-content a:hover {
            color: #1d4ed8;
            border-bottom-color: #2563eb;
        }

        .dark .documentation-content a {
            color: #60a5fa;
        }

        .dark .documentation-content a:hover {
            color: #93c5fd;
            border-bottom-color: #60a5fa;
        }

        /* Code */
        .documentation-content pre,
        .documentation-content code {
            font-family: 'JetBrains Mono', 'Fira Code', Consolas, monospace;
            font-size: 14px;
        }

        .documentation-content code {
            background-color: #f1f5f9;
            color: #e11d48;
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: 500;
        }

        .documentation-content pre {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 24px 0;
            position: relative;
        }

        .documentation-content pre code {
            background: none;
            color: #334155;
            padding: 0;
            font-weight: normal;
        }

        .dark .documentation-content code {
            background-color: #475569;
            color: #fbbf24;
        }

        .dark .documentation-content pre {
            background-color: #1e293b;
            border-color: #475569;
        }

        .dark .documentation-content pre code {
            color: #e2e8f0;
        }

        /* Citations */
        .documentation-content blockquote {
            border-left: 4px solid #3b82f6;
            margin: 24px 0;
            padding: 16px 24px;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-radius: 0 8px 8px 0;
            color: #1e40af;
            font-style: italic;
            position: relative;
        }

        .documentation-content blockquote::before {
            content: '"';
            font-size: 4em;
            color: #3b82f6;
            position: absolute;
            top: -10px;
            left: 10px;
            opacity: 0.3;
        }

        .dark .documentation-content blockquote {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            border-left-color: #60a5fa;
            color: #dbeafe;
        }

        .dark .documentation-content blockquote::before {
            color: #60a5fa;
        }

        /* Listes */
        .documentation-content ul,
        .documentation-content ol {
            padding-left: 28px;
            margin: 16px 0;
        }

        .documentation-content li {
            margin: 8px 0;
            line-height: 1.6;
        }

        .documentation-content ul li::marker {
            color: #3b82f6;
        }

        .documentation-content ol li::marker {
            color: #3b82f6;
            font-weight: 600;
        }

        /* Paragraphes */
        .documentation-content p {
            margin: 16px 0;
            text-align: justify;
        }

        /* Adaptation responsive */
        @media (max-width: 768px) {
            .documentation-content {
                font-size: 14px;
            }

            .documentation-content h1 { font-size: 1.875em; }
            .documentation-content h2 { font-size: 1.5em; }
            .documentation-content h3 { font-size: 1.25em; }

            .documentation-content table,
            .documentation-content pre {
                font-size: 12px;
            }

            .documentation-content th,
            .documentation-content td {
                padding: 12px 8px;
            }

            .documentation-content blockquote {
                margin: 16px 0;
                padding: 12px 16px;
            }
        }

        /* Styles du nouveau sommaire */
        #toc a, #mobile-toc a {
            display: block;
            padding: 6px 0;
            color: #6b7280;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
            border-left: 2px solid transparent;
            margin-bottom: 2px;
        }

        .dark #toc a, .dark #mobile-toc a {
            color: #9ca3af;
        }

        #toc a:hover, #mobile-toc a:hover {
            color: #3b82f6;
        }

        .dark #toc a:hover, .dark #mobile-toc a:hover {
            color: #60a5fa;
        }

        #toc a.active, #mobile-toc a.active {
            color: #1d4ed8;
            border-left-color: #3b82f6;
            font-weight: 500;
        }

        .dark #toc a.active, .dark #mobile-toc a.active {
            color: #60a5fa;
            border-left-color: #60a5fa;
        }

        /* Indentations */
        #toc a.h1, #mobile-toc a.h1 { padding-left: 8px; font-weight: 600; }
        #toc a.h2, #mobile-toc a.h2 { padding-left: 20px; }
        #toc a.h3, #mobile-toc a.h3 { padding-left: 32px; }
        #toc a.h4, #mobile-toc a.h4 { padding-left: 44px; font-size: 13px; }
        #toc a.h5, #mobile-toc a.h5 { padding-left: 56px; font-size: 13px; }
        #toc a.h6, #mobile-toc a.h6 { padding-left: 68px; font-size: 13px; }

        /* État caché */
        #sidebar.hidden {
            transform: translateX(-100%);
        }

        #content.expanded {
            margin-left: 0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const toc = document.getElementById('toc');
            const mobileToc = document.getElementById('mobile-toc');
            const hideBtn = document.getElementById('hide-sidebar');
            const showBtn = document.getElementById('show-sidebar');
            const mobileBtn = document.getElementById('mobile-menu');
            const mobileOverlay = document.getElementById('mobile-overlay');
            const closeBtn = document.getElementById('close-mobile');
            const docContent = document.querySelector('.documentation-content');

            // Éléments de recherche
            const tocSearch = document.getElementById('toc-search');
            const mobileTocSearch = document.getElementById('mobile-toc-search');
            const clearSearch = document.getElementById('clear-search');
            const searchResults = document.getElementById('search-results');
            const mobileSearchResults = document.getElementById('mobile-search-results');
            const noResults = document.getElementById('no-results');
            const mobileNoResults = document.getElementById('mobile-no-results');

            let headings = [];
            let allTocLinks = [];
            let allMobileTocLinks = [];

            function generateId(text) {
                return text.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .trim();
            }

            function buildTOC() {
                if (!docContent) return;

                headings = Array.from(docContent.querySelectorAll('h1, h2, h3, h4, h5, h6'))
                    .filter(heading => {
                        // Exclure les titres vides ou qui ne contiennent que des espaces
                        const text = heading.textContent.trim();
                        return text.length > 0;
                    });

                if (headings.length === 0) {
                    toc.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-sm">Aucun titre trouvé</p>';
                    mobileToc.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-sm">Aucun titre trouvé</p>';
                    return;
                }

                // Générer les IDs
                headings.forEach((heading, index) => {
                    if (!heading.id) {
                        let id = generateId(heading.textContent);
                        if (document.getElementById(id)) {
                            id = `${id}-${index}`;
                        }
                        heading.id = id;
                    }
                });

                // Créer les liens
                let html = '';
                headings.forEach(heading => {
                    const level = heading.tagName.toLowerCase();
                    const text = heading.textContent.trim();
                    const id = heading.id;

                    // Double vérification que le texte n'est pas vide
                    if (text.length > 0) {
                        html += `<a href="#${id}" class="${level}" data-id="${id}" data-text="${text.toLowerCase()}">${text}</a>`;
                    }
                });

                toc.innerHTML = html;
                mobileToc.innerHTML = html;

                // Stocker les références des liens pour la recherche
                allTocLinks = Array.from(toc.querySelectorAll('a'));
                allMobileTocLinks = Array.from(mobileToc.querySelectorAll('a'));

                // Ajouter les événements
                document.querySelectorAll('#toc a, #mobile-toc a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const target = document.getElementById(this.dataset.id);
                        if (target) {
                            window.scrollTo({
                                top: target.offsetTop - 80,
                                behavior: 'smooth'
                            });
                        }
                        if (this.closest('#mobile-toc')) {
                            mobileOverlay.classList.add('hidden');
                        }
                    });
                });
            }

            function updateActive() {
                if (headings.length === 0) return;

                const scrollTop = window.pageYOffset;
                let activeHeading = null;

                headings.forEach(heading => {
                    if (heading.offsetTop <= scrollTop + 100) {
                        activeHeading = heading;
                    }
                });

                document.querySelectorAll('#toc a, #mobile-toc a').forEach(link => {
                    link.classList.remove('active');
                });

                if (activeHeading) {
                    document.querySelectorAll(`[data-id="${activeHeading.id}"]`).forEach(link => {
                        link.classList.add('active');
                    });
                }
            }

            function toggleSidebar() {
                const isHidden = sidebar.classList.contains('hidden');
                if (isHidden) {
                    sidebar.classList.remove('hidden');
                    content.classList.remove('expanded');
                    showBtn.classList.add('hidden');
                } else {
                    sidebar.classList.add('hidden');
                    content.classList.add('expanded');
                    showBtn.classList.remove('hidden');
                }
            }

            // Fonction de recherche corrigée
            function filterTOC(searchTerm, isDesktop = true) {
                const links = isDesktop ? allTocLinks : allMobileTocLinks;
                const container = isDesktop ? toc : mobileToc;
                const resultsEl = isDesktop ? searchResults : mobileSearchResults;
                const noResultsEl = isDesktop ? noResults : mobileNoResults;

                if (!searchTerm.trim()) {
                    // Afficher tous les liens et restaurer le texte original
                    links.forEach(link => {
                        link.style.display = 'block';
                        const originalText = link.dataset.text;
                        link.innerHTML = originalText.charAt(0).toUpperCase() + originalText.slice(1);
                    });
                    resultsEl.classList.add('hidden');
                    noResultsEl.classList.add('hidden');
                    container.classList.remove('hidden');
                    return;
                }

                const search = searchTerm.toLowerCase();
                let visibleCount = 0;

                links.forEach(link => {
                    const text = link.dataset.text;
                    const matches = text.includes(search);

                    if (matches) {
                        link.style.display = 'block';
                        // Surligner le terme recherché
                        const regex = new RegExp(`(${search.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                        const highlightedText = text.replace(regex, '<mark class="bg-yellow-200 dark:bg-yellow-800 px-1 rounded">$1</mark>');
                        link.innerHTML = highlightedText.charAt(0).toUpperCase() + highlightedText.slice(1);
                        visibleCount++;
                    } else {
                        link.style.display = 'none';
                    }
                });

                // Afficher les résultats
                if (visibleCount > 0) {
                    resultsEl.textContent = `${visibleCount} résultat${visibleCount > 1 ? 's' : ''} trouvé${visibleCount > 1 ? 's' : ''}`;
                    resultsEl.classList.remove('hidden');
                    noResultsEl.classList.add('hidden');
                    container.classList.remove('hidden');
                } else {
                    resultsEl.classList.add('hidden');
                    noResultsEl.classList.remove('hidden');
                    container.classList.add('hidden');
                }
            }

            // Événements de recherche
            if (tocSearch) {
                tocSearch.addEventListener('input', function() {
                    const value = this.value;
                    filterTOC(value, true);

                    if (value.trim()) {
                        clearSearch.classList.remove('hidden');
                    } else {
                        clearSearch.classList.add('hidden');
                    }
                });

                tocSearch.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        this.value = '';
                        this.dispatchEvent(new Event('input'));
                        this.blur();
                    }
                });
            }

            if (mobileTocSearch) {
                mobileTocSearch.addEventListener('input', function() {
                    filterTOC(this.value, false);
                });

                mobileTocSearch.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        this.value = '';
                        this.dispatchEvent(new Event('input'));
                        this.blur();
                    }
                });
            }

            if (clearSearch) {
                clearSearch.addEventListener('click', function() {
                    tocSearch.value = '';
                    tocSearch.dispatchEvent(new Event('input'));
                    tocSearch.focus();
                });
            }

            // Événements
            if (hideBtn) hideBtn.addEventListener('click', toggleSidebar);
            if (showBtn) showBtn.addEventListener('click', toggleSidebar);

            if (mobileBtn) {
                mobileBtn.addEventListener('click', () => {
                    mobileOverlay.classList.remove('hidden');
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    mobileOverlay.classList.add('hidden');
                });
            }

            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', (e) => {
                    if (e.target === mobileOverlay) {
                        mobileOverlay.classList.add('hidden');
                    }
                });
            }

            window.addEventListener('scroll', updateActive, { passive: true });

            // Initialisation
            buildTOC();
            updateActive();
        });
    </script>
</x-app-layout>
