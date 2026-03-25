#!/bin/bash

# Script pour corriger les permissions du storage
# À exécuter depuis le serveur (Vagrant/Homestead)

echo "🔧 Correction des permissions du répertoire storage..."

# Créer le répertoire media s'il n'existe pas
mkdir -p storage/app/public/media
mkdir -p storage/app/public/media/cde
mkdir -p storage/app/public/media/ddp

# Donner les bonnes permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Changer le propriétaire (www-data pour Apache/Nginx, vagrant pour Homestead)
# Décommentez la ligne appropriée selon votre environnement

# Pour Homestead/Vagrant :
chown -R vagrant:www-data storage
chown -R vagrant:www-data bootstrap/cache

# Pour production (si www-data est l'utilisateur du serveur web) :
# chown -R www-data:www-data storage
# chown -R www-data:www-data bootstrap/cache

echo "✅ Permissions corrigées"
echo ""
echo "🔗 Recréation du lien symbolique..."
php artisan storage:link

echo ""
echo "✅ Terminé !"
echo ""
echo "Vérifiez avec : php artisan storage:link"
echo "Test d'écriture : touch storage/app/public/test.txt && rm storage/app/public/test.txt && echo 'OK'"
