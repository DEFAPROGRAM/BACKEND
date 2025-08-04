#!/bin/bash

echo "Configurando enlace simbólico de storage..."
echo

# Verificar si existe el enlace simbólico
if [ -L "public/storage" ]; then
    echo "Eliminando enlace simbólico existente..."
    rm "public/storage"
fi

# Crear nuevo enlace simbólico
echo "Creando nuevo enlace simbólico..."
php artisan storage:link

echo
echo "¡Enlace simbólico configurado correctamente!"
echo "Las imágenes ahora deberían ser accesibles en: http://127.0.0.1:8000/storage/"
echo 