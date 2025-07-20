@echo off
cd /d "c:\xampp\htdocs\minecraft-hosting-billing"
echo Starting Laravel Queue Worker for Notifications...
php artisan queue:work --queue=default --timeout=60 --tries=3
pause
