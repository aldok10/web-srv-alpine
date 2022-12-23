FROM php:8.2-fpm-alpine

LABEL Maintainer="Aldo Karendra <dev.akarendra835@gmail.com>"
LABEL Description="PHP-FPM v8.2 OpenSwoole v22.0.0 based on Alpine Linux."

# Define specific openswoole version [ex : 22.0.0 / 4.12.1 ]
ARG OPENSWOOLE_VERSION=22.0.0

ARG TZ=Asia/Jakarta
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone

# Copy File Config
# ADD ./www.conf /usr/local/etc/php-fpm.d/www.conf

# Add and set Group
# RUN addgroup -g 1000 site \
#     && adduser -G site -g site -s /bin/sh -D site \
#     && mkdir -p /app \
#     && chown site:site /app

# Work in the specific space
WORKDIR /app

# Install composer from the official image
# COPY --from=composer /usr/bin/composer /usr/bin/composer

SHELL ["/bin/sh", "-eou", "pipefail", "-c"]

# Install dependencies
RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) \
    && docker-php-ext-install -j${NPROC} gd \
    && apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

# Install common extensions & openswoole
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions gd curl json mbstring pdo pdo_mysql xml zip bcmath iconv pdo_pgsql \
    && apk add --no-cache libstdc++ supervisor nano unzip libpq libpq-dev ${PHPIZE_DEPS} \
    && pecl install -D \ 
        'enable-openssl="yes" enable-http2="yes" enable-swoole-curl="yes" enable-mysqlnd="yes" with-postgres="yes" enable-cares="yes"' \
        openswoole-$OPENSWOOLE_VERSION \
    && docker-php-ext-enable openswoole \
    && apk del ${PHPIZE_DEPS} libpq-dev \
    && docker-php-source delete \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

COPY ./supervisor/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY ./supervisor/supervisord.conf /etc/supervisor/supervisord.conf

COPY ./pre-run.sh /tmp
RUN ["chmod", "+x", "/tmp/pre-run.sh"]

ENTRYPOINT ["sh", "/tmp/pre-run.sh"]