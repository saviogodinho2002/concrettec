#!/bin/bash
set -e

# Garante que as permissões dos diretórios de escrita estejam corretas na inicialização
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache

# Executa o comando fornecido (normalmente php-fpm)
exec "$@"
