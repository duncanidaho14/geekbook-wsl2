FROM php:8.1-apache

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
  \
  &&  apt-get update \
  &&  apt-get install -y --no-install-recommends \
  locales apt-utils git libicu-dev g++ libpq-dev libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip wget \
  \
  &&  echo "en_US.UTF-8 UTF-8" > /etc/locale.gen  \
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
  &&  mv /root/.symfony5/bin/symfony /usr/local/bin \ 
  &&  docker-php-ext-configure \
  intl \
  &&  docker-php-ext-install \
  pdo pdo_pgsql opcache intl zip calendar dom mbstring gd xsl \
  \
  &&  pecl install apcu && docker-php-ext-enable apcu 

RUN apt-get install -y \
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

RUN apt install -y openssl

RUN apt install -y openssh-server

RUN curl -JLO "https://dl.filippo.io/mkcert/v1.4.4?for=linux/amd64" && \
    chmod +x mkcert-v1.4.4-linux-amd64 \
    && cp mkcert-v*-linux-amd64 /usr/local/bin/mkcert

RUN curl -LO https://deployer.org/deployer.phar  && \
    mv deployer.phar /usr/local/bin/dep  && \
    chmod +x /usr/local/bin/dep

RUN mkdir -p /var/www/project/assets/pdf \
    && chown -R www-data:www-data /var/www/project/assets/pdf \
    && chmod -R 755 /var/www/project/assets/pdf

RUN mkdir -p /etc/ssl/traefik/ \
  && chown -R www-data:www-data /etc/ssl/traefik/ \
  && chmod -R 755 /etc/ssl/traefik/

COPY ./vhosts/vhosts.conf ./etc/apache2/sites-enabled/000-default.conf
COPY ./vhosts/default-ssl.conf ./etc/apache2/sites-enabled/ssl/000-default-ssl.conf
COPY ./vhosts/openssl.cnf ./etc/apache2/sites-enabled/ssl/openssl.cnf

# ENV FONTCONFIG_PATH=/tmp/fontconfig

COPY . /var/www/

WORKDIR /var/www/

EXPOSE 7000

CMD ["/usr/sbin/apache2ctl", "-DFOREGROUND"]
