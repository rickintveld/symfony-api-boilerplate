<?php

namespace App\Tests\Functional\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Enum\State;

class UserControllerTest extends ApiTestCase
{
    private const TEST_USER_ID = 1;

    public function testFetchAllUsers(): void
    {
        static::createClient()->request('GET', '/user/all');

        $users = json_encode([[
            'id' => self::TEST_USER_ID,
            'firstName' => 'Rick',
            'lastName' => 'in t Veld',
            'email' => 'rick@test.nl',
            'password' => 'Test123!',
            'enabled' => true
        ]]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains(['users' => $users], false);
    }

    public function testFetchOneUser(): void
    {
        static::createClient()->request('GET', '/user/' . self::TEST_USER_ID);

        $user = json_encode([
            'id' => self::TEST_USER_ID,
            'firstName' => 'Rick',
            'lastName' => 'in t Veld',
            'email' => 'rick@test.nl',
            'password' => 'Test123!',
            'enabled' => true
        ]);

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
        $user = json_encode([
            'firstName' => 'Rick',
            'lastName' => 'in t Veld',
            'email' => 'rick@test.nl',
            'password' => 'Test123!',
            'enabled' => State::ENABLED->value
        ]);

        static::createClient()->request('POST', '/user/create', ['body' => $user]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonEquals(['message' => 'Successfully created the new user!']);
    }
}
