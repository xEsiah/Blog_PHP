# Utilise l'image officielle PHP avec Apache
FROM php:8.2-apache

# Copie les fichiers dans le dossier web de Apache
COPY . /var/www/html/

# Active mod_rewrite si nécessaire
RUN a2enmod rewrite

# Installe les dépendances via Composer si besoin
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install

# Expose le port 80
EXPOSE 80
