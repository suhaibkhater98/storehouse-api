## Installation

Clone the repo locally:

```sh
git@github.com:suhaibkhater98/storehouse-api.git
cd storehouse-api
```

Install PHP dependencies:

```sh
composer install
```


Create an SQLite database. You can also use another database (MySQL, Postgres), simply update your configuration accordingly.

```sh
touch database/database.sqlite
```

Run database migrations and seeder:

```sh
php artisan migrate --seed
```

Run artisan server:

```sh
php artisan serve
```

Make sure the Website is Running on localhost:8000 to be compatible with front-end
if not possible no worries you can change the URL in front-end Side
