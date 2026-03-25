
<head>
    <style>


        h1 {
            position: relative;
            z-index: 1;
        }

        .paillettes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .paillette {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: rgba(255, 215, 0, 0.8); /* Couleur or */
            border-radius: 50%;
            animation: tomber 4s linear infinite;
        }

        @keyframes tomber {
            0% {
                transform: translateY(-80vh);
                opacity: 1;
            }
            100% {
                transform: translateY(90vh);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Conteneur pour les paillettes -->
    <div class="paillettes"></div>

    <script>
        // Générer un certain nombre de paillettes
        const nombreDePaillettes = 1000;

        // Référence au conteneur de paillettes
        const paillettesContainer = document.querySelector('.paillettes');

        // Fonction pour générer une paillette à une position aléatoire
        function creerPaillette() {
            const paillette = document.createElement('div');
            paillette.classList.add('paillette');
            paillette.style.left = Math.random() * window.innerWidth + 'px';
            paillette.style.animationDuration = Math.random() * 3 + 3 + 's'; // Entre 3 et 6 secondes
            paillette.style.opacity = Math.random();
            paillettesContainer.appendChild(paillette);
        }

        // Générer les paillettes
        for (let i = 0; i < nombreDePaillettes; i++) {
            creerPaillette();
        }
    </script>

</body>
</html>
