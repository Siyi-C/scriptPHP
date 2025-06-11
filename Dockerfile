FROM php:8.2-cli

# install PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Install MySQL client
RUN apt-get update && apt-get install -y default-mysql-client

WORKDIR /var/www/html

# executes a bash shell to keep container running for developing and testing purpose, 
# otherwise the container will exit
CMD ["bash"]