FROM base-img

COPY .    /var/www/html

#add www-data + mdkdir var folder
RUN usermod -u 1000 www-data && \
    mkdir -p /var/www/html/var web/uploads/.tmb var/cache/ var/log/ && \
    chown -R www-data:www-data var/  web/uploads/ && \
    chmod 777 -R var/  web/uploads/

RUN composer install --no-dev --optimize-autoloader

COPY cleanup-tmp.sh cleanup-tmp.sh
RUN chmod +x cleanup-tmp.sh
RUN ./cleanup-tmp.sh
