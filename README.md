## Comic Addict Project 👋

### System Requirements 👀

    * PHP
        > php:8.3-fpm
        > laravel 11
        > composer:2.2
    * Nginx (ex: http://localhost)
        > nginx:1.23-alpine
    * Mysql
        > mysql:8.0.21

### Installation 👀

    * Clone repository
        $ git clone git@github.com:manhmanhdzaii/fabbi-test.git

    * copy .env file from .env.example and then change database configuration
        $ cp .env.example .env

    * Build project
        $ docker compose up -d --build

    * Start environment
        $ docker-compose up -d  # to start base containers

    * Migrate database
        $ composer install
        $ php artisan key:generate
        $ php artisan migrate
        $ php artisan db:seed

### Core Features 👀

-   Design pattern service repository

### Architecture 👀

    * After you done following the procedure below, I expect that your directory consists of here.
    |-- ...
    |-- app/
        |-- ...
        |-- Helpers
        |-- Http
        |-- Models
        |-- Repositories
        |-- Services
        |-- Trains
        |-- ...
    |-- bootstrap/
    |-- config/
    |-- database/
    |-- docker/
        |-- mysql
        |-- nginx
        |-- php
    |-- public
    |-- resources
    |-- routes
    |-- stograge
    |-- Makefile
    |-- docker-compose.yml
    |-- composer.json
    |-- .env.example
    |-- README.md
    |-- ...
