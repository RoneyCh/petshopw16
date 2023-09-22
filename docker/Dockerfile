FROM ubuntu:22.04

ARG WWWGROUP

ARG PHP_VERSION=php7.4

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

COPY ./php.ini /etc/php/7.4/apache2/php.ini

RUN apt-get -y update && apt-get -y upgrade && \
  apt-get install -y software-properties-common 
RUN add-apt-repository ppa:ondrej/php && apt install -y ${PHP_VERSION}  apache2
RUN php --version

RUN apt-get -y update && apt-get -y upgrade && \
  apt-get install -y \
  gosu \
  git \
  libapache2-mod-${PHP_VERSION} \
  ${PHP_VERSION}-mysql \
  ${PHP_VERSION}-curl \
  ${PHP_VERSION}-gd \ 
  ${PHP_VERSION}-intl \ 
  ${PHP_VERSION}-mbstring \
  ${PHP_VERSION}-soap \ 
  ${PHP_VERSION}-xml \ 
  ${PHP_VERSION}-zip \
  ${PHP_VERSION}-mcrypt \
  libpq-dev \
  libmemcached-dev \
  curl \
  supervisor \
  mysql-client \
  libpng-dev \
  zip\
  git\
  libfreetype6-dev \
  libssl-dev \
  libmcrypt-dev \
  openssh-client \
  ca-certificates \
  --no-install-recommends && \
  rm -r /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer  

RUN a2enmod rewrite

RUN mkdir /opt/session
RUN chmod -R 777 /opt/session

RUN groupadd --force -g $WWWGROUP clarotem
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 clarotem

COPY ./Vhosts/000-default.conf /etc/apache2/sites-enabled
COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisord/conf.d/supervisord.conf
RUN chmod +x /usr/local/bin/start-container

EXPOSE 80

ENTRYPOINT ["start-container"]