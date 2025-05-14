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

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier uniquement composer.json et composer.lock d'abord pour utiliser le cache Docker
WORKDIR /var/www/html
COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --no-scripts

# Ensuite, copier le reste du projet
COPY . .

# Réinstaller si des fichiers ont changé après copie
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

EXPOSE 80
