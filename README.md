# Carefer Technical Assessment

a Bus Tickets Reservation System with the following

* The ticket should be issued with the user's email
* The user Should be able to book a ticket for one or more passengers in one request
* The user should provide the seat Ids
* The user should get a discount if he booked for more than five passengers
* The user should provide the pickup and destination stations
* We have two types of buses one for long trips and one for short trips if the trip distance is
  greater than or equal to 100 KM will be considered a long trip
* The number of reserved tickets should don’t exceed the bus capacity
* If a user started a reservation on a bus no other user should be able to do a reservation on the
  same bus
* The reservation session should be only two minutes if the user exceeded the two minutes the
  reservation session should be canceled and make the bus free for reservation again
* The system should record all reservations in the database for reporting purposes

## Installation

### Prerequisites

* You must have Docker installed.

```shell
git clone https://github.com/abdullahessam/Carefer-task
cd Carefer-task
cp .env.example .env
composer install
php artisan key:generate
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
```

## Used Ports

this is the ports I used to for the project

| Port |     usage      |
|:-----|:--------------:|
| 8080 | laravel server |
| 3307 |     mysql      |
| 6378 |     redis      |

feel free to update any port in .env file

# Database structure

![alt database](https://www11.0zz0.com/2023/04/28/01/194804685.png)

# How it works ?

* using laravel sail package that provides ready docker container for the project php-8.0 / mysql / redis
  also I install ed phpmyadmin for accessing the database .
* using laravel sanctum for authentication and api token generation .
* I used redis for lock the bus by locking the line->id for 2 minutes and used delayed job for expiring the order if the
  use take no action (confirming / canceling)
* I handled seats number by just register the reserved seats in the database and get the available seats by subtracting
  the reserved seats from the bus capacity which is by default 20 seats .

# How to test ?

## after installing the project

i created database with sqlite for testing purpose, so you can run the tests without any problems also i created
database connection named : sqlite_testing in the .env.testing file .

```shell
./vendor/bin/sail test
```

# API Documentation and Endpoints

here is the postman collection for the api endpoints you can import it and test the api .
[![Run in Postman](https://run.pstmn.io/button.svg)](https://documenter.getpostman.com/view/2535308/2s93eR6bj5)

# The Main Flow to book a ticket

* The user should be logged in to make a reservation (login , register)
* The user should select the Line (bus) and the trip he wants to reserve .
* The user should select the seats he wants to reserve .
* The user should confirm the reservation .
* The user can cancel the reservation before the 2 minutes expire .
* The user can update his order line's and seat's before confirming the reservation .
* The user can get all his orders .

# Monitoring

* I used laravel telescope for monitoring the application requests , logs and the database queries .
  here you can access the telescope dashboard by this link http://localhost/telescope
* I used laravel horizon for monitoring the delayed jobs and the failed jobs .
  here you can access the horizon dashboard by this link http://localhost/horizon

# The Main Packages I used

* laravel sail
* laravel sanctum
* laravel telescope
* laravel horizon
* Spatie Data
* friendsofphp/php-cs-fixer

# Files Structure

* app/Http/Controllers/Api/V1/ : contains the api controllers
* app/Http/Requests/Api/V1/ : contains the api requests
* app/Models/ : contains the models
* app/Domains : contains the business logic
* app/Exceptions/ : contains the custom exceptions
* app/Http/Resources/ : contains the api resources
* app/Rules/ : contains the custom validation rules
* app/Helpers/ : contains the helper functions
* database/migrations/ : contains the database migrations
* tests/Feature/ : contains the feature tests
* route/v1/api.php : contains the api routes
* ./docker-compose.yml --> the docker compose file.
* ./docker/* --> Contains the docker services configurations such nginx.
* ./config/*.php --> Contains the configuration files such as sanctum and more.

# Finally

* thanks and if you have any questions please contact me.
