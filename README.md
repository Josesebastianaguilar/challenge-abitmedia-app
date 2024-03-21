# challenge-abitmedia-app

Autor: José Aguilar
Datos de contacto:

email: jose.aguilar.siva@outlook.com
teléofono: +593999077465

# Resumen

Este proyecto es una API REST de Laravel v11 para gestionar licencias y precios de productos de software y servicios. Proporciona endpoints para crear, actualizar, eliminar y recuperar licencias y precios de productos de software y servicios. La API está construida utilizando PHP versión 8.2 o superior (se utilizó 8.3.4 en el desarrollo). Se recomienda correr el proycto en un sistema Linux.

# Enunciado del proyecto:

## Introducción:

Una empresa de venta de software y servicios de soporte, requiere implementar un sistema para
poder gestionar su oferta de productos, los mismos detallados a continuación:

## Software:

• Antivirus ($5 para Windows, $7 para Mac), en existencia 10 para cada S.O.
respectivamente.
• Ofimática ($10 para Windows, $12 para Mac), en existencia 20 para cada S.O.
respectivamente.
• Editor de video ($20 para Windows, $22 para Mac), en existencia 30 para cada S.O.
respectivamente

## Servicios:

• Formateo de computadores ($25)
• Mantenimiento ($30)
• Hora de soporte en software ($50)

## Requerimientos funcionales
1. El usuario del sistema podría generar operaciones CRUD, tanto para software como
servicios.
2. Al agregar una licencia de software, debería generarse automáticamente un serial de 100
caracteres asignado a la misma, este serial no puede repetirse.
3. Tanto software como servicios deben tener un identificador SKU irrepetible de 10
caracteres, ingresado manualmente.
4. La API debe ser segura.

## Notas importantes:
• Buena documentación en archivo Readme, explicando como instalar y ejecutar el
proyecto.
• Se le evaluará para las funcionalidades solicitadas en este documento. Otras iniciativas no
se tendrán en cuenta para mejorar su puntuación final.

## Requisitos y herramientas del proyecto:

• challenge-abitmedia-app debe se el nombre de su proyecto.
• Debe desarrollarse en cualquiera de los frameworks PHP: Laravel o Yii.
• Implemente la persistencia de datos utilizando la base de datos MySQL.
• Debe usar git como control de versión, cree el repositorio en su cuenta GitHub o Bitbucket
con acceso público.
• Enviar URL del repositorio con indicaciones de como ejecutarlo
Url del repositorio
https://github.com/Josesebastianaguilar/challenge-abitmedia-app.git

# Infraestructura

## Modelo User

El modelo User representa a los usuarios de la aplicación. Este modelo está ubicado en el archivo User.php dentro del directorio app/Models.

### Estructura del Modelo

El modelo User tiene los siguientes atributos y métodos:

<strong>Atributos Fillable:</strong> Los atributos que se pueden asignar en masa mediante la función create o update del Eloquent ORM están definidos en la propiedad $fillable. En este caso, los atributos incluyen name, email y password.

<strong>Atributos Ocultos:</strong> Los atributos que no se incluirán en la serialización JSON del modelo están definidos en la propiedad $hidden. En este caso, solo el atributo password está oculto.

<strong>Casting de Atributos:</strong> Los atributos que deben ser convertidos a tipos de datos específicos están definidos en el método casts. En este caso, el atributo password se convierte en un valor hash.

### Relaciones
El modelo User no tiene relaciones definidas en este archivo. Sin embargo, puedes definir relaciones con otros modelos según las necesidades de tu aplicación.

### Métodos Adicionales
Este modelo utiliza ciertos traits para proporcionar funcionalidades adicionales:

<strong>HasApiTokens:</strong> Este trait proporciona métodos para autenticar a los usuarios utilizando tokens de Sanctum.
<strong>HasFactory:</strong> Este trait proporciona métodos para generar instancias de modelo para pruebas o semillas de base de datos.
<strong>Notifiable:</strong> Este trait permite que el modelo envíe notificaciones mediante el sistema de notificaciones de Laravel.

## SoftwareProduct (Porducto de Software)

