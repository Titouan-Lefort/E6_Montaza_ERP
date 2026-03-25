@echo off
echo ============================================
echo MIGRATION POUR LES CONGES
echo ============================================
echo.
echo Cette migration va ajouter la table personnel_conges pour gerer les conges des employes.
echo.
echo IMPORTANT : Cette migration doit etre executee sur le serveur Vagrant.
echo.
echo Instructions :
echo 1. Ouvrez Git Bash ou un terminal
echo 2. Executez : vagrant ssh
echo 3. Executez : cd /home/vagrant/code/montaza
echo 4. Executez : php artisan migrate
echo 5. Executez : php artisan view:clear
echo 6. Executez : php artisan config:clear
echo 7. Executez : php artisan route:clear
echo 8. Tapez : exit
echo.
echo ============================================
echo Appuyez sur une touche pour fermer...
pause > nul
