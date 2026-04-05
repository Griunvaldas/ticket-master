# Ticket Master demo app

A [dunglas/symfony-docker](https://github.com/dunglas/symfony-docker) boilerplate-based simple [Symfony](https://symfony.com) ticket selling web application
with [FrankenPHP](https://frankenphp.dev) and [Mercure](https://mercure.rocks/) inside!

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Install make - `sudo apt install make`
3. Run `make build` to build fresh images
4. Run `make up` to launch application
5. Run `make migrations-migrate` to seed fresh database
6. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)

## Available commands
```
make help                           Outputs this help screen
make build                          Builds the Docker images
make up                             Start the docker hub in detached mode (no logs)
make up-debug                       Start the docker hub in detached mode (no logs) with xdebug
make start                          Build and start the containers
make down                           Stop the docker hub
make logs                           Show live logs
make sh                             Connect to the FrankenPHP container
make bash                           Connect to the FrankenPHP container via bash so up and down arrows go to previous commands
make test                           Start tests with phpunit, pass the parameter "c=" to add options to phpunit, example: make test c="--group e2e --stop-on-failure"
make composer                       Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
make vendor                         Install vendors according to the current composer.lock file
make sf                             List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
make cc                             Clear the cache
```

## Debugging with Xdebug and PhpStorm

First, [create a PHP debug remote server configuration](https://www.jetbrains.com/help/phpstorm/creating-a-php-debug-server-configuration.html):

1. In the `Settings/Preferences` dialog, go to `PHP | Servers`
2. Create a new server:
    - Name: `symfony` (or whatever you want to use for the variable `PHP_IDE_CONFIG`)
    - Host: `localhost` (or the one defined using the `SERVER_NAME` environment variable)
    - Port: `443`
    - Debugger: `Xdebug`
    - Check `Use path mappings`
    - Absolute path on the server: `/app`

3. In PhpStorm, click on `Start Listening for PHP Debug Connections`

4. On the command line, we might need to tell PhpStorm which
   [path mapping configuration](https://www.jetbrains.com/help/phpstorm/zero-configuration-debugging-cli.html#configure-path-mappings)
   should be used, set the value of the PHP_IDE_CONFIG environment variable to
   `serverName=symfony`, where `symfony` is the name of the debug server configured
   above.

   Example:

   ```console
   XDEBUG_SESSION=1 PHP_IDE_CONFIG="serverName=symfony" php bin/console ...
   ```

## Architecture Decision Records (ADR)
A set of ADRs can be found inside `docs` directory to explain various decisions made during application development

## License and Credits
Symfony Docker is available under the MIT License.
Created by [Kévin Dunglas](https://dunglas.dev), co-maintained by [Maxime Helias](https://twitter.com/maxhelias) and sponsored by [Les-Tilleuls.coop](https://les-tilleuls.coop).
