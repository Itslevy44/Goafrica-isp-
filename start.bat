@echo off
title goAfrica Connect - Background Services
color 0a
echo ===================================================
echo   Starting goAfrica Connect Services
echo ===================================================

echo [1/2] Starting Laravel Web Server on port 8000...
start "Laravel Server" cmd /c "php artisan serve --port=8000"

echo [2/2] Starting Background Queue Worker...
start "Queue Worker" cmd /c "php artisan queue:work --tries=3"

echo.
echo All services started in background windows!
echo Please keep the black command prompt windows open while developing.
echo.
pause
