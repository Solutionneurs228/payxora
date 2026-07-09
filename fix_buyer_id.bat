@echo off
chcp 65001 >nul
title PayXora - Fix buyer_id NULL
color 0A

echo ==========================================
echo    CORRECTION buyer_id NULLABLE
echo ==========================================
echo.

echo [1/4] Vidage complet des caches...
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear
echo.

echo [2/4] Suppression des anciennes migrations en conflit...
if exist "database\migrations\*make_buyer_id_nullable*" del /Q "database\migrations\*make_buyer_id_nullable*"
if exist "database\migrations\*fix_buyer_id*" del /Q "database\migrations\*fix_buyer_id*"
echo.

echo [3/4] Copie de la nouvelle migration...
copy /Y "install_files\2026_07_08_011300_fix_buyer_id_nullable.php" "database\migrations\" >nul
echo.

echo [4/4] Execution de la migration...
php artisan migrate --force
echo.

echo ==========================================
echo    VERIFICATION
echo ==========================================
echo.
php artisan migrate:status | findstr "fix_buyer_id"
echo.

echo Si la migration est en status "Ran", le probleme est resolu !
echo.
echo Testez maintenant la creation d'une transaction.
echo.
pause
