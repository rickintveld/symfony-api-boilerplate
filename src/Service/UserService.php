<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function disable(User $user): void
    {
        $user->disable();

        $this->userRepository->update($user);
    }

    public function enable(User $user): void
    {
        $user->enable();

        $this->userRepository->update($user);
    }

    public function remove(User $user): void
    {
        $this->userRepository->remove($user);
    }
}
