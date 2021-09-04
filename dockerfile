FROM php:7.2-apache
RUN apt-get update

# Install Postgre PDO
RUN apt-get install  unzip -y libpq-dev  libzip-dev  zip \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \ 
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && a2enmod rewrite                              \
    && chmod 777 -R -c /var/www  \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
 &&  echo 'memory_limit = -1' >> /usr/local/etc/php/php.ini \
 &&  echo 'max_execution_time = 0/g' >> /usr/local/etc/php/php.ini
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer