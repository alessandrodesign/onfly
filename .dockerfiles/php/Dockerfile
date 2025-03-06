FROM php:8.3-fpm-bullseye

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libperl-dev \
    libmagickwand-dev \
    imagemagick \
    cron \
    unzip \
    git \
    curl \
    gnupg2 \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN docker-php-ext-install pdo pdo_mysql mysqli zip intl opcache bcmath \
    && docker-php-ext-install sockets

RUN docker-php-ext-install pcntl

RUN echo "disable_functions = " > /usr/local/etc/php/conf.d/disable_functions.ini

RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install perl && docker-php-ext-enable perl || echo "Perl extension not installed"
RUN pecl install xdebug && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug,develop" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini
RUN pecl install imagick && docker-php-ext-enable imagick

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer global require laravel/installer \
    && echo 'export PATH="$HOME/.composer/vendor/bin:$PATH"' >> ~/.bashrc \
    && echo 'export PATH="$HOME/.composer/vendor/bin:$PATH"' >> ~/.profile \
    && echo 'export PATH="$HOME/.composer/vendor/bin:$PATH"' >> /etc/environment \
    && ln -s /root/.composer/vendor/bin/laravel /usr/local/bin/laravel \
    && export PATH="$HOME/.composer/vendor/bin:$HOME/.config/composer/vendor/bin:$PATH"

ENV NVM_DIR=/root/.nvm
RUN bash -c "curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash \
    && . \$NVM_DIR/nvm.sh \
    && nvm install node \
    && nvm alias default node \
    && ln -s \$(nvm which node) /usr/local/bin/node \
    && ln -s \$(nvm which npm) /usr/local/bin/npm"

WORKDIR /var/www

COPY php.ini /usr/local/etc/php/conf.d/custom.ini
COPY ./cron/mycron /etc/cron.d/mycron
RUN chmod 0644 /etc/cron.d/mycron && crontab /etc/cron.d/mycron
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN chown -R www-data:www-data /var/www

EXPOSE 9000
EXPOSE 8080

ENV PATH="$HOME/.composer/vendor/bin:$HOME/.config/composer/vendor/bin:$PATH"

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
