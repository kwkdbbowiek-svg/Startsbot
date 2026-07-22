# Railway.app uchun Dockerfile
FROM php:8.2-apache

# PostgreSQL client libraries o'rnatish
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Extensions o'rnatish (MySQL va PostgreSQL)
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mysqli

# Apache MPM to'liq tuzatish
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true

# mpm_prefork.load faylni to'g'ridan-to'g'ri yozish
RUN echo 'LoadModule mpm_prefork_module /usr/lib/apache2/modules/mod_mpm_prefork.so' > /etc/apache2/mods-enabled/mpm_prefork.load \
    && echo '<IfModule mpm_prefork_module>\nStartServers 5\nMinSpareServers 5\nMaxSpareServers 10\nMaxRequestWorkers 150\nMaxConnectionsPerChild 0\n</IfModule>' > /etc/apache2/mods-enabled/mpm_prefork.conf

# Apache mod_rewrite yoqish
RUN a2enmod rewrite

# Apache konfiguratsiya
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Ishchi katalog
WORKDIR /var/www/html

# Fayllarni nusxalash
COPY . /var/www/html/

# Ruxsatlar
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p bot/logs webapp/uploads/receipts \
    && chmod 750 bot/logs webapp/uploads/receipts

# Apache portni sozlash (Railway uchun)
ENV PORT=80
EXPOSE 80

# Apache ishga tushirish
CMD ["apache2-foreground"]
