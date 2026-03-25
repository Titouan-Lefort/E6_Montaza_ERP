@echo off
echo ========================================
echo Migration des tables personnel et taches
echo ========================================
echo.
echo Connexion au serveur Vagrant...
echo.

cd C:\Users\prepaetude\Homestead

vagrant ssh -c "cd /home/vagrant/code/montaza && php artisan migrate && php artisan view:clear && php artisan config:clear && php artisan cache:clear"

echo.
echo ========================================
echo Migrations terminees !
echo ========================================
pause
