FROM php:8.0.7-fpm-alpine3.13

# ADD and set Group
#RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

# Create folder to run
RUN mkdir -p /var/www

WORKDIR /var/www/app
#RUN chown laravel:laravel /var/www

RUN apk add --update --no-cache $PHPIZE_DEPS \
        libjpeg-turbo-dev \
        libpng-dev \
        freetype-dev \
        libwebp-dev \
        supervisor \
        libzip-dev \
        icu-dev \
        curl \
        libzip-dev \
        postgresql-dev \
        postgresql-client \
        zip \
        nano \
        git && \
    docker-php-ext-install -j$(nproc) iconv pdo pdo_pgsql pgsql bcmath intl zip && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql  && \
    docker-php-ext-enable pdo_pgsql && \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ --with-webp=/usr/include/

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN apk update \
    rm /var/cache/apk/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


#cron
#RUN echo '*  *  *  *  * /usr/local/bin/php /var/www/app/artisan schedule:run >> /dev/null 2>&1' > /etc/crontabs/root
RUN echo '*  *  *  *  * /usr/local/bin/php /var/www/app/artisan schedule:run >> /var/log/cron_ji.log 2>&1' > /etc/crontabs/root

#RUN touch /etc/crontab /etc/cron.*/*

CMD ["php-fpm"]