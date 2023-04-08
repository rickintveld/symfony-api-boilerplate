<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /** 
     * @throws NoResultException
     */
    public function findAll(): array
    {
        return $this->getEntityManager()->getRepository(User::class)->findBy(['enabled' => true]);
    }

    /**
     * @throws NoResultException
     */
    public function findById(int $id): User
    {
        return $this->getEntityManager()->find(User::class, $id);
    }

    /**
     * @throws UserNotFoundException
     */
    public function update($entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function store($entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove($entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
