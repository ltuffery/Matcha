FROM composer:latest

WORKDIR /api

COPY composer.json composer.json

RUN composer dump-autoload

CMD ["php", "-S", "0.0.0.0:3000", "-t", "public/"]