## How to install

### Petshop e-commerce
#### clone repository

```
git@github.com:juntyms/petshop.git
cd petshop
composer install
```

#### Running Docker

```
cd petshop
./local.sh rebuild
```
#####Other docker option
Starting Docker Server
```
./docker.sh start
```
Shutting Down Docker Server
```
./docker.sh down
```
Rubuilding Docker Server
```
./docker.sh rebuild
```
SSH to docker Server
```
./docker.sh ssh
```


#### Configure .env 
Open .env file
```
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
- create your preferred database name or copy the **sample .env configuration provided**

#### Running migration
_access the shell and run the migration and seeder_
```
./docker.sh ssh
php artisan migrate:fresh --seed
```

#### Swagger Api Endpoint Documentation
http://127.0.0.1/api/documentation



### Default Admin User
>username : **admin@buckhill.co.uk**
>password: **admin**

