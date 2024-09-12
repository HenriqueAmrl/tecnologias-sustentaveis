FROM php:7.4-alpine
RUN apk --no-cache add git zip
RUN docker-php-ext-install pdo_mysql bcmath
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"
COPY --chmod=777 .docker/php/entrypoint.sh /entrypoint.sh
COPY ./api /app
ENV TZ=America/Sao_Paulo
RUN echo 'date.timezone = "America/Sao_Paulo"' > /usr/local/etc/php/conf.d/timezone.ini
EXPOSE 80
WORKDIR /app
ENTRYPOINT [ "/entrypoint.sh" ]