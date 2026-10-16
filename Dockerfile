FROM php:8.2-apache
RUN docker-php-ext-install mysqli
# Allow .htaccess (deny rules + Basic Auth) to take effect, and enable auth modules
RUN sed -i 's#AllowOverride None#AllowOverride All#g' /etc/apache2/apache2.conf \
 && a2enmod auth_basic authn_core authn_file authz_core authz_user
COPY src/ /var/www/html/
# Harden file perms but keep the intended vulns reachable
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
