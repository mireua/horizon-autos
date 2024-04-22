<?php

namespace App\Repository;

use App\Entity\CarService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CarService|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarService|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarService[]    findAll()
 * @method CarService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarService::class);
    }
}