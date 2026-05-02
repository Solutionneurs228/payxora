@echo off
cd /d C:\1SSD\ProjetWeb\payxora
C:\xampp\php\php.exe -d memory_limit=1024M artisan payxora:expire-transactions >> C:\1SSD\ProjetWeb\payxora\storage\logs\cron-expire.log 2>&1
