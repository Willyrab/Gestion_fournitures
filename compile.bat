@echo off
REM Script pour compiler et lancer le projet Laravel, puis l'ouvrir dans un navigateur.

REM Mise en cache de la configuration, des routes et des vues
echo Mise en cache des configurations...
php artisan config:cache

echo Mise en cache des routes...
php artisan route:cache

echo Mise en cache des vues...
php artisan view:cache

echo Effacement du cache général...
php artisan cache:clear

REM Lancer le serveur Laravel
echo Lancement du serveur Laravel...
start /b php artisan serve

REM Attendre quelques secondes pour s'assurer que le serveur est bien démarré
timeout /t 5 >nul

REM Ouvrir l'application dans le navigateur par défaut
echo Ouverture de l'application dans le navigateur...
start http://localhost:8000

REM Fin du script
echo Le projet est lancé avec succès.
pause
