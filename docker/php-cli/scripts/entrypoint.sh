#!/bin/sh

# Gid 82 is www-data. Uid 1000 is usually the Uid of the user on the host that the mounted files are owned by.
chown -R 1000:33 /code

exec "$@"
