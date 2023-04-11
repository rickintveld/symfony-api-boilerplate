<?php

namespace App\Tests\Functional\Controller;

use DateTime;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;

class UserControllerTest extends ApiTestCase
{
    private Connection $connection;
    private string $tableName;

    private const TEST_USER_ID = 1;

    private array $user = [
        'id' => self::TEST_USER_ID,
        'firstName' => 'Rick',
        'lastName' => 'in t Veld',
        'email' => 'rick@test.nl',
        'password' => 'Test123!',
        'enabled' => true
    ];

    public function setUp(): void
    {
        parent::setUp();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        $this->tableName = $entityManager->getClassMetadata(User::class)->getTableName();
        $this->connection = $entityManager->getConnection();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->connection->close();
        unset($this->connection, $this->tableName);
    }

    public function testFetchAllUsers(): void
    {
        $this->reloadDatabase();

        static::createClient()->request('GET', '/user/all');

        $users = json_encode([$this->user]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains(['users' => $users], false);
    }

    public function testFetchOneUser(): void
    {
        $this->reloadDatabase();

        static::createClient()->request('GET', '/user/' . self::TEST_USER_ID);

        $user = json_encode($this->user);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains(['user' => $user], false);
    }

    public function testDisableUser(): void
    {
        $this->reloadDatabase();

        static::createClient()->request('PATCH', '/user/disable', ['body' => json_encode(['id' => self::TEST_USER_ID])]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonEquals(['message' => 'User is disabled!']);
    }

    public function testEnableUser(): void
    {
        $this->reloadDatabase();

        static::createClient()->request('PATCH', '/user/enable', ['body' => json_encode(['id' => self::TEST_USER_ID])]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonEquals(['message' => 'User is enabled!']);
    }

    public function testRemoveUser(): void
    {
        $this->reloadDatabase();

        static::createClient()->request('DELETE', '/user/remove/' . self::TEST_USER_ID);

        $this->assertResponseIsSuccessful();

        $this->assertJsonEquals(['message' => 'User is removed!']);
    }

    public function testCreateUser(): void
    {
        $user = json_encode($this->user);

        static::createClient()->request('POST', '/user/create', ['body' => $user]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonEquals(['message' => 'Successfully created the new user!']);
    }

    private function reloadDatabase(): void
    {
        $user = $this->user;
        $user['created'] = (new \DateTimeImmutable())->format('Y-m-d H:m:s');
        $user['updated'] = (new DateTime())->format('Y-m-d H:m:s');

        $this->connection->executeQuery('TRUNCATE TABLE ' . $this->tableName);
        $this->connection->executeStatement('INSERT INTO ' . $this->tableName . '(id, first_name, last_name, email, password, enabled, created, updated) VALUES (:id, :firstName, :lastName, :email, :password, :enabled, :created, :updated)', $user);
    }
}
