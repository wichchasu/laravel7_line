# Laravel7.x-Roles-Permissions-Admin-Paper-Dashboard-bootstrap4+Line
This is a Laravel 7 Permissions & Admin & Line

# Installation
* Create Database from mysql
* Clone the repository with git clone
* Copy .env.example file to .env and edit database credentials there
  * Go to https://developers.line.biz/
  * edit .env 
     * LINE_CLIENT_ID= ?
     * LINE_CLIENT_SECRET= ?
     * LINE_REDIRECT_URI= ?
* Run composer install
* Run php artisan migrate --seed (it has some seeded data - see below)
* Run php artisan serve (Laravel development server started: http://127.0.0.1:8000) 
* That's it: launch the main URL and login with default credentials admin@admin.com - password

