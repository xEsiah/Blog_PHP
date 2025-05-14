FROM php:8.2-apache

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Activer mod_rewrite
RUN a2enmod rewrite

# Changer le document root vers /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Copier les fichiers du projet
COPY . /var/www/html/

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer les dépendances PHP
WORKDIR /var/www/html/
RUN composer install --no-interaction --prefer-dist

EXPOSE 80
