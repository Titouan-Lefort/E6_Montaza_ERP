/**
 * Script simplifié pour redimensionner automatiquement les textareas selon leur contenu
 */

// Fonction pour redimensionner une textarea avec optimisation pour éviter les reflows forcés
function autoResizeTextarea(textarea) {
    // Utilise requestAnimationFrame pour éviter les reflows forcés
    window.requestAnimationFrame(() => {
        // Phase de lecture (mesurage)
        const scrollHeight = textarea.scrollHeight;

        // Phase d'écriture (mise à jour)
        window.requestAnimationFrame(() => {
            const minHeight = 40; // Hauteur minimale en pixels
            textarea.style.height = 'auto';
            textarea.style.height = Math.max(scrollHeight, minHeight) + 'px';
            textarea.style.overflowY = 'hidden';
        });
    });
}

// Fonction principale que vous pouvez appeler n'importe quand pour redimensionner toutes les textareas
function refreshAllTextareas() {
    const textareas = document.querySelectorAll('textarea');
    // console.log(`Found ${textareas.length} textareas to auto-resize.`);
    textareas.forEach(textarea => {
        if (!textarea.dataset.autoResizeInitialized) {
            // Marque cette textarea comme initialisée
            textarea.dataset.autoResizeInitialized = 'true';

            // Ajoute les écouteurs d'événements une seule fois
            textarea.addEventListener('input', function() {
                autoResizeTextarea(this);
            });
        }

        // Applique le redimensionnement initial
        autoResizeTextarea(textarea);
    });
}

// Fonction pour rafraîchir les textareas après un délai, utile pour les contenus dynamiques
function delayedRefreshTextareas(delay = 200) {
            setTimeout(function() {
                window.refreshTextareas();
            }, delay);
        }
// Initialisation de base au chargement de la page
document.addEventListener('DOMContentLoaded', refreshAllTextareas);

// Expose la fonction globalement pour pouvoir l'appeler de n'importe où
window.refreshTextareas = refreshAllTextareas;
// Expose la fonction pour rafraîchir les textareas après un délai
window.delayedRefreshTextareas = delayedRefreshTextareas;
// Également exposer sous l'ancien nom pour maintenir la compatibilité
window.initAutoResizeTextareas = refreshAllTextareas;


// Version optimisée de l'observateur de mutation pour réduire la fréquence d'appels
const observer = new MutationObserver(function(mutations) {
    // Utilise un debounce simple pour éviter les appels multiples
    clearTimeout(observer.debounceTimer);
    observer.debounceTimer = setTimeout(() => {
        initAutoResizeTextareas();
    }, 100); // Attendre 100ms après la dernière mutation
});