El modelo SoftwareProduct representa un producto de software en la aplicación. Este modelo proporciona métodos para acceder y manipular información relacionada con los productos de software.

### Estructura del Modelo

El modelo SoftwareProduct se encuentra en el directorio app/Models/SoftwareProduct.php. 

### Atributos Principales

name: El nombre del producto de software.
sku: El código SKU del producto de software, que se utiliza para identificar de manera única el producto (10 caracteres).

### Relaciones

licenses(): Esta función define la relación entre el producto de software y sus existencias o licencias asociadas. Retorna una consulta para obtener las licencias relacionadas con el producto de software.

## Modelo Service

El modelo Service representa un servicio en la aplicación. Este modelo proporciona métodos para acceder y manipular información relacionada con los servicios.

### Estructura del Modelo

El modelo Service se encuentra en el directorio app/Models/Service.php

### Atributos Principales

<strong>name:</strong> El nombre del servicio.
<strong>sku:</strong> El código SKU del servicio, que se utiliza para identificar de manera única el servicio (10 caracteres).
<strong>price:</strong> El precio del servicio.

## Modelo OperativeSystem

El modelo OperativeSystem representa un sistema operativo en la aplicación. Este modelo proporciona métodos para acceder y manipular información relacionada con los sistemas operativos.

### Estructura del Modelo

El modelo OperativeSystem se encuentra en el directorio app/Models/OperativeSystem.php.

### Atributos Principales

<strong>name:</strong> El nombre del sistema operativo.
<strong>slug:</strong> El slug del sistema operativo, que se utiliza para identificar de manera única el sistema operativo y en URLs amigables.

## Modelo SoftwareProductLicense

El modelo SoftwareProductLicense representa una existencia o licencia de un producto de software en la aplicación. Este modelo proporciona métodos para acceder y manipular información relacionada con las licencias de productos de software.

### Estructura del Modelo

El modelo SoftwareProductLicense se encuentra en el directorio app/Models/SoftwareProductLicense.php.

### Atributos Principales

<strong>serial:</strong> El número de serie único de la licencia, 100 caracteres, autogenerado.
<strong>software_product_sku:</strong> El SKU (Stock Keeping Unit) del producto de software al que está asociada la licencia.
<strong>operative_system_slug:</strong> El slug del sistema operativo al que está asociada la licencia.

### Métodos y Relaciones Adicionales

<strong>softwareProduct():</strong> Método para obtener el producto de software asociado a la licencia.
<strong>operativeSystem():</strong> Método para obtener el sistema operativo asociado a la licencia.
<strong>price():</strong> Método para obtener el precio del producto de software asociado a la licencia.
<strong>generateSerialNumber():</strong> Método para generar un número de serie para la licencia.
<strong>getSoftwareProductAttribute():</strong> Método de acceso para obtener el nombre del producto de software asociado a la licencia.
getPriceAttribute(): Método de acceso para obtener el precio del producto de software asociado a la licencia.

## Modelo SoftwareProductPrice

El modelo SoftwareProductPrice representa el precio de un producto de software asociado a un sistema operativo en la aplicación. Este modelo proporciona métodos para acceder y manipular información relacionada con los precios de los productos de software.

### Estructura del Modelo

El modelo SoftwareProductPrice se encuentra en el directorio app/Models/SoftwareProductPrice.php.

### Atributos Principales

<strong>value:</strong> El precio del producto de software.
<strong>software_product_sku:</strong> El SKU (Stock Keeping Unit) del producto de software al que está asociado el precio.
<strong>operative_system_slug:</strong> El slug del sistema operativo al que está asociado el precio.

### Métodos y Relaciones Adicionales

<strong>softwareProduct():</strong> Método para obtener el producto de software asociado al precio.
<strong>operativeSystem():</strong> Método para obtener el sistema operativo asociado al precio.
<strong>getSoftwareProductAttribute():</strong> Método de acceso para obtener el nombre del producto de software asociado al precio.
<strong>getOperativeSystemAttribute():</strong> Método de acceso para obtener el nombre del sistema operativo asociado al precio.

