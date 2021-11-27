#!/bin/sh
set -eu

ENV_FILE=".env"
MYSQL_HOST=$(cat $ENV_FILE | grep "DB_HOST" | cut -d"=" -f2)
MYSQL_PORT=$(cat $ENV_FILE | grep "DB_PORT" | cut -d"=" -f2)

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

npm install && npm rebuild node-sass && npm run dev

php artisan serve --host 0.0.0.0

