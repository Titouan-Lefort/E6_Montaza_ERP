@echo off
echo ===============================================
echo Migration: Ajout des creneaux pour les taches
echo ===============================================
echo.
echo Cette migration ajoute les colonnes creneau_debut et creneau_fin
echo pour permettre de choisir les demi-journees (matin/apres-midi)
echo.
echo Commandes a executer dans Vagrant:
echo.
echo vagrant ssh
echo cd /home/vagrant/code/montaza
echo php artisan migrate
echo php artisan view:clear
echo php artisan config:clear
echo php artisan cache:clear
echo exit
echo.
echo ===============================================
pause
