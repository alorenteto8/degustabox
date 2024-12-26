FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    libmariadb-dev \
    curl \
    gnupg \
    lsb-release \
    libpq-dev \
    zip \
    unzip \
    libzip-dev

RUN docker-php-ext-install pdo pdo_mysql # Para MySQL/MariaDB

RUN docker-php-ext-install zip

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -

RUN apt-get install -y nodejs

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY . /app

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]