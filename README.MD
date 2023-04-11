# Symfony API Boilerplate

[![Actions Status](https://github.com/rickintveld/symfony-api-boilerplate/workflows/CI/badge.svg)](https://github.com/rickintveld/symfony-api-boilerplate/actions)

## Configuration

- Configure your `env.local`

```env
DATABASE_NAME=api
DATABASE_USER=root
DATABASE_PASSWORD=root
DATABASE_ROOT_PASSWORD=root

DATABASE_URL=mysql://${DATABASE_USER}:${DATABASE_PASSWORD}@database:3306/${DATABASE_NAME}?serverVersion=mariadb-10.4.21
```

## Installation

```bash
// Start the docker instance
docker-compose up -d

// Run the composer install
docker-compose run php-fpm composer install

// create the database tables
docker-compose run php-fpm bin/console doctrine:migration:migrate
```

## Request cycles

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

## Deptrac

Deptrac is a static code analysis tool for PHP that helps you communicate, visualize and enforce architectural decisions in your projects. You can freely define your architectural layers over classes and which rules should apply to them.

```bash
docker-compose run php-fpm ./vendor/bin/deptrac
```

## Psalm

Psalm is a static analysis tool for finding errors in PHP applications.

```bash
docker-compose run php-fpm ./vendor/bin/psalm
```

## Php-cs

The PHP Coding Standards Fixer (PHP CS Fixer) tool fixes your code to follow standards

```bash
docker-compose run php-fpm ./vendor/bin/phpcs ./src
```

# Tests

## Create tests database

```bash
docker-compose run php-fpm bin/console --env=test doctrine:database:create
```

## Create the test tables / columns

```bash
docker-compose run php-fpm bin/console --env=test doctrine:schema:create
```

## Run tests

```bash
docker-compose run php-fpm bin/phpunit
```
