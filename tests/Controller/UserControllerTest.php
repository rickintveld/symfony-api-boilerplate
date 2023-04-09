<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserControllerTest extends ApiTestCase
{
    public function testFetchAllUsers(): void
    {
        $response = static::createClient()->request('GET', '/user/all');

        $user = json_encode([
            'id' => 1,
            'firstName' => 'Rick',
            'lastName' => 'in t Veld',
            'email' => 'intveld@redkiwi.nl',
            'enabled' => true
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['users' => [$user]]);
    }
}
