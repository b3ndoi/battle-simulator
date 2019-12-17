[![Build Status](https://travis-ci.org/b3ndoi/battle-simulator.svg?branch=master)](https://travis-ci.org/b3ndoi/battle-simulator)

# Battle Simulator

## Initial Setup

after cloning the repo run `composer install` and `npm install` (if you want to change the JS files)

if you dont have node.js please install it before you run `npm install`

make an .env file and copy the contents of the .env.example file into it `cp .env.example .env`

run the artisan command `php artisan key:generate` to generate the app key

create database and set env file to use correct database

run the artisan command `php artisan migrate` to migrate the tables

run the artisan command `php artisan db:seed` to seed some initial data

