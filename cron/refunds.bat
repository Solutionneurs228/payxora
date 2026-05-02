@echo off
cd /d C:\1SSD\ProjetWeb\payxora
C:\xampp\php\php.exe -d memory_limit=1024M artisan payxora:process-auto-refunds >> C:\1SSD\ProjetWeb\payxora\storage\logs\cron-refunds.log 2>&1
