#!/bin/sh

# Copy from the official entrypoint https://github.com/docker-library/php/blob/9abc1efe542b56aa93835e4987d5d4a88171b232/7.1/fpm/alpine/docker-php-entrypoint
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

# Gid 82 is www-data. Uid 1000 is usually the Uid of the user on the host that the mounted files are owned by.
chown -R 1000:33 /code

exec "$@"
