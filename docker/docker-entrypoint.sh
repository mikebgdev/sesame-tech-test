#!/bin/sh
set -e

echo "Ejecutando el script docker-entrypoint.sh..."

if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ -f composer.json ]; then
	echo "Ejecutando comandos..."

	composer install --prefer-dist --no-progress --no-interaction

	chmod -R 777 public
	chmod -R 777 var/cache/

	php bin/console cache:clear --env=prod

	echo "bin/console lexik:jwt:generate-keypair --overwrite --no-interaction"
	php bin/console lexik:jwt:generate-keypair --overwrite --no-interaction

	echo "bin/console doctrine:database:create --if-not-exists --no-interaction"
	php bin/console doctrine:database:create --if-not-exists --no-interaction

	echo "bin/console doctrine:migrations:migrate --no-interaction"
	php bin/console doctrine:migrations:migrate --no-interaction
fi

exec docker-php-entrypoint "$@"
