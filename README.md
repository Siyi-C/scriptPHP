Create a PHP script, that is executed from the command line, which accepts a CSV file as input and processes the CSV file.

# Prerequisite
- PHP version: 8.1+
- MySQL version 8+
- Installed Docker

# Installation
```bash
git clone https://github.com/Siyi-C/scriptPHP.git
```
# Run the script
```bash 
docker compose up --build
```
If you are running script inside docker,
```bash 
docker ps 
```
You will see the container list.

<p align="center">
  <img src="assets/dockerContainer.png" width="100%">
</p>

## If you use .env

### Database Credential
copy env.example to your env file, you can use below credential
```bash 
MYSQL_ROOT_PASSWORD=password
DB_HOST=db 
MYSQL_DATABASE=mydb
MYSQL_USER=user
MYSQL_PASSWORD=password
```

Copy the scriptphp-php container id 
```bash
docker exec -it [past_container_id_here] bash
```
You are now inside the Docker container. 

## CLI Usage
```bash
php user_upload.php --help
```

```bash
php user_upload.php --create_table
```

```bash
php user_upload.php --create_table --dry_run
```

```bash
php user_upload.php --file users.csv
```

```bash
php user_upload.php --file users.csv --dry_run
```

## If you are not use .env
### Database Credential
```bash 
MYSQL_ROOT_PASSWORD=password
DB_HOST=127.0.0.1 
MYSQL_DATABASE=mydb
MYSQL_USER=user
MYSQL_PASSWORD=password
```
```bash 
php user_upload.php --create_table -u user -h 127.0.0.1 -p password
```

```bash
php user_upload.php --create_table -u user -h 127.0.0.1 -p password  --dry_run
```

```bash
php user_upload.php --file users.csv -u user -h 127.0.0.1 -p password
```

```bash
php user_upload.php --file users.csv -u user -h 127.0.0.1 -p password --dry_run
```
