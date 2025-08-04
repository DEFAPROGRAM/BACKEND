@echo off
echo Configurando enlace simbólico de storage...
echo.

REM Verificar si existe el enlace simbólico
if exist "public\storage" (
    echo Eliminando enlace simbólico existente...
    rmdir /s /q "public\storage"
)

REM Crear nuevo enlace simbólico
echo Creando nuevo enlace simbólico...
php artisan storage:link

echo.
echo ¡Enlace simbólico configurado correctamente!
echo Las imágenes ahora deberían ser accesibles en: http://127.0.0.1:8000/storage/
echo.
pause 