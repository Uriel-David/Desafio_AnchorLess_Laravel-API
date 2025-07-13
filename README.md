## Initial Setup

- Project requirements:
    - `PHP 8.2`
    - `Composer`
    - `MySQL` - (By default I used a basic DB with WampServer, but if you use another one change the settings in the .env of the project)

- Installation of dependencies: `composer install`

- Copy .env.example to an .env file and then run the command: `php artisan key:generate`

- Finally, use the command to run the migrations: `php artisan migrate`

## Start Project (API)

- To start the project and be able to consume its API use the command: `php artisan serve`

- To check the existing routes in the project, use the command: `php artisan route:list`

## How to run test cases

- First, check that the .env.testing file has an APP_KEY, if not, run this command: `php artisan key:generate --env=testing`

- After that you can use the command: `php artisan test`
    - If you want to run an isolated test scenario, you can also use the command: `php artisan test --filter=test_file_name`
