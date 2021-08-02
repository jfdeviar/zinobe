# Prueba Fernando Devia
### Sistema de registro de personas

## Tecnología

La prueba se hizo teniendo en cuenta las siguientes versiones:
- PHP 8.0.0
- MySQL 8.0.21

## Características

- Permite registrar a cualquier persona
- Una vez registrada la persona, el sistema buscará por su identificación su nombre y apellido (Solo funciona con cedulas colombianas)
- Guarda estadísticas de registros y búsquedas de personas de manera exitosa
- Se puede registrar y filtrar registros por cualquiera de los campos asociados
- Se requiere teléfono celular en Colombia para el registro

Por favor leer la Bitacora de desarrollo en Bitacora.docx

## Instalación

### Frontend
Si bien el frontend tiene instaurado el paquete de npm, no es requerido. El frontend solamente hizó uso de JQuery, ChartJS, FontAwesome, Animate.css y Google Fonts, todos llamados desde CDN y Google Fonts respectivamente.

El Css local, fue compilado y mapeado por el Webstorm desde su archivo principal en formato SCSS y posteriormente minimizado.

Se debe configurar el endpoint del CRUD desde el index.html:30 en la CONST BASE_URL del JS

### Backend

Es necesario desde el composer hacer la instalación de las librerias correspondientes:

```sh
composer install
```

Se usaron las siguientes librerias:

- nikic/fast-route: Nos ayuda a establecer el proceso de rutas y las funcionas a las cuales se puede ejecutar
- catfan/medoo: Nos ayuda con un proceso mas sencillo en las peticiones y conexión con la base de datos
- ext-curl: Requerido para hacer peticiones por CURL
- splitbrain/php-cli: Nos ayuda con la configuración del archivo Cli.php de la carpeta raíz para la ejecución de distintos comandos
- Firebase/JWT: Nos ayuda con la encriptación del token de autenticación

Se usaron librerias no tan populares en el ambito general, pero si funcionales y livianas para el funcionamiento del código.

#### Configuración
El proyecto se puede configurar desde el CLI con el siguiente comando:

```sh
php cli.php setup --debug false --database_host localhost --database_name zinobe --database_user root --admin_email jfdeviar@gmail.com --admin_phone 3185241383
```

Con las configuraciones correspondientes, para mas información puede correr el comando por defecto que devolera la ayuda correspondiente:

#### Migración

Se dejo en el proyecto una configuración de la base de datos inicial y esta se puede correr desde el CLI

```sh
php cli.php migrate
```

#### Pruebas
Solo se dejo instalado el comando de pruebas que determina la conexión con CellVoz
```sh
php cli.php test:sms [phone]
```

Toda la información sobre comandos

```sh
php cli.php
```

## Puesta en marcha

El proyecto se estará montando el lunes 9 de agosto en el siguiente alojamiento WEB: (Estando a la espera a que el proveedor me cambie a php8)

deviafernando.com



