# 📚 Documentation Utilisateur Montaza ERP

## Fichiers disponibles

Ce dossier contient la documentation utilisateur complète de l'application Montaza ERP sous plusieurs formats :

### 📄 Fichiers de documentation

1. **Documentation_Montaza.pdf** ⭐ **(RECOMMANDÉ)**
   - Format PDF prêt à l'impression
   - Taille : ~356 KB
   - Contient toute la documentation avec mise en forme professionnelle
   - Idéal pour la lecture et l'impression

2. **DOCUMENTATION_COMPLETE.html**
   - Version HTML interactive
   - Peut être consultée directement dans un navigateur
   - Contient un bouton pour imprimer en PDF
   - Utilisez Ctrl+P pour imprimer

3. **DOCUMENTATION_COMPLETE.md**
   - Version Markdown source
   - Peut être modifiée avec n'importe quel éditeur de texte
   - Format brut pour l'édition

### 🛠️ Scripts de génération

1. **GENERER_PDF_DOCUMENTATION.ps1** ⭐ **(RECOMMANDÉ)**
   - Script PowerShell automatisé
   - Génère automatiquement le PDF depuis le HTML
   - Utilise Chrome ou Edge en mode headless
   - **Utilisation :** Clic droit > "Exécuter avec PowerShell"

2. **GENERER_PDF_DOCUMENTATION.bat**
   - Script batch alternatif
   - Ouvre le HTML dans le navigateur pour impression manuelle
   - **Utilisation :** Double-cliquer sur le fichier

## 📖 Contenu de la documentation

La documentation complète couvre les sections suivantes :

1. **Vue d'ensemble de l'application**
   - Présentation de Montaza ERP
   - Fonctionnalités principales (Gestion commerciale, Stocks, Tiers, SAV, etc.)

2. **Installation et Configuration**
   - Prérequis techniques
   - Étapes d'installation détaillées
   - Configuration de l'environnement
   - Comptes par défaut

3. **Système de Gestion des Tâches**
   - Gestion des tâches par affaire
   - Emploi du temps des employés
   - Structure de la base de données
   - Routes et utilisation

4. **Gestion des Devis et Affaires**
   - Schéma de liaison Devis ↔ Affaires
   - Structure de la base de données
   - Flux de données
   - Interface utilisateur

5. **Tests et Qualité**
   - Tests unitaires (CdeTest, CdeLigneTest)
   - Couverture des tests
   - Commandes d'exécution des tests

## 🚀 Comment utiliser la documentation

### Méthode 1 : Lire le PDF (Recommandé)
```
1. Ouvrez "Documentation_Montaza.pdf" avec votre lecteur PDF préféré
2. Naviguez avec la table des matières
```

### Méthode 2 : Régénérer le PDF
```
1. Faites un clic droit sur "GENERER_PDF_DOCUMENTATION.ps1"
2. Sélectionnez "Exécuter avec PowerShell"
3. Le nouveau PDF sera créé dans le même dossier
```

### Méthode 3 : Consulter en ligne (HTML)
```
1. Double-cliquez sur "DOCUMENTATION_COMPLETE.html"
2. Le fichier s'ouvrira dans votre navigateur par défaut
3. Cliquez sur le bouton bleu "Imprimer en PDF" en bas à droite si besoin
```

### Méthode 4 : Modifier la documentation
```
1. Ouvrez "DOCUMENTATION_COMPLETE.md" avec un éditeur de texte
2. Modifiez le contenu selon vos besoins
3. Relancez le script de génération pour créer un nouveau PDF/HTML
```

## 🔄 Mise à jour de la documentation

Si vous modifiez la documentation source (fichiers .md dans le projet), suivez ces étapes :

1. Mettez à jour les fichiers markdown sources :
   - `README.md`
   - `SYSTEME_TACHES.md`
   - `TESTS_COMMANDES.md`
   - `IMPORT_MATIERES.md`
   - `database/SCHEMA_DEVIS_AFFAIRES.md`

2. Compilez la nouvelle documentation complète
3. Lancez le script `GENERER_PDF_DOCUMENTATION.ps1` pour créer le nouveau PDF

## 💡 Astuces

### Impression optimale du PDF
- Le PDF est déjà optimisé pour l'impression A4
- Marges : 2 cm de chaque côté
- Les tableaux et blocs de code évitent les coupures de page
- Les titres principaux démarrent sur une nouvelle page

### Navigation dans le PDF
- Utilisez les signets/bookmarks de votre lecteur PDF pour naviguer rapidement
- Chaque section majeure commence sur une nouvelle page

### Recherche dans la documentation
- Utilisez Ctrl+F dans le PDF pour chercher des mots-clés
- La version HTML permet aussi une recherche rapide dans le navigateur

## 📞 Support

Pour toute question sur la documentation ou l'application Montaza :
- Consultez d'abord cette documentation complète
- Vérifiez les fichiers de migration (.bat) pour les mises à jour de schéma
- Contactez l'équipe de développement pour des questions spécifiques

---

**Dernière mise à jour :** 24 Mars 2026  
**Version de la documentation :** 1.0  
**Application :** Montaza ERP - Laravel 12
