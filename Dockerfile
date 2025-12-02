FROM php:8.4-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       default-mysql-client \
       libonig-dev \
       && docker-php-ext-install -j$(nproc) pdo_mysql mysqli mbstring \
       && a2enmod rewrite headers \
       && apt-get clean \
       && rm -rf /var/lib/apt/lists/*
