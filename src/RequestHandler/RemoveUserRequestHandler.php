<?php

namespace App\RequestHandler;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class RemoveUserRequestHandler implements RequestHandlerInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function handle(Request $request): void
    {
        /** @psalm-suppress MixedAssignment */
        $data = json_decode($request->getContent(), true);

        if (!isset($data['id'])) {
            throw new \Exception('Identifier not found');
        }

        /** @psalm-suppress MixedArrayAccess */
        $user = $this->userRepository->findById((int) $data['id']);

        $this->userRepository->remove($user);
    }
}
