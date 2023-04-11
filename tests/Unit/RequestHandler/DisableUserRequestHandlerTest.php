<?php

namespace App\Test\Unit\RequestHandler;

use App\Entity\User;
use App\Model\Identifier;
use App\Repository\UserRepositoryInterface;
use App\RequestHandler\EnableUserRequestHandler;
use App\Serializer\ObjectSerializer;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

class DisableUserRequestHandlerTest extends TestCase
{
    public function testDisableUser(): void
    {
        $identifier = new Identifier(1);

        $user = new User();
        $user
            ->setFirstName('Test')
            ->setLastName('Testing')
            ->setPassword('Testing123!@')
            ->setEmail('test@testing.com')
            ->setEnabled(true);

        $user->setCreated();
        $user->setUpdated();

        $payload = json_encode(['id' => $identifier->getId()]);

        $request = new Request(content: $payload);

        $objectSerializer = $this->getMockBuilder(ObjectSerializer::class)->onlyMethods(['deserialize'])->getMock();
        $objectSerializer
            ->expects(self::once())
            ->method('deserialize')
            ->with($request->getContent(), Identifier::class, 'json')
            ->willReturn($identifier);

        $userRepository = $this
            ->getMockBuilder(UserRepositoryInterface::class)
            ->onlyMethods(['findAll', 'store', 'findById', 'update', 'remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $userRepository->expects(self::once())->method('findById')->with($identifier->getId())->willReturn($user);
        $userRepository->expects(self::once())->method('update')->with($user);

        $requestHandler = new EnableUserRequestHandler($userRepository, $objectSerializer);
        $requestHandler->handle($request);
    }
}
