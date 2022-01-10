#!/bin/sh
set -eu

MYSQL_HOST=db
MYSQL_PORT=3306

composer install

echo "Wait for MySQL to be ready"
while true;
do
  nc -z $MYSQL_HOST $MYSQL_PORT && break
  sleep 1
done

MIGRATE_NOT_MIGRATED_STATUS=$(php artisan migrate:status | grep "not found" | wc -l)
if [ $MIGRATE_NOT_MIGRATED_STATUS = "1" ];
then
  php artisan migrate --seed
fi

npm install && npm run production

exec "php-fpm"
