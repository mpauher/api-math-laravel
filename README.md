# Configuración del Backend (API en Laravel) 

Antes de ejecutar la aplicación de escritorio (.exe), es necesario instalar, configurar y levantar el servidor backend. Este backend está desarrollado con **Laravel** y utiliza **MySQL** como base de datos.

# Descripción general

Este proyecto corresponde a la API utilizada por la aplicación de asignación de turnos. La API gestiona los trabajadores, turnos y asignaciones, utilizando PHP con el framework **Laravel**, una base de datos **MySQL** y el modelo arquitectónico MVC. 

# Requisitos 

Asegúrate de tener instaladas las siguientes herramientas: 

- **PHP 8+** 
- **Composer 2+** 
- **MySQL 8+** 
- **phpMyAdmin** (opcional pero recomendado para manejar la base de datos)

# Instalación 

1. Clonar el repositorio

        git clone https://github.com/mpauher/api-math-laravel.git
    
    Ingresar a la carpeta del proyecto:
        cd api-math-laravel

2. Instalar dependencias
    
        composer install

3. Copiar el archivo de entorno

    cp .env.example .env

4. Configurar el archivo .env

    Abrir el archivo: nano .env Y completar los datos de la base de datos según tu configuración local:
        DB_DATABASE=nombre_de_tu_base
        DB_USERNAME=tu_usuario_mysql
        DB_PASSWORD=tu_contraseña_mysql

    Nota: Nunca compartas tu archivo .env.
    Cada persona debe configurar sus propias credenciales locales.

5. Crear la base de datos

    Abrir http://localhost/phpmyadmin
    Iniciar sesión
    Crear una nueva base de datos con el nombre usado en el .env

6. Ejecutar migraciones y seeders


        php artisan migrate --seed
    Esto creará las tablas y llenará datos iniciales como cargos y turnos.

7. Levantar el servidor backend
    
        php artisan serve --port=8001

    Debes mantener el servidor encendido antes de abrir el archivo .exe de la aplicación Flutter.

## Archivos Opcionales

- Colección de Postman: /collection.postman_collection.json
- Tecnologías Utilizadas
- Laravel
- Composer
- MySQL

## Autores
María Paula Hernández
Jairo David Moreno



