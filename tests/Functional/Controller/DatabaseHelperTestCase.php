<?php

namespace App\Tests\Functional\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;

abstract class DatabaseHelperTestCase extends ApiTestCase
{
    protected string $className;
    protected EntityManagerInterface $entityManager;
    protected string $tableName;

    private Connection $connection;

    public function setUp(): void
    {
        parent::setUp();

        $this->setClassName();

        if (!isset($this->className)) {
            throw new \Exception(sprintf('%s must have a $className', get_class($this)));
        }

        /** @var EntityManagerInterface $entityManager */
        $this->entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $this->tableName = $this->entityManager->getClassMetadata($this->className)->getTableName();

        $this->connection = $this->entityManager->getConnection();

        $this->reloadDatabase();

        $this->prepareDatabaseData();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->reloadDatabase();

        $this->connection->close();

        unset($this->connection, $this->entityManager, $this->tableName);
    }

    abstract public function prepareDatabaseData(): void;

    abstract public function setClassName(): void;

    protected function reloadDatabase(): void
    {
        $this->connection->executeQuery('TRUNCATE TABLE ' . $this->tableName);
    }
}
