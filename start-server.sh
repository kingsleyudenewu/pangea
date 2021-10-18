#!/bin/bash

echo "Installing application"
sudo composer install
sudo composer du

echo "creating .env variables"
sudo cp .env.example .env

echo "generating application key"
sudo php artisan key:generate

echo "creating migration"
sudo php artisan migrate

echo "starting app"
sudo php artisan serve --port=8000 &
sudo php artisan serve --port=9005 &

echo "publisher and subscriber is running"
