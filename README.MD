# Start up

### First installation

- Configure your `env.local`
- Start the docker instance `docker-compose up -d`

```bash
// Run the composer install
docker-compose run php-fpm composer install

// create the database tables
docker-compose run php-fpm bin/console doctrine:migration:migrate
```

# Request cycles

```mermaid
  flowchart LR
    A[Request] --> B{Controller}
    B -->|POST| C[RequestHandler]
    B -->|GET| D[Service]
    B -->|PATCH| C[RequestHandler]
    B -->|PUT| C[RequestHandler]
    B -->|DELETE| D[Service]
    C -->|Serializer| E[Repository]
    D -->|onSuccess| E[Repository]
```

# DEPTRAC

Deptrac is a static code analysis tool for PHP that helps you communicate, visualize and enforce architectural decisions in your projects. You can freely define your architectural layers over classes and which rules should apply to them.

```bash
docker-compose run php-fpm ./vendor/bin/deptrac
```

# PSALM

Psalm is a static analysis tool for finding errors in PHP applications.

```bash
docker-compose run php-fpm ./vendor/bin/psalm
```

# PHP-CS

The PHP Coding Standards Fixer (PHP CS Fixer) tool fixes your code to follow standards

```bash
docker-compose run php-fpm ./vendor/bin/phpcs ./src
```

# Tests

### Create tests database

```bash
docker-compose run php-fpm bin/console --env=test doctrine:database:create
```

### Create the test tables / columns

```bash
docker-compose run php-fpm bin/console --env=test doctrine:schema:create
```

### Run tests

```bash
docker-compose run php-fpm bin/phpunit
```
