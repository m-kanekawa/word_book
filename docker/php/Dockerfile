FROM php:8.2-fpm

RUN apt-get update
RUN apt-get install -y git zip zlib1g-dev lsof

RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer
