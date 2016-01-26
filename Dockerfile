FROM php:7.0.2-apache
MAINTAINER paolo.mainardi@sparkfabrik.com
ENV DEBIAN_FRONTEND noninteractive

# Enable apache rewrite.
RUN a2enmod rewrite proxy proxy_http

# Install php packages.
ENV MEMCACHE_PHP7_COMMIT 7ac4e83c6cf4aa8e16f8088534096293ee8e254d
RUN apt-get update \ 
  && apt-get install -y \
  libpng12-dev \
  libjpeg-dev \
  && docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr \
  && docker-php-ext-install gd \
  && apt-get autoremove -y \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Copy application.
COPY . /var/www/html/

# Expose apache 
EXPOSE 80 