# Montaje del proyecto

## Sistema Operativo Recomendado

Para desarrollo y despliegue, se recomienda Ubuntu debido a su compatibilidad con PHP, MySQL y otras dependencias requeridas.

## Requisitos

PHP 8.2 o superior
Base de datos MySQL
Composer

## Módulos de PHP Requeridos

Extensión OpenSSL PHP
Extensión PDO PHP
Extensión Mbstring PHP
Extensión Tokenizer PHP
Extensión XML PHP
Extensión Ctype PHP
Extensión JSON PHP
Extensión BCMath PHP

## Pasos de Instalación

Instalar Php (v8.2 o superior) y los módulos requeridos
Documentación oficial: https://www.php.net/docs.php

Instalar el manejador de bases de datos (mysql o el que se vaya a utilizar).
Documentación oficial: https://dev.mysql.com/doc/

Instalar Composer:
Documentación oficial: https://getcomposer.org/download/

Siga las instrucciones de instalación de cada uno de estos componentes

## Creación de base de datos

Una vez instalado el manejador de base de datos, se debe crear una base de datos para el projecto. A continuación, se explica como hacerlo en <code>mysql</code>:

<code>
    CREATE DATABASE database_name
    CREATE USER 'new_user'@'localhost' IDENTIFIED BY 'password';
    GRANT ALL PRIVILEGES ON database_name. * TO 'username'@'localhost'
</code>

## Clonar el Repositorio:

El repositorio está en github con visibilidad pública. Para clonar utilizar el siguiente comando:

<code> git clone https://github.com/Josesebastianaguilar/challenge-abitmedia-app.git </code>


## Establecer Permisos de archivos y directorios del proyecto:

Establezca permisos adecuados para directorios y archivos por razones de seguridad.
Se deja un enlace para referencia de buenas prácticas:
https://tecadmin.net/laravel-file-permissions/

## Digirirse a la carpeta del proyecto

<code> cd /patth/to/the/project </code>

## Configurar el archivo de entorno:

<code> cp .env.example .env </code>

En este archivo <code>.env</code> se debe configurar las credenciales de acceso a la base de datos en los siguientes campos:

<code>
    DB_CONNECTION=mysql
    DB_HOST=db_host_ip
    DB_PORT=db_host_port
    DB_DATABASE=db_name
    DB_USERNAME=db_user
    DB_PASSWORD=db_user_pass
</code>

## Instalar Dependencias:

Se puede instalar las dependencias del proyecto con el siguiente comando:

<code>
    composer install
</code>

## Generar Clave de Aplicación:

<code>
    php artisan key:generate
</code>

## Enlazar Almacenamiento:

<code>
    php artisan storage:link
</code>

## Migrar la base de datos según los esquemas de la infraestructura:

Utilizar el siguiente comando:

<code>
    php artisan migrate
</code>

## Llenar algunas tablas de la base de datos con Seeders:

Se han establecido algunos registros que pueden ser creados mediante los seeders con los siguientes comandos:

<code>
    //Crear un usuarios de pruebas
    php artisan db:seed --class=DatabaseSeeder
    //Llenar tabla de Sistemas Operativos con Windows y Mac
    php artisan db:seed --class=OperativeSystemSeeder
    //Llenar tabla de Productos de software con los establecidos en el enunciado del reto
    php artisan db:seed --class=SoftwareProductSeeder
    //Llenar tabla de Servicios con los establecidos en el enunciado del reto
    php artisan db:seed --class=ServiceSeeder
</code>

De manera alternativa, se ha enviado un respaldo de una base de datos con todos los productos, servicios, sistemas operativos, existencias (licencias) y precios para poder importarlo a su base de datos. Para hacerlo se puede utilizar el siguiente comando. El archivo de la base de datos enviado por correo electrónico se llama: "challenge-abitmedia-app-db.zip". Al descomprimirlo,
en su interior se encuentra otro archivo denominado "challenge-abitmedia-app-db.sql" a partir del cual se puede restaurar la base de datos

<code>
    mysql -u [username] [database_name] -p[password] < [dump_file.sql]
