FROM php:7.2-apache
RUN a2enmod rewrite
COPY ./ /var/www/html/
RUN mkdir /var/www/html/uploads
RUN chown www-data:www-data -R /var/www/html/
RUN chmod 700 /var/www/html/uploads
