FROM php:8.2-fpm

ARG USER=1000

ARG PROJECT_NAME=bepositive

RUN docker-php-source extract \
&& apt-get update \
&& apt-get install -y \
    libicu-dev \
    libgmp-dev \
    libssl-dev \
    sudo \
    wget \
    git \
    unzip \
   	zip \
    unixodbc-dev \
    gnupg \
    vim \
    nano \
&& adduser --disabled-password --gecos '' -u $USER $PROJECT_NAME \
&& echo "${PROJECT_NAME} ALL=(ALL) NOPASSWD: ALL" > /etc/sudoers.d/$PROJECT_NAME \
&& chmod 0440 /etc/sudoers.d/$PROJECT_NAME \
&& apt-get install -y libicu-dev && docker-php-ext-install intl \
&& docker-php-ext-install opcache \
&& apt-get install -y default-mysql-client \
&& docker-php-ext-install bcmath pdo_mysql \
&& docker-php-ext-install mysqli \
&& apt-get install -y libzip-dev && docker-php-ext-install zip \
&& docker-php-ext-enable mysqli \
&& pecl install redis mailparse \
&& curl --fail -sSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
&& curl --fail -sOL https://gordalina.github.io/cachetool/downloads/cachetool-7.1.0.phar && chmod +x cachetool-7.1.0.phar && mv cachetool-7.1.0.phar /usr/local/bin/cachetool \
&& docker-php-source delete \
