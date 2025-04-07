FROM php:8.2-fpm

# Instala bibliotecas necessárias, incluindo suporte a JPEG e Freetype
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    poppler-utils

# Configura a GD com suporte a JPEG e Freetype
RUN docker-php-ext-configure gd \
    --with-jpeg=/usr/include/ \
    --with-freetype=/usr/include/

# Agora instala as extensões PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

WORKDIR /var/www

# Copia composer.lock e composer.json
COPY composer.lock composer.json /var/www/

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala as dependências do PHP sem scripts
RUN composer install --no-scripts --no-autoloader

# Copia o restante do código
COPY . /var/www

# Configura permissões corretas para todos os diretórios que precisam de escrita
RUN chown -R www-data:www-data /var/www \
    && find /var/www -type f -exec chmod 644 {} \; \
    && find /var/www -type d -exec chmod 755 {} \; \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/public \
    && touch /var/www/storage/logs/laravel.log \
    && chmod 664 /var/www/storage/logs/laravel.log \
    && chown -R www-data:www-data /var/www/storage/logs/laravel.log

# Gera autoload
RUN composer dump-autoload

# Cria um script de inicialização para garantir permissões corretas
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 9000

# Usa o script de entrada para configurações adicionais na inicialização
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
