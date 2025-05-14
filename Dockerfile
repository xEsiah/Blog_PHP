FROM php:8.2-apache

# Installer les extensions nécessaires (dont PostgreSQL)
RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev libonig-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip

# Activer mod_rewrite
RUN a2enmod rewrite

# Changer le document root vers /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier d'abord les fichiers Composer pour utiliser le cache Docker
COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --no-scripts

# Ensuite, copier tout le reste du projet
COPY . .

# Réinstaller au cas où d'autres fichiers influent sur les dépendances
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

EXPOSE 80
