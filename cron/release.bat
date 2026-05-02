@echo off
cd /d C:\1SSD\ProjetWeb\payxora
C:\xampp\php\php.exe -d memory_limit=1024M artisan payxora:release-escrow-funds >> C:\1SSD\ProjetWeb\payxora\storage\logs\cron-release.log 2>&1
