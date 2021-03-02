FROM ubuntu:20.04

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -y
ENV TZ=Europe/Warsaw
RUN apt-get -y install tzdata
RUN apt-get -y install software-properties-common \
    wget curl nginx zip unzip imagemagick webp libmagickwand-dev libyaml-dev \
    python3 python3-numpy libopencv-dev python3-setuptools opencv-data \
    gcc nasm build-essential make cmake wget vim git acl
RUN add-apt-repository -y ppa:ondrej/php
RUN apt-get -y update


RUN wget https://raw.githubusercontent.com/php-opencv/php-opencv-packages/master/opencv_4.5.0_amd64.deb && \
    dpkg -i opencv_4.5.0_amd64.deb && rm opencv_4.5.0_amd64.deb

RUN apt-get -y install php7.4 php7.4-fpm php7.4-gd php7.4-yaml php7.4-imagick php7.4-xdebug pkg-config php7.4-dev php7.4-xml php7.4-mbstring

RUN git clone https://github.com/php-opencv/php-opencv.git
RUN cd php-opencv && git checkout php7.4 && phpize && ./configure --with-php-config=/usr/bin/php-config && make && make install
RUN echo "extension=opencv.so" > /etc/php/7.4/cli/conf.d/opencv.ini
RUN echo "extension=opencv.so" > /etc/php/7.4/fpm/conf.d/opencv.ini

#install MozJPEG
RUN \
    wget "https://github.com/mozilla/mozjpeg/releases/download/v3.2/mozjpeg-3.2-release-source.tar.gz" && \
    tar xvf "mozjpeg-3.2-release-source.tar.gz" && \
    rm mozjpeg-3.2-release-source.tar.gz && \
    cd mozjpeg && \
    ./configure && \
    make && \
    make install

#facedetect script
RUN \
	cd /var && \
    curl https://bootstrap.pypa.io/get-pip.py -o get-pip.py && \
    python3 get-pip.py && \
    pip3 install numpy && \
    pip3 install opencv-python && \
    git clone https://github.com/flyimg/facedetect.git && \
    chmod +x /var/facedetect/facedetect && \
    ln -s /var/facedetect/facedetect /usr/local/bin/facedetect

#Smart Cropping pytihon plugin
RUN pip install git+https://github.com/flyimg/python-smart-crop

#composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN cd /tmp/ && wget https://github.com/just-containers/s6-overlay/releases/download/v2.2.0.1/s6-overlay-amd64-installer
RUN chmod +x /tmp/s6-overlay-amd64-installer && /tmp/s6-overlay-amd64-installer /

# Install other file processors.
RUN apt update && \
    apt install -y \
    ghostscript \
    cron \
    ffmpeg \
    --no-install-recommends && \
    pip3 install pillow && \
    rm -rf /var/lib/apt/lists/*


#copy etc/
COPY resources/etc/ /etc/
RUN cp /etc/php-fpm.d/www.conf /etc/php/7.4/fpm/pool.d/
RUN mkdir -p /run/php

ENV PORT 80

COPY resources/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

WORKDIR /var/www/html

RUN apt-get -y update

COPY .    /var/www/html

#add www-data + mdkdir var folder
RUN usermod -u 1000 www-data && \
    mkdir -p /var/www/html/var web/uploads/.tmb var/cache/ var/log/ && \
    chown -R www-data:www-data var/  web/uploads/ && \
    chmod 777 -R var/  web/uploads/

RUN composer update --no-dev --optimize-autoloader

# add cron to clear tmp dir
#daily at 1 am
RUN echo "0 1 * * * root find /var/www/html/var/tmp -type f -mtime +1 -delete" >> /etc/crontab
# enable cron service
RUN mkdir -p /etc/services.d/cron
RUN echo "#!/bin/sh \n /usr/sbin/cron -f" > /etc/services.d/cron/run && chmod +x /etc/services.d/cron/run
CMD ["/init"]