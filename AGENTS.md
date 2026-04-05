# Project

This is a Symfony application running on [FrankenPHP](https://frankenphp.dev), generated using [Symfony Docker](https://github.com/dunglas/symfony-docker). The stack includes Caddy (via FrankenPHP), [Mercure](https://mercure.rocks) for real-time, and [Vulcain](https://vulcain.rocks) for preloading. The Dockerfile uses multi-stage builds with separate dev and prod targets.

## Tech Stack

- **PHP**: 8.5
- **Server**: FrankenPHP (with Caddy)
- **Container**: Docker (multi-stage builds for dev/prod)
- **CLI**: Makefile available for common tasks

## Code Quality Tools

- **PHPStan**: Static analysis (`make phpstan`)
- **PHPUnit**: Testing (`make phpunit`)
- **PHPCSFixer**: Code style fixing (`make php-cs-fixer`)

## Development

### Starting the stack

```bash
make start   # Build and start containers
make logs    # View live logs
```

### Common tasks

```bash
make sh                                      # Enter FrankenPHP container
make sf c=about                              # Run Symfony console command
make sf c=doctrine:migrations:migrate        # Run database migrations
make composer c=require symfony/foo         # Run composer
make cc                                      # Clear Symfony cache
```

### Code quality

```bash
make phpcsfixer c="--dry-run --diff"  # Preview style changes first
make phpstan                          # Static analysis (run after phpcsfixer)
```

### Database setup

```bash
make sf c=doctrine:database:create     # Create test database (APP_ENV=test)
make sf c=doctrine:migrations:migrate # Run migrations before tests
```

### Testing

```bash
make sf c=doctrine:database:create     # Required before first test run
make sf c=doctrine:migrations:migrate
make phpunit                           # Run all tests
make phpunit c="--filter=testFoo"      # Run specific test
make test c="--group e2e"              # Run tests by group
```

### Stopping

```bash
make down  # Stop and remove containers
```
