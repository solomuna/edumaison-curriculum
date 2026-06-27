FROM php:8.4-fpm-alpine

# Dépendances système + build deps
RUN apk add --no-cache \
    bash git curl \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    libxml2-dev oniguruma-dev libzip-dev icu-dev \
    postgresql-dev \
    autoconf gcc g++ make linux-headers \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) \
    pdo pdo_pgsql pgsql \
    gd bcmath intl mbstring zip exif pcntl opcache \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && apk del autoconf gcc g++ make linux-headers \
 && rm -rf /var/cache/apk/* /tmp/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-curriculum.ini

WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
