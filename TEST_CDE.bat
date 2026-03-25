@echo off
echo ====================================
echo Tests Unitaires - Commandes (Cde)
echo ====================================
vagrant ssh -c "cd /home/vagrant/code/montaza && php artisan test --filter=CdeTest"
pause
