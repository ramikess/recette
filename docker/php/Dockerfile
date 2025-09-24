FROM php:8.4-fpm

RUN echo '-= BUILD PHP IMAGE =-'

# Installer dépendances système et librairies nécessaires aux extensions PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libfreetype6-dev \
        libxml2-dev \
        libsqlite3-dev \
        libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        intl \
        pdo_mysql \
        pdo_sqlite \
        pdo_pgsql \
        pgsql \
        gd \
        soap \
        sockets \
        zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer (dernière version stable)
RUN curl -sS https://getcomposer.org/installer | php \
    -- --install-dir=/usr/local/bin --filename=composer

# Set timezone
RUN ln -snf /usr/share/zoneinfo/Europe/Paris /etc/localtime

WORKDIR /var/www/symfony

CMD ["php-fpm"]
