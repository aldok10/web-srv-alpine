FROM alpine:3.16.0

WORKDIR /build

RUN apk update && apk add php81 php81-cli zip php81-zip curl wget \
    php81-pgsql php81-pear php81-dev gcc make g++ openssl-dev curl-dev postgresql14-dev c-ares-dev  php81-mbstring php81-xml php81-gd && \
    ln -s /usr/bin/php81 /usr/bin/php && ln -s /usr/bin/pecl81 /usr/bin/pecl && ln -s /usr/bin/phpize81 /usr/bin/phpize && ln -s /usr/bin/php-config81 /usr/bin/php-config

RUN mkdir /data && wget http://pecl.php.net/get/openswoole-4.11.1.tgz && tar -xf openswoole-4.11.1.tgz --strip-components=1 && which phpize && ls -l && phpize && \
    ./configure --with-postgres=/usr/include/postgresql --enable-http2 --enable-openssl && \
    make && make install INSTALL_ROOT=/data