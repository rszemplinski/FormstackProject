# Formstack Project

User MVC interview project.

## Instructions

run `vagrant up`

run `vagrant ssh`

run `cd /vagrant`

run `composer install`

run `./app/database/phinx migrate`

run `./app/database/phinx seed:run`

Hit any user endpoint

-   GET `/user/{userId}` (get specific user)
-   GET `/user` (get all users)
-   POST `/user/upload_avatar/{userId}` (upload user avatar)
-   POST `/user` (create)
-   PUT `/user/{userId}` (update user)
-   DELETE `/user/{userId}` (delete user)

## Note!

This project was influenced by [Barebones PHP](https://github.com/barebone-php/barebone)! It was stripped down even more to get to the bare necessities that this project required.
