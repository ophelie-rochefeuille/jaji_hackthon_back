FROM php:8.1-alpine

ARG port
ENV PORT=$port

WORKDIR /opt

RUN apk update \
    && apk add bash \
    && apk add --no-cache --virtual openssl libzip-dev zip unzip supervisor $PHPIZE_DEPS \
    postgresql-dev \
    && docker-php-ext-install bcmath pdo pdo_pgsql mysqli pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY composer.* ./

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
# Copie du code source de l'API dans le conteneur
COPY . /var/www/api

# Définition du répertoire de travail
WORKDIR /var/www/api

# Installation des dépendances de l'API
RUN composer install

# Exposition du port 8000 pour l'accès à l'API
EXPOSE 8000

# Exécution du serveur de développement de Symfony
CMD ["symfony", "server:start", "--allow-http", "--no-tls"]
