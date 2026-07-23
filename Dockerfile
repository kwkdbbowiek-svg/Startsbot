# Railway - Apache + mod_php (SODDA va ISHONCHLI)
FROM php:8.2-apache

# System dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP Extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mysqli

# Apache MPM muammosini to'liq tuzatish - fayllarni o'chirish
RUN rm -f /etc/apache2/mods-enabled/mpm_event.conf \
    /etc/apache2/mods-enabled/mpm_event.load \
    /etc/apache2/mods-enabled/mpm_worker.conf \
    /etc/apache2/mods-enabled/mpm_worker.load

# mpm_prefork'ni yoqish
RUN if [ ! -f /etc/apache2/mods-enabled/mpm_prefork.load ]; then \
        ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf && \
        ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load; \
    fi

# Apache mod_rewrite yoqish
RUN if [ ! -f /etc/apache2/mods-enabled/rewrite.load ]; then \
        ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load; \
    fi

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
