@echo off
REM Script pour générer le PDF de la documentation Montaza
echo ========================================
echo   GENERATION DU PDF DE DOCUMENTATION
echo ========================================
echo.
echo Ce script va convertir la documentation HTML en PDF
echo.

REM Vérifier si le fichier HTML existe
if not exist "DOCUMENTATION_COMPLETE.html" (
    echo ERREUR: Le fichier DOCUMENTATION_COMPLETE.html n'existe pas!
    pause
    exit /b 1
)

echo Ouverture du fichier HTML dans votre navigateur...
echo.
echo INSTRUCTIONS:
echo 1. Le fichier HTML va s'ouvrir dans votre navigateur par défaut
echo 2. Utilisez Ctrl+P pour ouvrir le dialogue d'impression
echo 3. Sélectionnez "Enregistrer au format PDF" ou "Microsoft Print to PDF"
echo 4. Enregistrez le fichier sous le nom: Documentation_Montaza.pdf
echo.
echo Alternative: Cliquez sur le bouton bleu "Imprimer en PDF" en bas à droite de la page
echo.

REM Ouvrir le fichier HTML dans le navigateur par défaut
start "" "DOCUMENTATION_COMPLETE.html"

echo.
echo Le navigateur a été ouvert avec le fichier de documentation.
echo Suivez les instructions ci-dessus pour générer le PDF.
echo.
pause
