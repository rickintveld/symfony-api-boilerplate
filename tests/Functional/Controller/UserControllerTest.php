<?php

namespace App\Tests\Functional\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;

class UserControllerTest extends ApiTestCase
{
    private Connection $connection;
    private EntityManagerInterface $entityManager;
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
        $this->entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        $this->tableName = $this->entityManager->getClassMetadata(User::class)->getTableName();
        $this->connection = $this->entityManager->getConnection();

        $this->prepareDatabaseData();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->reloadDatabase();
        $this->connection->close();
        unset($this->connection, $this->entityManager, $this->tableName);
    }

    public function testFetchAllUsers(): void
    {
        static::createClient()->request('GET', '/user/all');

        $users = json_encode([$this->user]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains(['users' => $users], false);
    }

    public function testFetchOneUser(): void
    {
        static::createClient()->request('GET', '/user/' . self::TEST_USER_ID);

        $user = json_encode($this->user);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains(['user' => $user], false);
    }

    public function testDisableUser(): void
    {
        static::createClient()->request('PATCH', '/user/disable', ['body' => json_encode(['id' => self::TEST_USER_ID])]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonEquals(['message' => 'User is disabled!']);
    }

    public function testEnableUser(): void
    {
        static::createClient()->request('PATCH', '/user/enable', ['body' => json_encode(['id' => self::TEST_USER_ID])]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonEquals(['message' => 'User is enabled!']);
    }

    public function testRemoveUser(): void
    {
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

    private function prepareDatabaseData(): void
    {
        $this->reloadDatabase();

        $user = new User();
        $user->setFirstName($this->user['firstName']);
        $user->setLastName($this->user['lastName']);
        $user->setEmail($this->user['email']);
        $user->setPassword($this->user['password']);

        $user->enable();
        $user->setCreated();
        $user->setUpdated();

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function reloadDatabase(): void
    {
        $this->connection->executeQuery('TRUNCATE TABLE ' . $this->tableName);
    }
}
