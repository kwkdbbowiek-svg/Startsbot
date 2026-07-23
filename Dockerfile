# Railway - Apache + mod_php (SODDA va ISHONCHLI)
FROM php:8.2-apache

# System dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP Extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mysqli

# Apache MPM muammosini tuzatish
RUN a2dismod mpm_event && a2enmod mpm_prefork

# Apache mod_rewrite yoqish
RUN a2enmod rewrite

# Apache konfiguratsiyasi
RUN echo '<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html\n\
    \n\
    <Directory /var/www/html>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    \n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p bot/logs webapp/uploads/receipts \
    && chmod -R 777 bot/logs webapp/uploads/receipts

EXPOSE 80

# Apache ishga tushirish
CMD ["apache2-foreground"]
