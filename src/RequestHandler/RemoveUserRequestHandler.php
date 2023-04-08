<?php

namespace App\RequestHandler;

use App\Model\Identifier;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class RemoveUserRequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SerializerInterface $objectSerializer
    ) {
    }

    public function handle(Request $request): void
    {
        /** @var Identifier $identifier */
        $identifier = $this->objectSerializer->deserialize($request->getContent(), Identifier::class, 'json');

        $user = $this->userRepository->findById($identifier->getId());

        $this->userRepository->remove($user);
    }
}
