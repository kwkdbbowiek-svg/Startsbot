# Railway.app uchun - Nginx + PHP-FPM (Apache muammosiz!)
FROM php:8.2-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    nginx \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

# PHP Extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mysqli

# Nginx config
RUN echo 'server {\n\
    listen 80;\n\
    root /var/www/html;\n\
    index index.php index.html;\n\
    \n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
    \n\
    location ~ \.php$ {\n\
        try_files $uri =404;\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
        fastcgi_read_timeout 300;\n\
        fastcgi_connect_timeout 300;\n\
        fastcgi_send_timeout 300;\n\
        include fastcgi_params;\n\
    }\n\
    \n\
    location ~ /\.(?!well-known).* {\n\
        deny all;\n\
    }\n\
}' > /etc/nginx/sites-available/default

# Supervisor config
RUN echo '[supervisord]\n\
nodaemon=true\n\
\n\
[program:php-fpm]\n\
command=php-fpm -F\n\
autostart=true\n\
autorestart=true\n\
\n\
[program:nginx]\n\
command=nginx -g "daemon off;"\n\
autostart=true\n\
autorestart=true\n\
' > /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p bot/logs webapp/uploads/receipts \
    && chmod 750 bot/logs webapp/uploads/receipts

EXPOSE 80

# Supervisor binary path tekshirish va run
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
