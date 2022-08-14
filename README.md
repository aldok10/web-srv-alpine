# Docker PHP-FPM & Nginx 1.22 on Alpine Linux
Example PHP-FPM 8.1/8.0/7.4/7.3./7.2/7.1/7.0 & Nginx 1.22 container image for Docker, built on [Alpine Linux](https://www.alpinelinux.org/).

## Example List
* ![Example PHP-FPM 8.1](https://github.com/aldok10/web-srv-alpine/tree/main/base-image/php-8.1)
* ![Example PHP-FPM 8.0](https://github.com/aldok10/web-srv-alpine/tree/main/base-image/php-8.0)
* ![Example PHP-FPM 7.4](https://github.com/aldok10/web-srv-alpine/tree/main/base-image/php-7.4)
* ![Example PHP-FPM 7.3](https://github.com/aldok10/web-srv-alpine/tree/main/base-image/php-7.3)
* ![Example PHP-FPM 7.2](https://github.com/aldok10/web-srv-alpine/tree/main/base-image/php-7.2)
* ![Example PHP-FPM 7.1](https://github.com/aldok10/web-srv-alpine/tree/main/base-image/php-7.1)
* ![Example PHP-FPM 7.0](https://github.com/aldok10/web-srv-alpine/tree/main/base-image/php-7.0)

## Details about repository
Repository: https://github.com/aldok10/web-srv-alpine

* Built on the lightweight and secure Alpine Linux distribution
* Multi-platform, supporting AMD4, ARMv6, ARMv7, ARM64
* Very small Docker image size (+/-40MB)
* Uses PHP 8.1 for better performance, lower CPU usage & memory footprint. Or choice another version for you needed.
* Optimized for 100 concurrent users
* Optimized to only use resources when there's traffic (by using PHP-FPM's `on-demand` process manager)
* The services Nginx, PHP-FPM and supervisord run under a non-privileged user (nobody) to make it more secure
* The logs of all the services are redirected to the output of the Docker container (visible with `docker logs -f <container name>`)
* Follows the KISS principle (Keep It Simple, Stupid) to make it easy to understand and adjust the image to your needs

[![Docker Pulls](https://img.shields.io/docker/pulls/akarendra835/web-srv.svg)](https://hub.docker.com/r/akarendra835/web-srv/)
![nginx 1.22](https://img.shields.io/badge/nginx-1.22-brightgreen.svg)
![php 8.1](https://img.shields.io/badge/php-8.1-brightgreen.svg)
![php 8.0](https://img.shields.io/badge/php-8.0-brightgreen.svg)
![php 7.4](https://img.shields.io/badge/php-7.4-brightgreen.svg)
![php 7.3](https://img.shields.io/badge/php-7.3-brightgreen.svg)
![php 7.2](https://img.shields.io/badge/php-7.2-brightgreen.svg)
![php 7.1](https://img.shields.io/badge/php-7.1-brightgreen.svg)
![php 7.0](https://img.shields.io/badge/php-7.0-brightgreen.svg)
![License MIT](https://img.shields.io/badge/license-MIT-blue.svg)

## Goal of this project
The goal of this container image is to provide an example for running Nginx and PHP-FPM in a container which follows
the best practices and is easy to understand and modify to your needs.

## Usage

Start the Docker container:

    docker run --rm -p 80:80 -e APP_ENV=local akarendra835/web-srv /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

See the PHP info on http://localhost, or the static html page on http://localhost/test.html

Or mount your own code to be served by PHP-FPM & Nginx

    docker run --rm -p 80:80 -v ~/my-codebase:/var/www/html -e APP_ENV=local akarendra835/web-srv /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

### Docker Hub repository
This image can be pulled from Docker Hub under the name [akarendra835/web-srv](https://hub.docker.com/r/akarendra835/web-srv).

## Configuration
In [config/](config/) you'll find the default configuration files for Nginx, PHP and PHP-FPM.
If you want to extend or customize that you can do so by mounting a configuration file in the correct folder;

Nginx configuration:

    docker run -v "`pwd`/nginx-server.conf:/etc/nginx/conf.d/server.conf" akarendra835/web-srv /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

PHP configuration:

```bash
# for php version 8.1 and 8.0
docker run -v "`pwd`/php-setting.ini:/etc/php8/conf.d/settings.ini" -e APP_ENV=local akarendra835/web-srv /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
# for php version 7
docker run -v "`pwd`/php-setting.ini:/etc/php7/conf.d/settings.ini" -e APP_ENV=local akarendra835/web-srv /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
```

PHP-FPM configuration:

```bash
# for php version 8.1 and 8.0
docker run -v "`pwd`/php-fpm-settings.conf:/etc/php8/php-fpm.d/server.conf" -e APP_ENV=local akarendra835/web-srv /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
# for php version 7
docker run -v "`pwd`/php-fpm-settings.conf:/etc/php7/php-fpm.d/server.conf" -e APP_ENV=local akarendra835/web-srv /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
```

_Note; Because `-v` requires an absolute path I've added `pwd` in the example to return the absolute path to the current directory_


## Adding composer

If you need [Composer](https://getcomposer.org/) in your project, here's an easy way to add it.

```Dockerfile
FROM akarendra835/web-srv:latest

# Install composer from the official image
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Run composer install to install the dependencies
RUN composer install --optimize-autoloader --no-interaction --no-progress
```

### Building with composer

If you are building an image with source code in it and dependencies managed by composer then the definition can be improved.
The dependencies should be retrieved by the composer but the composer itself (`/usr/bin/composer`) is not necessary to be included in the image.

```Dockerfile
FROM composer AS composer

# copying the source directory and install the dependencies with composer
COPY <your_directory>/ /app

# run composer install to install the dependencies
RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress

# continue stage build with the desired image and copy the source including the
# dependencies downloaded by composer
FROM akarendra835/web-srv
COPY --chown=nginx --from=composer /app /var/www/html
```