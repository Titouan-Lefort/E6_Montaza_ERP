<div class=" p-4 pl-0 pt-0">
    <label for="commentaire"
        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{!! __('Commentaire') !!}</label>
    <textarea id="commentaire" name="commentaire"
        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-900 dark:text-gray-100"
        data-media-id="{{ $media->id }}"
        onblur="updateCommentaireMedia(this)"
        >{{ $media->commentaire?->contenu ?? '' }}</textarea>
</div>
@once
    <script class="SCRIPT">
     function updateCommentaireMedia(element) {
            const mediaId = element.dataset.mediaId; // Récupère l'ID de la société
            const commentaireTexte = element.value; // Récupère la valeur du commentaire

            // Envoie la requête AJAX avec fetch
            fetch('/media/' + mediaId + '/commentaire/save', {
                    method: 'PATCH', // Utilise la méthode PATCH pour mettre à jour
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Envoie le token CSRF pour la sécurité
                    },
                    body: JSON.stringify({
                        commentaire: commentaireTexte, // Envoie le texte du commentaire
                    }),
                })
                .then(response => response.json()) // Récupère la réponse en JSON
                .then(data => {
                    if (!(data.message == 'Commentaire inchangé')) {
                        showFlashMessageFromJs(data.message, 2000);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la mise à jour du commentaire', error);
                    showFlashMessageFromJs('Erreur lors de la mise à jour du commentaire', 2000, 'error');
                });
        }
</script>
@endonce
