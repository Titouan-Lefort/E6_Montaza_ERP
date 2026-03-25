@echo off
echo ====================================
echo TOUS LES TESTS UNITAIRES
echo ====================================
vagrant ssh -c "cd /home/vagrant/code/montaza && php artisan test"
pause