</code>

De igual manera si no se llena las tablas con los seeders o restaurando el archivo enviado. Se puede crearlos directamente al probar las rutas específicadas en el espacio de trabajo compartido en Postman al correo gerencia@abitmedia.cloud

## Ejecutar la Aplicación:

Para ejecutar la aplicación se puede utilizar el comando:

<code>
    php artisan serve
</code>

Generalmente este comando va a activar el puerto 8000 para probar el servidor, sin embargo de acuerdo con sus configuraciones locales, puede ser otro puerto. En las rutas compartidas en Postman se puede modificar el url base y puerto para adaptarse a su entorno local.

De manera alternativa, se puede instalar servidores para ejecutar la aplicación
<strong>Apache:</strong> Consulte la documentación de Laravel para la configuración de Apache: https://ubuntu.com/tutorials/install-and-configure-apache#1-overview
<strong>Nginx:</strong> Consulte la documentación de Laravel para la configuración de Nginx: https://nginx.org/en/docs/

## Documentación de Rutas de la API
Para obtener documentación detallada sobre cada ruta de la API, consulte el espacio de trabajo compartido en Postman. Si no puede acceder a este, comuníquese con el autor del proyecto a los datos específicados en el inicio de este documento. También puede revisar las definiciones de ruta y los métodos de controlador correspondientes en el código fuente del proyecto. Cada ruta está documentada con su propósito, parámetros esperados y posibles respuestas.

## Documentación de Laravel

Se recomienda revisar la documentación de Laravel para una mejor gestión del proyecto:
https://laravel.com/docs/11.x/readme

# challenge-abitmedia-app

Author: José Aguilar
Contact Information:

email: jose.aguilar.siva@outlook.com
phone: +593999077465

# Summary

This project is a Laravel v11 REST API for managing software licenses and prices, as well as services. It provides endpoints for creating, updating, deleting, and retrieving software licenses, prices, and services. The API is built using PHP version 8.2 or higher (8.3.4 was used during development). Running the project on a Linux system is recommended.

# Project Statement:
## Introduction:

A company selling software and support services needs to implement a system to manage its product offerings, which are detailed below:

## Software:

Antivirus ($5 for Windows, $7 for Mac), with 10 in stock for each OS, respectively.
Office Suite ($10 for Windows, $12 for Mac), with 20 in stock for each OS, respectively.
Video Editor ($20 for Windows, $22 for Mac), with 30 in stock for each OS, respectively.
## Services:

Computer Formatting ($25)
Maintenance ($30)
Software Support Hour ($50)
## Functional Requirements:

The system user should be able to perform CRUD operations for both software and services.
When adding a software license, a 100-character serial should be automatically generated and assigned to it; this serial cannot be repeated.
Both software and services must have a unique 10-character SKU identifier entered manually.
The API must be secure.

## Important Notes:

Good documentation in a Readme file, explaining how to install and run the project.
You will be evaluated based on the functionalities requested in this document. Other initiatives will not be considered to improve your final score.
Project Requirements and Tools:
The project name must be "challenge-abitmedia-app."
It must be developed in either Laravel or Yii PHP frameworks.
Implement data persistence using the MySQL database.
Use git for version control; create the repository on your GitHub or Bitbucket account with public access.
Send the repository URL with instructions on how to run it.

Repository URL:
https://github.com/Josesebastianaguilar/challenge-abitmedia-app.git

# Infrastructure

## User Model

The User model represents the application's users. This model is located in the User.php file within the app/Models directory.

### Model Structure

The User model has the following attributes and methods:

<strong>Fillable Attributes:</strong> Attributes that can be mass-assigned using the create or update function of the Eloquent ORM are defined in the $fillable property. In this case, the attributes include name, email, and password.

<strong>Hidden Attributes:</strong> Attributes that will not be included in the JSON serialization of the model are defined in the $hidden property. In this case, only the password attribute is hidden.

<strong>Attribute Casting:</strong> Attributes that need to be cast to specific data types are defined in the casts method. In this case, the password attribute is cast to a hashed value.

### Relationships

