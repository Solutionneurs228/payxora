@echo off
cd /d C:\1SSD\ProjetWeb\payxora
C:\xampp\php\php.exe -d memory_limit=1024M artisan schedule:run >> C:\1SSD\ProjetWeb\payxora\storage\logs\scheduler.log 2>&1
