# Instalar dependencias en un proyecto Laravel existente

## 1. Clonar el Proyecto (si aún no lo has hecho)

Si el proyecto Laravel está en un repositorio Git y necesitas clonarlo, puedes hacerlo con el siguiente comando:

```bash
git clone https://github.com/andmari11/TimeFlex.git
```

## 2. Navegar al Directorio del Proyecto
Accede al directorio del proyecto Laravel:

```bash
cd TimeFlex/
```

## 3. Instalar las Dependencias con Composer
En el directorio del proyecto, instala las dependencias definidas en el archivo composer.json ejecutando:

```bash
composer install
```
Este comando instalará todas las dependencias necesarias para el proyecto.

## 4. Configurar el Archivo .env
Si es un proyecto recién clonado o descargado, asegúrate de que el archivo .env esté configurado correctamente. Si no tienes un archivo .env, puedes crearlo a partir del archivo de ejemplo:

```bash
cp .env.example .env
```

Luego, edita el archivo .env para ajustar las configuraciones de tu entorno, como la conexión a la base de datos y otras variables de entorno.

## 5. Generar la Clave de Aplicación
Genera una clave de aplicación si aún no está establecida en el archivo .env:

```bash
php artisan key:generate
```

## 6. Ejecutar Migraciones (si es necesario)
Si el proyecto utiliza una base de datos y tiene migraciones definidas, ejecuta las migraciones para crear las tablas necesarias:

```bash
php artisan migrate

```
## 7. Poblar Base de Datos
Llenar la base de datos con usuarios y empresas ficticios
```bash
php artisan db:seed
```

## 8. Activar API Python
Activa el servidor FastApi y configuralo para todas las funcionalidades que necesiten Python
```bash
python3 -m uvicorn main:app --host 0.0.0.0 --port 8001 --reload
```

## 9. Verificar Configuración del Servidor Web
Asegúrate de que el servidor web (Apache, Nginx, etc.) esté configurado correctamente para servir el proyecto Laravel. Si estás usando el servidor web integrado de Laravel para pruebas, puedes iniciarlo con:

```bash
php artisan serve
```

Esto levantará un servidor local en http://localhost:8000.

