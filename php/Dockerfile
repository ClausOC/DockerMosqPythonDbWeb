FROM php:fpm
RUN pecl install xdebug
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable xdebug
# FROM php:fpm-alpine

# # Installer nødvendige afhængigheder
# RUN apk add --no-cache \
#     linux-headers \
#     $PHPIZE_DEPS

# # Installér Xdebug via PECL
# RUN pecl install xdebug \
#     && docker-php-ext-enable xdebug \
#     && pecl clear-cache

# # Opret Xdebug-konfigurationsfil manuelt
# RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#     && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#     && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#     && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
