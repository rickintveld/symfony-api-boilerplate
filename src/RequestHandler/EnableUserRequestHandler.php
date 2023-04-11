<?php

namespace App\RequestHandler;

use App\Model\Identifier;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class EnableUserRequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly SerializerInterface $objectSerializer
    ) {
    }

    public function handle(Request $request): void
    {
        $identifier = $this->objectSerializer->deserialize($request->getContent(), Identifier::class, 'json');

        $user = $this->userRepository->findById($identifier->getId());

        $user->enable();

        $this->userRepository->update($user);
    }
}
