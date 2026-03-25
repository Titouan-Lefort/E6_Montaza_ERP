window.tooltip = function(forcedPosition = 'auto') {
    return {
        show: false,
        style: '',
        targetRect: null,
        tooltipHovered: false,
        offset: 10,
        position: forcedPosition,
        tooltipClass: '',
        isMobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
        isFirstShow: true,
        isPositioning: false, // Nouveau flag pour contrôler la visibilité

        init() {
            // Ajouter l'indicateur mobile si nécessaire
            if (this.isMobile) {
                this.addMobileIndicator();
            }
        },

        addMobileIndicator() {
            // Ajouter une classe pour indiquer que c'est cliquable
            this.$el.classList.add('cursor-pointer', 'relative');

            // Ajouter un petit icône d'information si pas déjà présent
            if (!this.$el.querySelector('.mobile-tooltip-indicator')) {
                const indicator = document.createElement('span');
                indicator.className = 'mobile-tooltip-indicator absolute -top-1 -right-1 w-4 h-4 bg-blue-500 text-white rounded-full text-xs flex items-center justify-center';
                indicator.innerHTML = '?';
                this.$el.appendChild(indicator);
            }
        },

        handleInteraction(event) {
            if (this.isMobile) {
                this.toggleTooltip(event);
            } else {
                this.showTooltip(event);
            }
        },

        toggleTooltip(event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.show) {
                this.hide();
            } else {
                this.showTooltip(event);
                // Fermer au clic ailleurs
                setTimeout(() => {
                    document.addEventListener('click', this.closeOnClickOutside.bind(this), { once: true });
                }, 0);
            }
        },

        closeOnClickOutside(event) {
            if (!this.$el.contains(event.target) && !this.$refs.tooltip?.contains(event.target)) {
                this.hide();
            }
        },

        showTooltip(event) {
            this.calculatePosition(event);
            this.isPositioning = true; // Masquer pendant le calcul
            this.show = true;

            // Forcer un recalcul après rendu complet
            this.$nextTick(() => {
                setTimeout(() => {
                    this.updatePosition();
                    this.isFirstShow = false;
                    this.isPositioning = false; // Afficher maintenant
                }, 10);
            });
        },

        hide() {
            this.show = false;
            // Réinitialiser pour le prochain affichage
            setTimeout(() => {
                this.isFirstShow = true;
            }, 200);
        },

        calculatePosition(event) {
            this.targetRect = this.$el.getBoundingClientRect();

            // Position initiale hors écran pour éviter le flash
            this.style = 'position: fixed; top: -9999px; left: -9999px; z-index: 9999; opacity: 0;';
        },

        updatePosition() {
            if (!this.$refs.tooltip) {
                setTimeout(() => this.updatePosition(), 5);
                return;
            }

            // Attendre que le tooltip soit rendu avec ses vraies dimensions
            if (!this.$refs.tooltip.offsetWidth || !this.$refs.tooltip.offsetHeight) {
                setTimeout(() => this.updatePosition(), 5);
                return;
            }

            const tooltipRect = this.$refs.tooltip.getBoundingClientRect();

            // IMPORTANT: Recalculer la position de l'élément cible maintenant, pas avant
            this.targetRect = this.$el.getBoundingClientRect();
            const targetRect = this.targetRect;

            // Double vérification des dimensions
            if (tooltipRect.width === 0 || tooltipRect.height === 0) {
                setTimeout(() => this.updatePosition(), 5);
                return;
            }

            // Vérifier que l'élément cible est toujours visible
            if (targetRect.width === 0 || targetRect.height === 0) {
                this.hide();
                return;
            }

            const viewport = {
                width: window.innerWidth,
                height: window.innerHeight,
                scrollX: window.scrollX,
                scrollY: window.scrollY
            };

            // Marges de sécurité
            const margin = 8;
            const bounds = {
                left: margin,
                right: viewport.width - margin,
                top: margin,
                bottom: viewport.height - margin
            };

            let finalPosition = this.position;

            // Pour mobile, privilégier bottom ou top
            if (this.isMobile) {
                finalPosition = this.getBestMobilePosition(targetRect, tooltipRect, viewport, bounds, margin);
            } else if (this.position === 'auto') {
                finalPosition = this.getBestDesktopPosition(targetRect, tooltipRect, viewport, bounds);
            } else {
                // Position forcée, vérifier qu'elle est possible
                finalPosition = this.position;
            }

            const coords = this.calculateCoordinates(finalPosition, targetRect, tooltipRect);
            const adjustedCoords = this.adjustForViewport(coords, tooltipRect, bounds);

            this.tooltipClass = `tooltip-${finalPosition}`;

            // Style final avec position absolue par rapport au viewport
            this.style = `position: fixed; top: ${adjustedCoords.top}px; left: ${adjustedCoords.left}px; z-index: 9999; opacity: 1;`;
        },

        getBestMobilePosition(targetRect, tooltipRect, viewport, bounds, margin) {
            const spaceBottom = viewport.height - targetRect.bottom - this.offset;
            const spaceTop = targetRect.top - this.offset;

            // Sur mobile, privilégier bottom, puis top
            if (spaceBottom >= tooltipRect.height + margin * 2) {
                return 'bottom';
            } else if (spaceTop >= tooltipRect.height + margin * 2) {
                return 'top';
            } else {
                // Si pas assez de place en haut/bas, utiliser bottom avec ajustement
                return 'bottom';
            }
        },

        getBestDesktopPosition(targetRect, tooltipRect, viewport, bounds) {
            const spaces = {
                top: targetRect.top - tooltipRect.height - this.offset,
                bottom: viewport.height - targetRect.bottom - tooltipRect.height - this.offset,
                left: targetRect.left - tooltipRect.width - this.offset,
                right: viewport.width - targetRect.right - tooltipRect.width - this.offset
            };

            // Trouver la position avec le plus d'espace disponible et assez de place
            let bestPosition = 'top';
            let bestSpace = -Infinity;

            Object.entries(spaces).forEach(([pos, space]) => {
                if (space >= 0 && space > bestSpace) {
                    bestSpace = space;
                    bestPosition = pos;
                }
            });

            // Si aucune position n'a assez de place, prendre celle avec le plus d'espace même si négatif
            if (bestSpace < 0) {
                bestSpace = Math.max(...Object.values(spaces));
                bestPosition = Object.keys(spaces).find(key => spaces[key] === bestSpace);
            }

            return bestPosition;
        },

        calculateCoordinates(position, targetRect, tooltipRect) {
            const coords = { top: 0, left: 0 };

            switch (position) {
                case 'top':
                    coords.top = targetRect.top - tooltipRect.height - this.offset;
                    coords.left = targetRect.left + (targetRect.width - tooltipRect.width) / 2;
                    break;
                case 'bottom':
                    coords.top = targetRect.bottom + this.offset;
                    coords.left = targetRect.left + (targetRect.width - tooltipRect.width) / 2;
                    break;
                case 'left':
                    coords.top = targetRect.top + (targetRect.height - tooltipRect.height) / 2;
                    coords.left = targetRect.left - tooltipRect.width - this.offset;
                    break;
                case 'right':
                    coords.top = targetRect.top + (targetRect.height - tooltipRect.height) / 2;
                    coords.left = targetRect.right + this.offset;
                    break;
            }

            return coords;
        },

        adjustForViewport(coords, tooltipRect, bounds) {
            // Ajuster horizontalement
            coords.left = Math.max(bounds.left, Math.min(coords.left, bounds.right - tooltipRect.width));

            // Ajuster verticalement
            coords.top = Math.max(bounds.top, Math.min(coords.top, bounds.bottom - tooltipRect.height));

            return coords;
        },

        // Gestion des événements hover pour desktop
        enterTooltip() {
            if (!this.isMobile) {
                this.tooltipHovered = true;
            }
        },

        leaveTooltip() {
            if (!this.isMobile) {
                this.tooltipHovered = false;
                setTimeout(() => {
                    if (!this.tooltipHovered) this.hide();
                }, 100);
            }
        },

        hideTooltip() {
            if (!this.isMobile) {
                setTimeout(() => {
                    if (!this.tooltipHovered) this.hide();
                }, 100);
            }
        }
    }
};
