#!/bin/sh

# Exit immediately if any command fails (for safety)
set -e

# Substitute the value of the APP_ENV environment variable in the nginx config template,
# and write the result to the actual nginx config file
envsubst '${APP_ENV}' < /etc/nginx/templates/nginx.conf.template > /etc/nginx/conf.d/default.conf

# Replace the current shell with the command passed to the script (usually starts nginx)
exec "$@"