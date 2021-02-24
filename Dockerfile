FROM flyimg/base-image:1.1.0

# Install other file processors.
RUN apt update && \
    apt install -y \
    ghostscript \
    cron \
    ffmpeg \
    --no-install-recommends && \
    pip3 install pillow && \
    rm -rf /var/lib/apt/lists/*

COPY .    /var/www/html

#add www-data + mdkdir var folder
RUN usermod -u 1000 www-data && \
    mkdir -p /var/www/html/var web/uploads/.tmb var/cache/ var/log/ && \
    chown -R www-data:www-data var/  web/uploads/ && \
    chmod 777 -R var/  web/uploads/

RUN composer install --no-dev --optimize-autoloader

#tune max_children
RUN sed -i -e "s/pm.max_children = 5/pm.max_children = 60/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i -e "s/pm.start_servers = 2/pm.start_servers = 20/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i -e "s/pm.min_spare_servers = 1/pm.min_spare_servers = 15/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i -e "s/pm.max_spare_servers = 3/pm.max_spare_servers = 25/g" /usr/local/etc/php-fpm.d/www.conf

# add cron to clear tmp dir
#daily at 1 am
RUN echo "0 1 * * * root find /var/www/html/var/tmp -type f -mtime +1 -delete" >> /etc/crontab
# enable cron service
RUN mkdir -p /etc/services.d/cron
RUN echo "#!/bin/sh \n /usr/sbin/cron -f" > /etc/services.d/cron/run && chmod +x /etc/services.d/cron/run