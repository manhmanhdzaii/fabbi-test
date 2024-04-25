FROM php:8.3-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mariadb-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    nodejs \
    npm \
    unzip \
    git \
    make \
    nano \
    net-tools \
    curl \
    cron \
    supervisor

RUN npm install n -g && n 16 && apt purge -y nodejs npm
RUN npm install --global yarn
RUN apt-get install libzip-dev -y
RUN apt-get install libicu-dev -y

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis
RUN docker-php-ext-install pdo pdo_mysql bcmath gd zip exif pcntl intl redis
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN apt-get update && apt-get install -y procps

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create system user to run Composer and Artisan Commands
RUN mkdir -p /home/$user/.composer && \chown -R $user:$user /home/$user

# Create folder
RUN mkdir -p /var/www/app
COPY . /var/www/app

RUN chown -R www-data:www-data /var/www/app
RUN chmod -R 755 /var/www/app

# php0n
COPY ./php.ini /usr/local/etc/php/php.ini
COPY ./php.ini /usr/local/etc/php/

# Expose port 9000 and start php-fpm server
EXPOSE 9000

# Set working directory
WORKDIR /var/www/app
