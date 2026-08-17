FROM php:8.4-fpm-bookworm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        libxml2-dev \
        libonig-dev \
        libpq-dev \
        git \
        curl \
        unzip \
    # Конфигурация и одновременная установка всех расширений PHP
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        mbstring \
        exif \
        pcntl \
        bcmath \
        intl \
        zip \
        mysqli \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
    # Очистка кэша apt для уменьшения размера образа
    && docker-php-source delete \
    && rm -rf /var/lib/apt/lists/*

# Добавлены bcmath (для точных расчетов) и intl (для форматирования дат/валют в Yii2)

# Установка Composer 2.x
COPY --from=composer:2.9.2 /usr/bin/composer /usr/local/bin/composer

# Настройка php (убедитесь, что файл существует по этому пути на хосте)
COPY ./conf/custom-settings.ini /usr/local/etc/php/conf.d/custom-settings.ini

# Настройка рабочей директории
WORKDIR /var/www/tech.local

# Создание пользователя www-data и установка прав
# RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
# USER www-data