FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update -yy && \
  apt-get install -yy --no-install-recommends \
  unzip \
  libpq-dev \
  libcurl4-gnutls-dev \
  libzip-dev \
  libgd-dev \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip gd pdo pdo_mysql curl exif iconv mysqli
RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN apt-get update -yy && apt install -y libgmp-dev && docker-php-ext-install gmp


RUN pecl update-channels
RUN pecl install -o -f redis
RUN docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | \
  php -- --install-dir=/usr/bin/ --filename=composer

COPY composer.* ./

RUN composer install --prefer-dist --no-scripts --no-autoloader && rm -rf /root/.composer

COPY  . .

COPY docker/php/php.ini $PHP_INI_DIR/conf.d/php.ini

COPY docker/php/www.conf ${PHP_INI_DIR}-fpm.d/www.conf

RUN composer dump-autoload --no-scripts --optimize

ENV PORT=8000

RUN apt update
RUN apt-get install acl
RUN chmod +x /var/www/html/run.sh

CMD ["sh", "-c", "/var/www/html/run.sh ; php-fpm"]
