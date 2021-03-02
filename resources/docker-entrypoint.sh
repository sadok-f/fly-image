#!/bin/sh
set -e

sed -i 's/$PORT/'"$PORT"'/g' /etc/nginx/conf.d/flyimage.conf

exec docker-php-entrypoint "$@"