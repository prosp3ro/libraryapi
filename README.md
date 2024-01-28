# library api

## Prerequisites

- PHP 8.3.2 (latest) with extensions
- Composer
- Laravel 10 (latest)

Apache/Nginx is not needed for testing since `artisan serve` is fine.

## Installation

1. Install latest version of php

```bash
sudo pacman -Sy php php-mysql php-gd

# or for debian based systems
sudo apt install php php-mysql php-gd
```

other systems:

https://www.php.net/manual/en/install.php

2. Update php.ini file

Edit `/etc/php/php.ini` and uncomment this line:

```bash
extension=pdo_mysql

# and maybe these too:
extension=iconv
extension=gd
extension=curl
```

3. Install composer

```bash
sudo pacman -Sy composer

# or
sudo apt install composer
```

https://getcomposer.org/download/

4. Clone project

```bash
git clone https://github.com/prosp3ro/libraryapi
```

5. Install dependencies

```bash
cd libraryapi
composer install
```

6. Configure env variables && create a database

```bash
cp .env.example .env
```

Edit these lines:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=libraryapi
DB_USERNAME=root
DB_PASSWORD=
```

7. Generate application key

```bash
php artisan key:generate
```

8. Generate secret JWT key

```bash
php artisan jwt:secret
```

This will update `.env` file with `JWT_SECRET=value`

9. Migrate database

```bash
php artisan migrate
```

10. Start the development server

```bash
php artisan serve
```

The application will be accessible at `http://localhost:8000`.
