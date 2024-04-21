<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // Example method
    public function findByEmail(string $email)
    {
        return $this->createQueryBuilder('u')
                    ->where('u.email = :email')
                    ->setParameter('email', $email)
                    ->getQuery()
                    ->getOneOrNullResult();
    }
}