FROM unit:1.34.1-php8.3

RUN apt-get update  \
    && apt-get install -y git unzip libzip-dev libicu-dev libpq-dev libxslt-dev  \
    libfreetype-dev  \
    libjpeg62-turbo-dev  \
    libpng-dev  \
    cmake \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip intl pdo_mysql xsl opcache gd  \
    && pecl install xdebug-3.3.1  \
    && docker-php-ext-enable xdebug

WORKDIR /app

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY ./nginx-unit/conf.json /docker-entrypoint.d/conf.json
COPY ./nginx-unit/php.ini /usr/local/etc/php/php.ini
