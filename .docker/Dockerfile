FROM php:8.4-fpm AS base

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo mbstring mysqli xml

COPY . /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chown -R www-data:www-data /var/www

EXPOSE 9000

CMD ["php-fpm"]

FROM base AS db-instance

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install \
    pdo_mysql