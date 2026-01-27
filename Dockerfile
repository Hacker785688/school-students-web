# Use official PHP image with built-in server
FROM php:8.2-cli

# Copy all project files into container
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Expose port for Render
EXPOSE 10000

# Start PHP built-in server using Render-assigned port
CMD ["php", "-S", "0.0.0.0:10000", "-t", "."]

