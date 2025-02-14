FROM composer:2.0

WORKDIR /app

RUN apk add --no-cache \
    php8-pdo \
    php8-pdo_mysql \
    php8-mysqli \
    mysql-client \
    libpng-dev

RUN docker-php-ext-install pdo pdo_mysql gd

COPY --chmod=777 ./entrypoint_docker.sh .
ENTRYPOINT ["sh", "entrypoint_docker.sh"]