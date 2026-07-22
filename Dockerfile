# Railway.app uchun Dockerfile
FROM php:8.2-apache

# PostgreSQL client libraries o'rnatish
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Extensions o'rnatish (MySQL va PostgreSQL)
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mysqli

# Apache MPM xatosini tuzatish
RUN a2dismod mpm_event && a2enmod mpm_prefork

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
