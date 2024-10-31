FROM php:8.2-apache

#ENV APP_ENV=prod

RUN echo "ServerName GeekBook" >> mkdir -p /etc/apache2/conf/000-default.conf \
  \
  &&  apt-get update \
  &&  apt-get install -y --no-install-recommends \
  locales apt-utils git libicu-dev g++ libpq-dev libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip wget libsodium-dev \
  \
  &&  echo "en_US.UTF-8 UTF-8" >> /etc/locale.gen  \
  &&  echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
  &&  locale-gen \
  \
  &&  curl -sS https://getcomposer.org/installer | php -- \
  &&  mv composer.phar /usr/local/bin/composer \
  \
  && curl -sL https://deb.nodesource.com/setup_18.x | bash \
  && apt-get install -y nodejs \
  \
  &&  curl -sS https://get.symfony.com/cli/installer | bash \
  &&  mv /root/.symfony5/bin/symfony /usr/local/bin  

RUN docker-php-ext-configure \
  intl \
  &&  docker-php-ext-install \
  -j5 pdo pdo_pgsql mysqli shmop opcache intl zip calendar dom mbstring gd xsl \
  \
  &&  pecl install apcu && docker-php-ext-enable apcu \
  && apt install nano

RUN apt-get install -y \
    openssl \
    libxrender1 \
    libfontconfig1 \
    libx11-dev \
    libjpeg62 \
    libxtst6 \
    wget \
    && wget https://github.com/h4cc/wkhtmltopdf-amd64/blob/master/bin/wkhtmltopdf-amd64?raw=true -O /usr/bin/wkhtmltopdf \
    && chmod +x /usr/bin/wkhtmltopdf \
    && wget https://github.com/h4cc/wkhtmltoimage-amd64/blob/master/bin/wkhtmltoimage-amd64?raw=true -O /usr/bin/wkhtmltoimage \
    && chmod +x /usr/bin/wkhtmltoimage

RUN curl -L https://install.meilisearch.com | sh \
    && composer require meilisearch/search-bundle symfony/http-client nyholm/psr7 \
    && chmod +x meilisearch \
    && mv meilisearch /usr/bin/meilisearch

RUN a2enmod rewrite proxy proxy_http proxy_wstunnel ssl

RUN apt install -y openssl

RUN apt install -y openssh-server

RUN curl -JLO "https://dl.filippo.io/mkcert/v1.4.4?for=linux/amd64" && \
    chmod +x mkcert-v1.4.4-linux-amd64 \
    && cp mkcert-v*-linux-amd64 /usr/local/bin/mkcert

RUN curl -LO https://deployer.org/deployer.phar  && \
    mv deployer.phar /usr/local/bin/dep  && \
    chmod +x /usr/local/bin/dep

RUN mkdir -p /var/www/html/project/assets/pdf \
    && chown -R www-data:www-data /var/www/html/project/assets/pdf \
    && chmod -R 755 /var/www/html/project/assets/pdf

RUN mkdir -p /etc/ssl/traefik/ \
  && chown -R www-data:www-data /etc/ssl/traefik/ \
  && chmod -R 755 /etc/ssl/traefik/

RUN mkdir -p tools/php-cs-fixer \
  && composer require --working-dir=tools/php-cs-fixer friendsofphp/php-cs-fixer

RUN pecl install redis redis-cli \
  && docker-php-ext-enable redis

# RUN caddy fmt --overwrite /etc/caddy/Caddyfile
# RUN apt install certbot -y \
#   && certbot --apache 


# RUN curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose \
#     && chmod +x /usr/local/bin/docker-compose \
#     && apt install make \
#     && apt install build-essential  
# RUN touch /etc/apache2/sites-available/000-default.conf
# RUN touch /etc/apache2/sites-available/default-ssl.conf

# COPY ./vhosts/vhosts.conf /etc/apache2/sites-available/000-default.conf
# COPY ./vhosts/default-ssl.conf /etc/apache2/sites-available/ssl/default-ssl.conf
# RUN curl "https://github.com/jolicode/castor/releases/latest/download/castor.linux-amd64.phar" -Lfso $HOME/.local/bin/castor && \
#     chmod u+x $HOME/.local/bin/castor && \
#     castor --version || \
#     (echo "Could not install castor. Is the target directory writeable?" && (exit 1))

