# Prueba técnica para Sesame HR

[![Estado de construcción](https://drone.mikebgdev.com/api/badges/mikebgdev/sesame-tech-test/status.svg)](https://drone.mikebgdev.com/mikebgdev/sesame-tech-test)

## Instalación

1. Crear .env.local y añadir los siguientes parámetros:

```dotenv
APP_ENV=prod
APP_DEBUG=0

APP_SECRET=TU_SECRETO_DE_APP
DATABASE_URL=URL_DE_TU_BASE_DE_DATOS
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0

###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=65be8a83d499a121cfc914a87ea82538d593cbb792c2a87d7c9a869bd3ea5f26
###< lexik/jwt-authentication-bundle ###
```

2. Ejecutar docker-compose

```console
docker compose up -d
```

Cuando el contenedor de PHP se levante se ejecutará [docker-entrypoint.sh](./docker/docker-entrypoint.sh)

Este archivo se encarga de lanzar los siguientes comandos:

```console
php bin/console lexik:jwt:generate-keypair --overwrite --no-interaction
```

```console
php bin/console doctrine:database:create --if-not-exists --no-interaction
```

```console
php bin/console doctrine:migrations:migrate --no-interaction
```

3. Acceder a /api/doc para hacer las pruebas

        http://127.0.0.1:8000/api/doc

## Pruebas unitarias

1. Acceder al contenedor

```console
docker exec -it php-sesame sh
```

2. Comandos para ejecutar las pruebas unitarias

Con cobertura:
```console
php vendor/bin/phpunit --coverage-html build/coverage
```

Al generar las pruebas con cobertura podemos acceder a los resultados [Cobertura](http://localhost:8000/coverage/)

Sin cobertura:
```console
php vendor/bin/phpunit
```
