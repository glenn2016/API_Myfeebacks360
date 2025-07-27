FROM php:8.2-fpm

# Installer les dépendances système

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    git \
    unzip \
    libxml2-dev \
    curl \
    gnupg2 \
    lsb-release \
    && rm -rf /var/lib/apt/lists \
    && apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd/*


# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers de l'application
COPY . .

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer les dépendances PHP avec Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Exposer le port
EXPOSE 8000

# Lancer les commandes Laravel à l'exécution
CMD php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=8000
