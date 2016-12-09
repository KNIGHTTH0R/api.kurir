# API for Kurir Site

API kurir

## Installation

### Requirements
- php version > 5.6
- MySQL, <https://www.mysql.com/>
- redis-server, <http://redis.io/>
- composer, <https://getcomposer.org/>

### How to start
1. Install all the requirements
2. `$ git clone git@github.com:harryosmar/kurir.git front`
3. `$ cd front`
4. `$ composer install`
5. `$ cp env.example .env`
make sure all composer packages dependencies is sucessfully installed
```
Generating autoload files
> Illuminate\Foundation\ComposerScripts::postInstall
> php artisan optimize
Generating optimized class loader
```

### Running Database Migration
```
$ php artisan migrate --seed
```
You can find the users table seed data in https://github.com/harryosmar/api.kurir/blob/master/database/seeds/UsersTableSeeder.php

Then start your web server in port `8001` different port with frontend
```
$ php artisan serve --port=8001
Laravel development server started on http://localhost:8001/
```

### Note
API endpoints list shared by `postman` connection link : https://www.getpostman.com/collections/77c503026058ca1ed41c
Set the postman environment variables
```
host:localhost
port:8001
```

### Wiki
https://github.com/harryosmar/api.kurir/wiki



## About

### Submitting bugs and feature requests
Harry Osmar Sitohang - <harryosmarsitohang@gmail.com> - <https://github.com/harryosmar><br />
See also the list of [contributors](https://github.com/onolinus/ApiSurveyOnline/contributors) which participated in this project
