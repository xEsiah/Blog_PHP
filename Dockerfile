# Étape 1 : Utiliser une image PHP avec Apache
FROM php:8.2-apache

# Étape 2 : Installer les extensions nécessaires (ex: PDO MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Étape 3 : Copier le contenu du projet dans le dossier Apache
COPY . /var/www/html/

# Étape 4 : Copier et installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Étape 5 : Installer les dépendances PHP
WORKDIR /var/www/html/
RUN composer install

# Étape 6 : Activer le module Apache rewrite (utile pour le routing si besoin)
RUN a2enmod rewrite

# Étape 7 : Changer le document root vers le dossier `public/`
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Mise à jour du VirtualHost pour Apache
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80
