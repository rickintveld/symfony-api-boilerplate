<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): User;

    public function update(User $user): void;

    public function store(User $user): void;

    public function remove(User $user): void;
}
