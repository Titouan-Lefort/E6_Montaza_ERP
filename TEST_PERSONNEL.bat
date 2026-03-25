@echo off
echo ====================================
echo Tests Unitaires - Personnel
echo ====================================
vagrant ssh -c "cd /home/vagrant/code/montaza && php artisan test --filter=PersonnelControllerTest"
pause
