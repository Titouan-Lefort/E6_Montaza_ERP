@echo off
echo ====================================
echo Tests Unitaires - Lignes de Commandes
echo ====================================
vagrant ssh -c "cd /home/vagrant/code/montaza && php artisan test --filter=CdeLigneTest"
pause
