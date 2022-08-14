FROM akarendra835/web-srv:8.0

# install application dependencies
WORKDIR /var/www/app

ARG TZ=Asia/Jakarta

USER root

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone

# Install composer from the official image
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copy composer.* to container
COPY composer.* .

# Run composer install to install the dependencies
RUN composer install --no-scripts --no-autoloader --ansi --no-interaction

# Copy Nginx configuration
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

# copy application code
COPY . .

RUN composer dump-autoload -o

USER nobody

EXPOSE 80

# run supervisor
# CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
