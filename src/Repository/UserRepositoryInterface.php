<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

interface UserRepositoryInterface
{
    /** 
     * @return User[]
     * @throws NoResultException
     */
    public function findAll(): array;

    /**
     * @throws NoResultException
     */
    public function findById(int $id): User;

    /**
     * @return User[]
     * @throws NoResultException
     */
    public function findBy(array $criteria, ?array $orderBy = null, int $limit = null, int $offset = null): array;

    /**
     * @throws EntityNotFoundException
     */
    public function update(User $user): void;

    /**
     * @throws NonUniqueResultException
     */
    public function store(User $user): void;

    /**
     * @throws EntityNotFoundException
     */
    public function remove(User $user): void;
}
