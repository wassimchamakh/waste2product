# Custom Jenkins build image with PHP 8.2 + Node.js 18
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update -qq && \
    apt-get install -y -qq \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    && docker-php-ext-install zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js 18
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /workspace

# Verify installations
RUN php --version && \
    composer --version && \
    node --version && \
    npm --version

ENV COMPOSER_ALLOW_SUPERUSER=1
