# Server App

A small app to filter a list of servers.

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker-compose build --pull --no-cache` to build fresh images
3. Run `docker-compose up -d`
4. Run `docker exec -it symfony-docker_php_1 bin/console app:read-spreadsheet` to read and save spreadsheet data into application cache. (This action needs to be executed after every change made in the spreadsheet).
5. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
6. Run `docker exec -it symfony-docker_php_1 bin/phpunit` to execute the tests.
7. Run `docker-compose down --remove-orphans` to remove the Docker containers.

