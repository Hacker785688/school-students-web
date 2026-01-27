# Use official PHP CLI image
FROM php:8.2-cli

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Set working directory
WORKDIR /var/www/html

# Copy project files into container
COPY . .

# Expose Render-assigned port
EXPOSE 10000

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:10000", "-t", "."]
