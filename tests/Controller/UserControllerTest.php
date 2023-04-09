<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserControllerTest extends ApiTestCase
{
    public function testSomething(): void
    {
        $response = static::createClient()->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/']);
    }
}
