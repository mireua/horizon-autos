<?php

namespace App\Repository;

use App\Entity\TestDriveAppointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TestDriveAppointment>
 *
 * @method TestDriveAppointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestDriveAppointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestDriveAppointment[]    findAll()
 * @method TestDriveAppointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestDriveAppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestDriveAppointment::class);
    }

    public function findByUser(int $userId): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :val')
            ->setParameter('val', $userId)
            ->getQuery()
            ->getResult();
    }

    public function findByCar(int $carId): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.car = :val')
            ->setParameter('val', $carId)
            ->getQuery()
            ->getResult();
    }
}