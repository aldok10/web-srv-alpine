ARG ALPINE_VERSION=3.16

FROM alpine:${ALPINE_VERSION}

LABEL Maintainer="Aldo Karendra <dev.akarendra835@gmail.com>"
LABEL Description="Lightweight container with Nginx 1.22 & PHP 8.1 based on Alpine Linux."

# Setup document root
WORKDIR /var/www/html

# Version PHP
ARG PHP_VERSION=81

# Install packages and remove default server definition
RUN apk add --no-cache \
  curl \
  nginx \
  nginx-mod-http-headers-more \
  php${PHP_VERSION}-bcmath \
  php${PHP_VERSION}-cli \
  php${PHP_VERSION}-ctype \
  php${PHP_VERSION}-curl \
  php${PHP_VERSION}-dom \
  php${PHP_VERSION}-fileinfo \
  php${PHP_VERSION}-fpm \
  php${PHP_VERSION}-gd \
  php${PHP_VERSION}-intl \
  php${PHP_VERSION}-mbstring \
  php${PHP_VERSION}-mysqli \
  php${PHP_VERSION}-opcache \
  php${PHP_VERSION}-openssl \
  php${PHP_VERSION}-pdo \
  php${PHP_VERSION}-pdo_pgsql \
  php${PHP_VERSION}-pdo_mysql \
  php${PHP_VERSION}-pgsql \
  php${PHP_VERSION}-phar \
  php${PHP_VERSION}-redis \
  php${PHP_VERSION}-session \
  php${PHP_VERSION}-simplexml \
  php${PHP_VERSION}-sodium \
  php${PHP_VERSION}-tokenizer \
  php${PHP_VERSION}-xml \
  php${PHP_VERSION}-xmlwriter \
  php${PHP_VERSION}-xmlreader \
  php${PHP_VERSION}-zip \
  php${PHP_VERSION}-zlib \
  supervisor

# Configure nginx
COPY config/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY config/fpm-pool.conf /etc/php${PHP_VERSION}/php-fpm.d/www.conf
COPY config/php.ini /etc/php${PHP_VERSION}/conf.d/custom.ini

# Configure supervisord
COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html /run /var/lib/nginx /var/log/nginx

# Switch to use a non-root user from here on
USER nobody

# Add application
COPY --chown=nobody src/ /var/www/html/

# Expose the port nginx is reachable on
EXPOSE 80

# Let supervisord start nginx & php-fpm
# CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1/fpm-ping