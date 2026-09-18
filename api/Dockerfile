FROM dunglas/frankenphp:latest-alpine

WORKDIR /app

RUN apk add --no-cache \
    git \
    unzip \
    libpng-dev \
    mysql-client

RUN install-php-extensions \
    pdo_mysql \
    mysqli \
    gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY --chmod=777 ./entrypoint_docker.sh .
ENTRYPOINT ["sh", "entrypoint_docker.sh"]