## How to install

---

### Petshop e-commerce
#### cloning the repository

```BASH
git clone git@github.com:juntyms/petshop.git
cd petshop
composer install
```

#### Running Docker

```BASH
cd petshop
./docker.sh rebuild
```
##### Other docker option
Starting Docker Server
```BASH
./docker.sh start
```
Shutting Down Docker Server
```BASH
./docker.sh down
```
Rubuilding Docker Server
```BASH
./docker.sh rebuild
```
SSH to docker Server
```BASH
./docker.sh ssh
```


#### configure .env 
Open .env file
```BASH
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=petshop_db
DB_USERNAME=petshop_user
DB_PASSWORD=petshop_pass
```
> Note:
>_DB_DATABASE, DB_USERNAME, DB_PASSWORD can be changed as per your prefrences_

#### Migrating Database
- Make sure database has been created before migrating

#### Accessing phpmyadmin
- Open web browser and visit the following link : [127.0.0.1:8081](http://127.0.0.1:8081)
- create your preferred database name or copy the **[sample .env configuration provided](https://github.com/juntyms/petshop#configure-env)**

#### Running migration
_access the shell and run the migration and seeder_
```BASH
./docker.sh ssh
php artisan migrate:fresh --seed
```

#### Swagger Api Endpoint Documentation
http://127.0.0.1/api/documentation

### PHP Insights
Check code quality using php [insights](https://phpinsights.com/)
```BASH
./docker.sh ssh
php artisan insight
```

### Testing
```BASH
./docker.sh ssh
php artisan test
```

### Admin User
>username : **admin@buckhill.co.uk**
>password: **admin**

### Normal User
> password : userpassword 

---

### Readme - Currency Conversion Package ( L3 Challege
#### [Currency conversion package](/src/packages/juntyms/currencyexchange/readme.md) 
