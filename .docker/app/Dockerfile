FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update -y && apt-get upgrade -y
RUN apt-get -ym install software-properties-common
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update -y

# Install necessary dependencies
RUN apt-get install -y \
    tree \
    git \
    php8.2 \
    php8.2-cli \
    php8.2-fpm \
    php8.2-mysql \
    php8.2-curl \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-zip \
    php8.2-xdebug \
    unzip \
    curl \
    && apt-get clean

ADD config/xdebug.sh /bin/xdebug.sh
RUN chmod +x /bin/xdebug.sh
RUN /bin/xdebug.sh

# Configure PHP-FPM
RUN sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/8.2/fpm/php.ini

# Configure PHP
RUN mkdir -p /run/php && chown -R www-data:www-data /run/php
ADD config/www.conf /etc/php/8.2/fpm/pool.d/www.conf
ADD config/php-fpm.conf /etc/php/8.2/fpm/php-fpm.conf
ADD config/custom.ini /etc/php/8.2/fpm/conf.d/custom.ini
RUN rm /etc/alternatives/php
RUN ln -s /usr/bin/php8.2 /etc/alternatives/php

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Configure Xdebug
RUN echo "zend_extension=xdebug.so" >> /etc/php/8.2/fpm/conf.d/20-xdebug.ini && \
    echo "xdebug.mode=debug" >> /etc/php/8.2/fpm/conf.d/20-xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /etc/php/8.2/fpm/conf.d/20-xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /etc/php/8.2/fpm/conf.d/20-xdebug.ini && \
    echo "xdebug.client_port=9003" >> /etc/php/8.2/fpm/conf.d/20-xdebug.ini

# Install NodeJS 20 and NPM 10
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN apt-get update -y && apt-get upgrade -y
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs

# Create directory for the web
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

RUN git config --global --add safe.directory '*'

EXPOSE 9000

CMD ["php-fpm8.2","-F"]