FROM nginx:bullseye

RUN echo '-= BUILD NGINX IMAGE =-'

RUN apt update && apt install -y --no-install-recommends \
        curl \
        libgd-dev \
        nginx-module-image-filter \
        vim \
    && apt clean && apt autoremove -y \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set timezone
RUN rm /etc/localtime && \
    ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime && \
    "date"

RUN mkdir /var/www
RUN chown -R www-data:www-data /var/www && \
  chmod -R 755 /var/www
