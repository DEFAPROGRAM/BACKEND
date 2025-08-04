# 🚀 Instrucciones de Configuración - JUSTROOM Backend

## Configuración Inicial

Después de clonar o copiar el proyecto a un nuevo PC, sigue estos pasos:

### 1. Instalar Dependencias
```bash
composer install
```

### 2. Configurar Variables de Entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configurar Base de Datos
- Crear base de datos `justroom_bd`
- Configurar credenciales en `.env`
- Ejecutar migraciones: `php artisan migrate`
- Ejecutar seeders: `php artisan db:seed`

### 4. 🔧 Configurar Storage (IMPORTANTE)
**Este paso es necesario para que las imágenes funcionen correctamente.**

#### Opción A: Usar comando personalizado (Recomendado)
```bash
php artisan setup:storage
```

#### Opción B: Usar script automático
**Windows:**
```bash
setup_storage.bat
```

**Linux/Mac:**
```bash
chmod +x setup_storage.sh
./setup_storage.sh
```

#### Opción C: Manual
```bash
# Eliminar enlace existente si existe
rm -rf public/storage

# Crear nuevo enlace simbólico
php artisan storage:link
```

### 5. Verificar Configuración
```bash
# Iniciar servidor
php artisan serve

# Probar imágenes (en otro terminal)
curl -I http://127.0.0.1:8000/storage/slider/1.jpg
```

## 🐛 Solución de Problemas

### Las imágenes no se muestran
1. Verificar que el enlace simbólico existe: `ls -la public/storage`
2. Verificar que las imágenes existen: `ls storage/app/public/slider/`
3. Ejecutar: `php artisan setup:storage`

### Error de permisos (Linux/Mac)
```bash
chmod -R 755 storage/
chmod -R 755 public/
```

### Enlace simbólico no funciona
```bash
# Eliminar y recrear
rm -rf public/storage
php artisan storage:link
```

## 📁 Estructura de Archivos Importantes

```
storage/app/public/
├── slider/          # Imágenes del slider principal
│   ├── 1.jpg
│   ├── 2.png
│   └── 3.jpg
└── news_sliders/    # Imágenes de noticias
    ├── 1.jpg
    └── 2.png

public/storage/      # Enlace simbólico (se crea automáticamente)
```

## 🔗 URLs de Prueba

- API Slider: `http://127.0.0.1:8000/api/slider`
- API News: `http://127.0.0.1:8000/api/news_sliders`
- Imagen 1: `http://127.0.0.1:8000/storage/slider/1.jpg`
- Imagen 2: `http://127.0.0.1:8000/storage/slider/2.png`
- Imagen 3: `http://127.0.0.1:8000/storage/slider/3.jpg` 