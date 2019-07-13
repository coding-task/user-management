From ubuntu:18.04
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && apt-get -y upgrade && \
    apt-get -y install  -q supervisor \
                            cron \
                            nginx \
                            unzip \
                            curl \
                            libxrender1 \
                            php7.2-fpm \
                            php7.2-cli \
                            php7.2-gd \
                            php7.2-imap \
                            php7.2-json \
                            php7.2-mysql \
                            php7.2-mbstring \
                            php7.2-zip \
                            php7.2-xml \
                            php7.2-sqlite \
                            php7.2-curl && \
                            apt-get clean

RUN curl  -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer

WORKDIR /var/www

ADD conf/php.ini /etc/php/7.2/fpm/conf.d/99.custom.ini
ADD conf/www.conf /etc/php/7.2/fpm/pool.d/www.conf

ADD conf/nginx.conf /etc/nginx/nginx.conf
ADD conf/supervisord.conf /etc/supervisord.conf

RUN mkdir /var/run/php
RUN useradd -u 1000 1000

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]