ENV session.save_handler="redis"
ENV session.save_path="tcp://db_redis_cache:6379?auth=redis-stack"

ENV REDIS_ARGS="--save 60 1000 --appendonly yes --requirepass redis-stack"
ENV REDISTIMESERIES_ARGS="RETENTION_POLICY=20"

# The URL of the Mercure hub, used by the app to publish updates (can be a local URL)
ENV MERCURE_URL="https://localhost:2019/.well-known/mercure"
# The public URL of the Mercure hub, used by the browser to connect
ENV MERCURE_PUBLIC_URL="http://localhost:2019/.well-known/mercure"
# The secret used to sign the JWTs
ENV MERCURE_JWT_SECRET="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdfX0.yTxnVn4c7LBfetDZ0Ck9tcI9vkAEwk6tEBtkp_g0QLE"

#ENV MERCURE_TRANSPORT_URL="postgres://postgres:postgrespw@db_geekbook_app:5432/geekbook?serverVersion=16&charset=utf8"
ENV MERCURE_LICENSE=snip
ENV MERCURE_PUBLISH_URL='http://localhost:2019/.well-known/mercure'
ENV MERCURE_SUBSCRIBE_URL='http://localhost:2019/.well-known/mercure'
ENV MERCURE_PUBLISHER_JWT_KEY='eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdfX0.yTxnVn4c7LBfetDZ0Ck9tcI9vkAEwk6tEBtkp_g0QLE'
ENV MERCURE_SUBSCRIBER_JWT_KEY='eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdfX0.yTxnVn4c7LBfetDZ0Ck9tcI9vkAEwk6tEBtkp_g0QLE'

ENV MERCURE_PUBLISH_ALLOWED_ORIGINS='*'
ENV MERCURE_SUBSCRIBE_ALLOWED_ORIGINS='*'
ENV MERCURE_CORS_ALLOWED_ORIGINS='*'

ENV CORS_ALLOWED_ORIGINS='*'
ENV ACCESS_CONTROL_ALLOWED_ORIGINS='*'

ENV SYMFONY_ROOT="/var/www/html/project"
ENV MERCURE_PATH="$SYMFONY_ROOT/vendor/mercure"

ENV MERCURE_PUBLISHER_JWT_KEY="$(cat $SYMFONY_ROOT/config/jwt/public.pem)" 
ENV MERCURE_PUBLISHER_JWT_ALG="RS256" 
ENV MERCURE_SUBSCRIBER_JWT_KEY="$(cat $SYMFONY_ROOT/config/jwt/public.pem)" 
ENV MERCURE_SUBSCRIBER_JWT_ALG="RS256" 

###< symfony/mercure-bundle ###

COPY . /var/www/html/

# RUN cd /var/www/html && \
#     composer install && \
#     php bin/console cache:clear && \
#     php bin/console cache:warmup && \
#     chown -R www-data:www-data /var/www/html && \
#     npm install -g npm && \
#     npm install
    
WORKDIR /var/www/html/

# ENTRYPOINT [ "bash", "./docker/docker.sh" ]

EXPOSE 7000

# FROM redis
# COPY ./vhosts/redis.conf /usr/local/etc/redis/redis.conf
# EXPOSE 6380
# CMD [ "redis-server", "/usr/local/etc/redis/redis.conf" ]

# FROM debian:bullseye-slim
# # Installer les dépendances requises
# RUN apt-get update && apt-get install -y \
#   curl \
#   ca-certificates \
#   && rm -rf /var/lib/apt/lists/*

# # Télécharger et installer le binaire Mercure
# RUN curl -L https://github.com/dunglas/mercure/releases/download/v0.16.3/mercure-legacy_Linux_x86_64.tar.gz | tar xz -C /usr/local/bin

# RUN chmod +x /usr/local/bin/mercure
# EXPOSE 3200
# CMD ["mercure"] 

# FROM dunglas/mercure

# RUN touch /etc/caddy/Caddyfile

# COPY ../src/caddy/Caddyfile /etc/caddy/Caddyfile

# WORKDIR /

# EXPOSE 3201