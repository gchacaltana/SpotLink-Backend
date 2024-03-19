# SpotLink Backend Application

Repositorio oficial del API backend de SpotLink

## Overview

SpotLink es una herramienta para acortar URL y generar enlaces cortos. El acortador de URL permite crear un enlace acortado que facilita compartirlo.

## Logo

![image Logo Aplicación](https://gonzch.com/img/cloud/spotlink/logo_spotlink_120.png)

## Repositorios

El proyecto se compone de dos repositorios:

* Repositorio: [SpotLink Frontend](https://github.com/gchacaltana/SpotLink-Frontend)

* Repositorio: [SpotLink Backend](https://github.com/gchacaltana/SpotLink-Backend)

## Tech Stack

* Frontend: React JS v18.2. Coding in Typescript v4.8.2.
* Backend: Laravel v10. Coding en PHP v.8.2.0.
* Database Server: PostgreSQL v14
* Cache : Redis v6.2.14
* Web Proxy: Nginx v1.18.0

## Arquitectura

El siguiente diagrama muestra la interacción entre los componentes.

![image Application Architecture](https://gonzch.com/img/cloud/spotlink/spotlink_architecture.jpg)

# Backend Application

## Requisitos

* PHP ^8.1
* Composer v2.4.1
* Docker 20.10.17

## Dependencias

```json
"guzzlehttp/guzzle": "^7.2",
"laravel/framework": "^10.10",
"laravel/sanctum": "^3.3",
"laravel/tinker": "^2.8",
"php-open-source-saver/jwt-auth": "^2.2",
"predis/predis": "^2.2"
```

## Documentación

* URL: https://api.spotlink.gonzch.com

![image API Documentation](https://gonzch.com/img/cloud/spotlink/spotlink_api_documentation.jpg)

## Settings

1. Crear archivo .env a partir del archivo de ejemplo: env.example
2. Modificar las variables de conexión a la base de datos Postgres y Redis.

## Desplegar en entorno desarrollo

1. Clonar proyecto
2. Instalar dependencias

```bash
composer install
```

3. Desplegar aplicación

```bash
# Linux
./run_dev.sh

# Windows
sh run_dev.sh
```

## Unit Testing