The User model does not have relationships defined in this file. However, you can define relationships with other models as per your application's needs.

### Additional Methods

This model uses certain traits to provide additional functionalities:

<strong>HasApiTokens:</strong> This trait provides methods to authenticate users using Sanctum tokens.
<strong>HasFactory:</strong> This trait provides methods to generate model instances for testing or database seeding.
<strong>Notifiable:</strong> This trait allows the model to send notifications using Laravel's notification system.

## SoftwareProduct Model

The SoftwareProduct model represents a software product in the application. This model provides methods to access and manipulate information related to software products.

### Model Structure

The SoftwareProduct model is located in the app/Models/SoftwareProduct.php directory.

### Main Attributes

<strong>name:</strong> The name of the software product.
<strong>sku:</strong> The SKU code of the software product, used to uniquely identify the product (10 characters).

### Relationships

<strong> licenses():</strong> This function defines the relationship between the software product and its associated stocks or licenses. It returns a query to fetch licenses related to the software product.

## Service Model

The Service model represents a service in the application. This model provides methods to access and manipulate information related to services.

### Model Structure

The Service model is located in the app/Models/Service.php directory.

### Main Attributes

<strong>name:</strong> The name of the service.
<strong>sku:</strong> The SKU code of the service, used to uniquely identify the service (10 characters).
<strong>price:</strong> The price of the service.

## OperativeSystem Model

The OperativeSystem model represents an operating system in the application. This model provides methods to access and manipulate information related to operating systems.

### Model Structure

The OperativeSystem model is located in the app/Models/OperativeSystem.php directory.

### Main Attributes

<strong>name:</strong> The name of the operating system.
<strong>slug:</strong> The slug of the operating system, used to uniquely identify the operating system and in friendly URLs.

## SoftwareProductLicense Model

The SoftwareProductLicense model represents a stock or license of a software product in the application. This model provides methods to access and manipulate information related to software product licenses.

### Model Structure

The SoftwareProductLicense model is located in the app/Models/SoftwareProductLicense.php directory.

### Main Attributes

<strong>serial:</strong> The unique serial number of the license, 100 characters, autogenerated.
<strong>software_product_sku:</strong> The SKU (Stock Keeping Unit) of the software product associated with the license.
<strong>operative_system_slug:</strong> The slug of the operating system associated with the license.

### Additional Methods and Relationships

<strong>softwareProduct():</strong> Method to get the software product associated with the license.
<strong>operativeSystem():</strong> Method to get the operating system associated with the license.
<strong>price():</strong> Method to get the price of the software product associated with the license.
<strong>generateSerialNumber():</strong> Method to generate a serial number for the license.
<strong>getSoftwareProductAttribute():</strong> Accessor method to get the name of the software product associated with the license.
<strong>getPriceAttribute():</strong> Accessor method to get the price of the software product associated with the license.

## SoftwareProductPrice Model

The SoftwareProductPrice model represents the price of a software product associated with an operating system in the application. This model provides methods to access and manipulate information related to software product prices.

### Model Structure

The SoftwareProductPrice model is located in the app/Models/SoftwareProductPrice.php directory.

### Main Attributes

<strong>value:</strong> The price of the software product.
<strong>software_product_sku:</strong> The SKU (Stock Keeping Unit) of the software product associated with the price.
<strong>operative_system_slug:</strong> The slug of the operating system associated with the price.

### Additional Methods and Relationships

<strong>softwareProduct():</strong> Method to get the software product associated with the price.
<strong>operativeSystem():</strong> Method to get the operating system associated with the price.
<strong>getSoftwareProductAttribute():</strong> Accessor method to get the name of the software product associated with the price.
<strong>getOperativeSystemAttribute():</strong> Accessor method to get the name of the operating system associated with the price.

# Project Setup

## Recommended Operating System

For development and deployment, Ubuntu is recommended due to its compatibility with PHP, MySQL, and other required dependencies.

## Requirements

PHP 8.2 or higher
MySQL database
Composer

## Required PHP Modules

