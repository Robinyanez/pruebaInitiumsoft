# Prueba

## Instalación

- composer install
- npm install
- npm run dev (en caso de que falle volver a ejecutar)
- cp .env.example .env
- php artisan key:generate

## Base de Datos

- Crear base de datos con:
- nombre cualquiera
- charset => utf8
- collation => utf8_general_ci

## Observaciones

- Luego de crear la base de datos actualizar los datos de conexión en el archivo **.env** y correr el comando: **php artisan migrate** esto es para crear las tablas necesarias para el proyecto.
- La logica esta en el archivo **CreateUser** de Liveware.

