@echo off
REM Serveur de développement local - Double-cliquez pour lancer
REM Ce script lance un serveur HTTP Python sur le port 8000

cd /d "%~dp0"

echo.
echo ============================================
echo   Groupe Scolaire Noumidia - Dev Server
echo ============================================
echo.
echo Lancement du serveur local...
echo.

python -m http.server 8000

echo.
echo Serveur arrete.
pause
