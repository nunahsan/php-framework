FROM php:8.2-fpm

RUN apt update -y

RUN apt install wget -y
RUN apt install unzip -y

RUN wget -O composer-setup.php https://getcomposer.org/installer

RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN pecl install mongodb && docker-php-ext-enable mongodb


#docker build -t php82-fpm .