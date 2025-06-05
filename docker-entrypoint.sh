#!/bin/sh
set -e

# wait for MySQL to be ready
if [ -n "$DB_HOST" ]; then
  max=30
  until mysqladmin ping -h "$DB_HOST" -u"$DB_USER" -p"$DB_PASS" --silent; do
    max=$((max-1))
    if [ $max -le 0 ]; then
      echo "MySQL not available" >&2
      exit 1
    fi
    echo "Waiting for MySQL..."
    sleep 2
  done

  exists=$(mysql -h "$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -sse "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_NAME' AND table_name='products';")
  if [ "$exists" -eq 0 ] || [ "$(mysql -h "$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -sse "SELECT COUNT(*) FROM products;")" -eq 0 ]; then
    echo "Importing initial database data..."
    mysql --default-character-set=utf8mb4 -h "$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < /var/www/html/db/init.sql
  fi
fi

exec apache2-foreground
