@php
    $quickMatiereModalId = 'quickMatiereModal-' . time();
@endphp

<button x-data {{ $attributes->merge(['type' => 'button', 'class' => 'btn']) }}
    id="quickMatiereModalBtn-{{ $quickMatiereModalId }}"
    x-on:click.prevent="$dispatch('open-modal', '{{ $quickMatiereModalId }}')"
    onclick="showquickMatiereModal('{{ $quickMatiereModalId }}')">
    Nouvelle Matière
</button>
{{-- Script temporaire !!!!!!!!!!!!!!!!!
 A RETIRER--}}

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function() {
            document.getElementById('quickMatiereModalBtn-{{ $quickMatiereModalId }}').click();
        }, 1000);
    });
</script> --}}
<!-- Modal -->
<x-modal id="{{ $quickMatiereModalId }}" name="{{ $quickMatiereModalId }}" title="Quick Create Matiere">
    <div id="modal-body-{{ $quickMatiereModalId }}"></div>

</x-modal>
<script>
    function showquickMatiereModal(id) {
        var modalBody = document.getElementById('modal-body-'+id);
modalBody.innerHTML = '<div id="loading-spinner" class="m-6 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-32 w-full"><div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div></div><style>.loader {border-top-color: #3498db;animation: spinner 1.5s linear infinite;}@keyframes spinner {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}</style>';
        fetch('/matieres/quickcreate/' + id)
            .then(response => response.text())
            .then(html => {
                const modalContent = document.getElementById('modal-body-' + id);
                if (modalContent) {
                    // Utilisation de DOMParser pour analyser le HTML
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Insérer le contenu du body
                    modalContent.innerHTML = doc.body.innerHTML;

                    // Fonction pour exécuter les scripts
                    function executeScripts(doc) {
                        doc.querySelectorAll('.SCRIPT').forEach(script => {
                            const newScript = document.createElement('script');
                            newScript.textContent = script.textContent;
                            document.body.appendChild(newScript);
                        });
                    }

                    // Exécuter les scripts après avoir rempli le modal
                    executeScripts(doc);
                }
            })
            .catch(error => {
                console.error('Error loading modal content:', error);
                modalBody.innerHTML = '<p>Error loading content.</p>';
            });
    }
</script>
