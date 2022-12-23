FROM akarendra835/web-srv:8.2-laravel-openswoole-v22.0.0

# Install dependencies nodejs and npm for development [Comment on production]
RUN apk add --no-cache npm nodejs

# Install other extensions if needed
RUN install-php-extensions redis \
	&& docker-php-source delete \
    && rm -rf /var/cache/apk/* /var/tmp/*

COPY ./docker/supervisor/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

ENTRYPOINT ["sh", "/tmp/pre-run.sh"]

HEALTHCHECK --start-period=5s --interval=2s --timeout=5s --retries=8 CMD php artisan octane:status || exit 1