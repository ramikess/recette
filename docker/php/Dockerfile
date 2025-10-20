FROM php:8.4-fpm

RUN echo '-= BUILD PHP IMAGE =-'

# Installer dépendances système
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
        pkg-config \
        libssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        intl \
        gd \
        soap \
        sockets \
        zip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    # Nettoyage pour réduire la taille de l’image
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*


# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php \
    -- --install-dir=/usr/local/bin --filename=composer

# Set timezone
RUN ln -snf /usr/share/zoneinfo/Europe/Paris /etc/localtime

RUN mkdir -p var/cache var/log \
 && chown -R www-data:www-data var

WORKDIR /var/www/symfony

CMD ["php-fpm"]
