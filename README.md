# Family Hub Api
Family Hub API stores and allows management of family tree.

## Requirements

-   php >=7.2
-   composer
-   mysql

You can install laragon to easily meet these requirements or use Docker instead.


## Installation

Clone the repository and navigate into the source code folder

```
git clone https://github.com/HerringTheCoder/Spinback.git
cd Spinback
```

(Optional) If you are using Docker you can quickly run your local server, database, php v7.3.11 and cache service 
```
docker-compose up -d
```

Install required dependencies

```
composer install
```

Copy the contents of `.env.example` into `.env` file and configure the environment (e.g. database connection - not a necessity with docker)

```
cp .env.example .env
```

Generate the application key, run database migrations

```
php artisan key:generate
php artisan migrate
```

You can also populate the database

```
(TBD)
```


### Startup

For development purposes run one of the servers built in:

```
- Laragon (default port: 8000)
- Docker (default port: 14350)
- Php/Laravel server (invoked by 'php artisan serve', default port: 8000)
```
Your application should be ready to use at http://localhost:port_number address
