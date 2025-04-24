FROM php:8.1-apache

RUN apt-get update

RUN apt-get install -y \
    pkg-config \
    libzip-dev \
    supervisor

RUN pecl install redis

RUN docker-php-ext-install \
    mysqli \
    pdo_mysql \
    zip

RUN docker-php-ext-enable redis

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html

RUN /usr/local/bin/composer install --no-interaction --prefer-dist --optimize-autoloader
RUN php artisan key:generate

RUN a2enmod rewrite

COPY ./docker/supervisor /etc/supervisor/conf.d
CMD ["sh", "./docker/entrypoint.sh"]

