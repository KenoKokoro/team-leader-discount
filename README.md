# Discounts calculator

## Requirements
1. Unix OS
2. Available `80` port or any value that is set in the `.env` file as `NGINX_PORT` value
3. Docker engine `1.13.0+` version or above
4. Docker compose `1.21+` version or above
5. `make` command installed
6. `git` installed
7. Check the `DOCKER_HOST_UID` to be equal to your local user `echo $UID` inside `.env`
8. Check the `DOCKER_HOST_GID` to be equal to your local user `echo $GID` inside `.env`

## Installation
1. `git clone git@github.com:KenoKokoro/team-leader-discount.git`
2. Go to the `team-leader-discount` folder and run `cp .env.example .env`. Here in your `.env` file set you desired values. 
Most important are those under the `#Docker` comment and the `API_KEY` (since this is required value)
3. Execute `make local-setup` to boot up the docker containers
4. That should be it
5. [Documentation link](http://localhost/api/v1/docs)

## Business rules explanation
1. Costumer with revenue over 1000 is calculated by giving 10% discount of whole price
2. If customer buys more than 5 items from *switches* category, gets 6th free. This is not the case if the customer buys 
12 items, he doesn't get 2 items free.
3. If customer buys 2 or more items from *tools* category, gets 20% discount on the cheapest item from the *tools* category
(not entirely cheapest item)

## Useful stuff (maybe)
1. To boot up the docker containers use: `docker-compose -f docker-compose.yml -f dev.docker-compose.yml up -d --build`
2. `dev.docker-compose.yml` is used only to keep local cache of the composer files. It is not required for the application to boot
successfully.
3. To run composer command `docker-compose exec --user=nginx composer`
4. To run artisan command `docker-compose exec --user=nginx php artisan`
5. To run unit tests `docker-compose exec --user=nginx vendor/bin/phpunit`
6. `--user=nginx` and `DOCKER_HOST_GID` with `DOCKER_HOST_UID` are used to preserve the user permissions inside the docker container
and outside of it and avoid file permissions issue where the files gets owned by the container user and locks up for the host user