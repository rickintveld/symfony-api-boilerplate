<?php

namespace App\Test\Unit\RequestHandler;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use App\RequestHandler\CreateUserRequestHandler;
use App\Serializer\ObjectSerializer;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

class CreateUserRequestHandlerTest extends TestCase
{
    public function testCreateUser(): void
    {
        $user = new User();
        $user
            ->setFirstName('Test')
            ->setLastName('Testing')
            ->setPassword('Testing123!@')
            ->setEmail('test@testing.com')
            ->setEnabled(true);

        $user->setCreated();
        $user->setUpdated();

        $payload = json_encode([
            'firstName' => 'Test',
            'lastName' => 'Testing',
            'password' => 'Testing123!@',
            'email' => 'test@testing.com',
            'enabled' => true
        ]);

        $request = new Request(content: $payload);

        $objectSerializer = $this->getMockBuilder(ObjectSerializer::class)->onlyMethods(['deserialize'])->getMock();
        $objectSerializer
            ->expects(self::once())
            ->method('deserialize')
            ->with($request->getContent(), User::class, 'json')
            ->willReturn($user);

        $userRepository = $this
            ->getMockBuilder(UserRepositoryInterface::class)
            ->onlyMethods(['findAll', 'store', 'findById', 'update', 'remove'])
            ->disableOriginalConstructor()
            ->getMock();
        $userRepository->expects(self::once())->method('store')->with($user);

        $requestHandler = new CreateUserRequestHandler($userRepository, $objectSerializer);
        $requestHandler->handle($request);
    }
}
