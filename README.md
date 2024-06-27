# Assignement for GiG Media | Alan Silva

This is a step-by-step to help while executing the project.
- Tech specs:
    - PHP 8
    - Composer 2
    - Laravel 8
    - MySQL 5.7
    - Docker
- Details:
    - API for Post (list/delete) and Comments (list/delete/create)

## Running the project
- You can run the project using your local PHP, Composer and MySQL software or take the option below using Docker

### Using Docker
- Rename the file `.env.example` to `.env`
- In the `terminal`, inside the project root folder `/`, run:
    ```
    docker-compose up --build
    ```
- In another `terminal` tab/window, run the commands below. After that you will have the app ready and seeded with the initial generated data.
    ```
    docker exec -it gigmedia_laravel_app bash
    composer install
    php artisan config:clear
    php artisan migrate
    php artisan db:seed 
    ```
- In order to test the endpoints, you need to take the address `http://localhost:8001`
- In case you decide to run the tests, you will need to create a database called `gigmedia_laravel_testing` (see file .env.testing)