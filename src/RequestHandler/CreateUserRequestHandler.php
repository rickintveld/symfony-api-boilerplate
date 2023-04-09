<?php

namespace App\RequestHandler;

use App\Entity\User;
use App\Repository\UserRepository;
use App\RequestHandler\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class CreateUserRequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SerializerInterface $objectSerializer
    ) {
    }

    public function handle(Request $request): void
    {
        /** @var User $user */
        $user = $this->objectSerializer->deserialize($request->getContent(), User::class, 'json');

        $user->disable();

        $this->userRepository->store($user);
    }
}