OpenSSL PHP Extension
PDO PHP Extension
Mbstring PHP Extension
Tokenizer PHP Extension
XML PHP Extension
Ctype PHP Extension
JSON PHP Extension
BCMath PHP Extension
Installation Steps
Install PHP (v8.2 or higher) and the required modules.
Official documentation: https://www.php.net/docs.php

Install the database management system (MySQL or the one you intend to use).
Official documentation: https://dev.mysql.com/doc/

Install Composer:
Official documentation: https://getcomposer.org/download/

Follow the installation instructions for each of these components.

## Creating the Database

Once the database management system is installed, you need to create a database for the project. Below are the steps for creating a database in mysql:

<code>
    CREATE DATABASE database_name;
    CREATE USER 'new_user'@'localhost' IDENTIFIED BY 'password';
    GRANT ALL PRIVILEGES ON database_name.* TO 'username'@'localhost';
</code>

## Cloning the Repository

The repository is hosted on GitHub with public visibility. Use the following command to clone it:

<code>
    git clone https://github.com/Josesebastianaguilar/challenge-abitmedia-app.git
</code>

## Setting File and Directory Permissions for the Project

Set appropriate permissions for directories and files for security reasons. Here's a reference link for best practices: https://tecadmin.net/laravel-file-permissions/

## Navigate to the Project Folder

<code>
    cd /path/to/the/project
</code>

## Configure the Environment File

<code>
    cp .env.example .env
</code>

In the .env file, configure the database access credentials in the following fields:

<code>
    DB_CONNECTION=mysql
    DB_HOST=db_host_ip
    DB_PORT=db_host_port
    DB_DATABASE=db_name
    DB_USERNAME=db_user
    DB_PASSWORD=db_user_pass
</code>

## Install Dependencies:

You can install the project dependencies with the following command:

<code>
    composer install
</code>

## Generate Application Key:

<code>
    php artisan key:generate
</code>

## Link Storage:

<code>
    php artisan storage:link
</code>

## Migrate the Database According to Infrastructure Schemas:

Use the following command:

<code>
    php artisan migrate
</code>

## Populate Some Database Tables with Seeders (Optional):

Some records have been established that can be created using seeders with the following commands:

<code>
    # Create test users
    php artisan db:seed --class=DatabaseSeeder
    # Populate Operative System table with Windows and Mac
    php artisan db:seed --class=OperativeSystemSeeder
    # Populate Software Product table with those specified in the challenge statement
    php artisan db:seed --class=SoftwareProductSeeder
    # Populate Service table with those specified in the challenge statement
    php artisan db:seed --class=ServiceSeeder
</code>

Alternatively, a database backup has been sent with all the products, services, operating systems, stocks (licenses), and prices to be imported into your database. To do this, you can use the following command. The database file sent by email is named "challenge-abitmedia-app-db.zip". After decompressing it, inside you will find another file named "challenge-abitmedia-app-db.sql" from which you can restore the database:

<code>
    mysql -u [username] [database_name] -p[password] < [dump_file.sql]
</code>

Similarly, if you don't populate the tables with the seeders or by restoring the sent file, you can create them directly by testing the specified routes in the workspace shared on Postman to the email gerencia@abitmedia.cloud.

# Running the Application:

To run the application, you can use the following command:

<code>
    php artisan serve
</code>

Generally, this command will activate port 8000 to test the server, although according to your local settings, it may be another port. In the routes shared in Postman, you can modify the base URL and port to suit your local environment.

Alternatively, you can set up servers to run the application:

<strong>Apache:</strong> Refer to the Laravel documentation for Apache configuration: https://ubuntu.com/tutorials/install-and-configure-apache#1-overview
<strong>Nginx:</strong> Refer to the Laravel documentation for Nginx configuration: https://nginx.org/en/docs/

## API Route Documentation

For detailed documentation on each API route, refer to the shared workspace in Postman. If you cannot access this, please contact the project author using the contact information provided at the beginning of this document. You can also review the route definitions and corresponding controller methods in the project's source code. Each route is documented with its purpose, expected parameters, and possible responses.

## Laravel Docs

It is recommended to review the Laravel documentation for better project management:
https://laravel.com/docs/11.x/readme