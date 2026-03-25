document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            const target = event.target;

            // Vérifier si on est bien dans un input ou textarea
            if (target.tagName === "INPUT" || target.tagName === "SELECT") {
                event.preventDefault(); // Empêcher la soumission du formulaire

                // Récupérer tous les champs du formulaire
                const formElements = Array.from(target.form.elements);
                const index = formElements.indexOf(target);

                if (index !== -1) {
                    // Trouver le prochain élément focusable
                    let nextIndex = index + 1;
                    while (nextIndex < formElements.length) {
                        const nextElement = formElements[nextIndex];
                        const tabIndex = nextElement.getAttribute("tabindex");
                        if (
                            !nextElement.disabled &&
                            nextElement.tagName !== "FIELDSET" &&
                            tabIndex !== "-1"
                        ) {
                            nextElement.focus();
                            break;
                        }
                        nextIndex++;
                    }
                }
            }
        }
    });

});
