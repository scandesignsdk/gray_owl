FROM php:7.2-cli

RUN apt-get update -yqq \
    && apt-get install git zlib1g-dev libzip-dev -y \
    && docker-php-ext-install zip \
    && curl -fsSL https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

ENV PATH /root/.composer/vendor/bin:$PATH

WORKDIR ./app

CMD ["phpunit"]
