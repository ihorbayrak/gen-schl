FROM php:8.2-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libgd-dev \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install -j$(nproc) gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

ADD docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

RUN mkdir -p /run/supervisor && \
    mkdir -p /var/log/supervisor && \
    chown -R www:www-data /run/ && \
    chown -R www:www-data /var/www/storage && \
    chmod -R ug+w /var/www/storage && \
    chown -R www:www-data /var/log/supervisor/ && \
    chown -R www:www-data /etc/supervisor/conf.d/

USER root

RUN chmod -R 777 storage && \
    chmod -R 777 composer.lock && \
    chmod -R 775 bootstrap/cache && \
    chown -R www:www-data /var/www/storage/logs

USER www

EXPOSE 9000
ENTRYPOINT ["supervisord", "-c", "/etc/supervisor/conf.d/supervisor.conf"]
