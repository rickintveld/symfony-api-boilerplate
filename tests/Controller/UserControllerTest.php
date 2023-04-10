<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserControllerTest extends ApiTestCase
{
    public function testFetchAllUsers(): void
    {
        static::createClient()->request('GET', '/user/all');

        $users = json_encode([[
            'id' => 1,
            'firstName' => 'Rick',
            'lastName' => 'in t Veld',
            'email' => 'rick@test.nl',
            'password' => 'Test123!',
            'enabled' => true
        ]]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains(['users' => $users]);
    }

    public function testFetchOneUser(): void
    {
        static::createClient()->request('GET', '/user/1');

        $user = json_encode([
            'id' => 1,
            'firstName' => 'Rick',
            'lastName' => 'in t Veld',
            'email' => 'rick@test.nl',
            'password' => 'Test123!',
            'enabled' => true
        ]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains(['user' => $user]);
    }

    public function testDisableUser(): void
    {
        static::createClient()->request('PATCH', '/user/disable', ['body' => json_encode(['id' => 1])]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains(['message' => 'User is disabled!']);
    }

    public function testEnableUser(): void
    {
        static::createClient()->request('PATCH', '/user/enable', ['body' => json_encode(['id' => 1])]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains(['message' => 'User is enabled!']);
    }

    public function testRemoveUser(): void
    {
        static::createClient()->request('DELETE', '/user/remove/1');

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains(['message' => 'User is removed!']);
    }

    public function testCreateUser(): void
    {
        $user = json_encode([
            'id' => 1,
            'firstName' => 'Rick',
            'lastName' => 'in t Veld',
            'email' => 'rick@test.nl',
            'password' => 'Test123!',
            'enabled' => true
        ]);

        static::createClient()->request('POST', '/user/create', ['body' => $user]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains(['message' => 'Successfully created the new user!']);
    }
}
