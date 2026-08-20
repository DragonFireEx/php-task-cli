FROM php:8.3-cli-alpine

WORKDIR /app

COPY composer.json ./

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN composer install --no-interaction --no-dev --optimize-autoloader

COPY . .

ENTRYPOINT ["php", "bin/console.php"]
CMD ["help"]