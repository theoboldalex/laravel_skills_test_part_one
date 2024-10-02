FROM php:8.3-cli

RUN pecl install pcov \
    && docker-php-ext-enable pcov \
    && echo "pcov.enabled=1" >> /usr/local/etc/php/conf.d/pcov.ini

RUN echo "xdebug.mode=off" >> /usr/local/etc/php/conf.d/xdebug.ini || true

WORKDIR /var/www/html

CMD ["php", "-a"]
