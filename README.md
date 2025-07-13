# Backend (API) - Visa Dossier (PHP + Laravel)

API for uploading, viewing and deleting documents organized by category.

## Initial Setup

- Project requirements:
    - `PHP 8.2`
    - `Composer`
    - `MySQL` - (By default I used a basic DB with WampServer, but if you use another one change the settings in the .env of the project)

- Clone the backend repository:
    - `git@github.com:Uriel-David/Desafio_AnchorLess_Laravel-API.git`

- Installation of dependencies: `composer install`

- Copy .env.example to an .env file and then run the command: `php artisan key:generate`

- Finally, use the command to run the migrations: `php artisan migrate`

## Start Project (API)

- To start the project and be able to consume its API use the command: `php artisan serve`

- To check the existing routes in the project, use the command: `php artisan route:list`

## How to use the application

- You can test the API using Postman, the base URL of the endpoints is http://localhost:8000/api.

- The endpoints are:
    - http://localhost:8000/api/documents/list (GET)
        - No `body` type required
    - http://localhost:8000/api/documents/upload (POST)
        - It needs a `form-data body`:
            - file - the file itself
            - type - file type (document or image)
            - tag - file category
    - http://localhost:8000/api/documents/delete (DELETE)
        - It needs a `json body`:

            ```json
            {
                "id": 2
            }
            ```

- Now all you have to do is make the requests and you'll see the API working.

## How to run test cases

- First, check that the .env.testing file has an APP_KEY, if not, run this command: `php artisan key:generate --env=testing`

- After that you can use the command: `php artisan test`
    - If you want to run an isolated test scenario, you can also use the command: `php artisan test --filter=test_file_name`
