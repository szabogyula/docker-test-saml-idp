FROM composer:2.1.9 AS composer

FROM php:8.3-apache
LABEL maintainer="Gyula Szabó <gyufi@szabocsalad.com>"

# Utilities
RUN apt-get update && \
    apt-get -y install apt-transport-https git curl vim libldap2-dev --no-install-recommends && \
    rm -r /var/lib/apt/lists/* && \
    docker-php-ext-install pdo pdo_mysql ldap

# SimpleSAMLphp
ARG SIMPLESAMLPHP_VERSION=2.2.2
RUN curl -s -L -o /tmp/simplesamlphp.tar.gz https://github.com/simplesamlphp/simplesamlphp/releases/download/v$SIMPLESAMLPHP_VERSION/simplesamlphp-$SIMPLESAMLPHP_VERSION-full.tar.gz && \
    tar xzf /tmp/simplesamlphp.tar.gz -C /tmp && \
    rm -f /tmp/simplesamlphp.tar.gz  && \
    mv /tmp/simplesamlphp-* /var/www/simplesamlphp && \
    touch /var/www/simplesamlphp/modules/exampleauth/enable
COPY config/simplesamlphp/config.php /var/www/simplesamlphp/config
COPY config/simplesamlphp/authsources.php /var/www/simplesamlphp/config
COPY config/simplesamlphp/server.crt /var/www/simplesamlphp/cert/
COPY config/simplesamlphp/server.pem /var/www/simplesamlphp/cert/
COPY config/simplesamlphp/saml20-idp-hosted.php /var/www/simplesamlphp/metadata/saml20-idp-hosted.php

# Apache
COPY config/apache/ports.conf /etc/apache2
COPY config/apache/simplesamlphp.conf /etc/apache2/sites-available
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    a2dissite 000-default.conf default-ssl.conf && \
    a2ensite simplesamlphp.conf

# Set work dir
WORKDIR /var/www/simplesamlphp

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev
