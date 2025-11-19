#!/bin/sh

# Exit immediately if any command fails (for safety)
set -ex

# Create a symbolic link from the Docker secret (env_file) to the Laravel .env file location
ln -sf /run/secrets/env_file /var/www/html/.env

# Run Laravel database migrations forcefully (non-interactive, safe for production)
su -s /bin/sh www-data -c "php artisan migrate --force"

# Generate API documentation for Wafir project using Scribe
php artisan scribe:generate --config=scribe_wafir

# Generate API documentation for External project using Scribe
php artisan scribe:generate --config=scribe_external

# Start supervisord to run process manager with the specified config
supervisord -c /etc/supervisor.conf