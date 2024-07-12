FROM php:8.2-fpm

ARG user
ARG uid

RUN apt update && apt install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev

RUN apt clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user

RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www

USER $user


# FROM php:8.0-fpm

# # Install dependencies
# RUN apt-get update && apt-get install -y \
#     libpng-dev \
#     libjpeg-dev \
#     libfreetype6-dev \
#     libonig-dev \
#     libxml2-dev \
#     zip \
#     unzip \
#     git \
#     && docker-php-ext-configure gd --with-freetype --with-jpeg \
#     && docker-php-ext-install gd mbstring pdo pdo_mysql pdo_pgsql xml pcntl

# # Install Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# WORKDIR /var/www/html

# # Copy existing application directory contents
# COPY ./laravel-app /var/www/html

# # Copy existing application directory permissions
# RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# # Expose port 9000 and start php-fpm server
# EXPOSE 9000
# CMD ["php-fpm"]
