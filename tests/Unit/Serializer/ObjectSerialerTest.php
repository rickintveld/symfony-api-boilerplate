<?php

namespace App\Tests\Unit\Serializer;

use App\Entity\User;
use App\Serializer\ObjectSerializer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ObjectSerialerTest extends KernelTestCase
{
    public function testObjectToJsonSerialize(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var ObjectSerializer $objectSerializer */
        $objectSerializer = $container->get(ObjectSerializer::class);

        $user = new User();
        $user
            ->setFirstName('Test')
            ->setLastName('Testing')
            ->setEmail('test@email.com')
            ->setPassword('testing123!@')
            ->setEnabled(true);

        $payload = json_encode([
            'id' => null,
            'firstName' => 'Test',
            'lastName' => 'Testing',
            'email' => 'test@email.com',
            'password' => 'testing123!@',
            'enabled' => true,
            'created' => null,
            'updated' => null
        ]);

        $this->assertEquals($objectSerializer->deserialize($payload, User::class, 'json'), $user);
    }

    public function testJsonToObjectSerialize(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var ObjectSerializer $objectSerializer */
        $objectSerializer = $container->get(ObjectSerializer::class);

        $user = new User();
        $user
            ->setFirstName('Test')
            ->setLastName('Testing')
            ->setEmail('test@email.com')
            ->setPassword('testing123!@')
            ->setEnabled(true);

        $payload = json_encode([
            'id' => null,
            'firstName' => 'Test',
            'lastName' => 'Testing',
            'email' => 'test@email.com',
            'password' => 'testing123!@',
            'enabled' => true,
            'created' => null,
            'updated' => null
        ]);

        $this->assertEquals($objectSerializer->serialize($user, 'json'), $payload);
    }
}
