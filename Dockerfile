FROM php:7.4-fpm

# Instala dependências do sistema e extensões PHP necessárias para o Laravel e MySQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Limpa o cache do apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões do PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Ajusta permissões
RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]
