# Create tests database

php bin/console --env=test doctrine:database:create

# Create the tables / columns

php bin/console --env=test doctrine:schema:create

# Insert Fixtures

php bin/console --env=test doctrine:fixtures:load --purge-with-truncate
