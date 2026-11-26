@echo off
setlocal
title Laravel Project Setup

echo.
echo ==========================================
echo        Laravel Project Setup
echo ==========================================
echo.

set "STEP=Composer dependencies"
echo [1/4] Installing Composer dependencies...
call composer install
if errorlevel 1 goto :fail
echo [OK] %STEP% installed.
call :wait

set "STEP=Copying .env file"
echo.
echo [2/4] Copying .env.example to .env...
xcopy /y .env.example .env
if errorlevel 1 goto :fail
echo [OK] %STEP%.
call :wait

set "STEP=Generating application key"
echo.
echo [3/4] Generating application key...
php artisan key:generate
if errorlevel 1 goto :fail
echo [OK] %STEP%.
call :wait

set "STEP=Database migration and seeding"
echo.
echo [4/4] Migrating and seeding the database...
php artisan migrate:fresh --seed
if errorlevel 1 goto :fail
echo [OK] %STEP%.

echo.
echo ==========================================
echo All setup steps completed successfully.
echo ==========================================
pause
exit /b 0

:wait
timeout /t 2 /nobreak >nul
exit /b

:fail
echo.
echo [FAILED] %STEP%.
echo Check the error output above.
pause
exit /b 1
