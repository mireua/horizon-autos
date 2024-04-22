<?php

namespace App\Repository;

use App\Entity\Inquiry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InquiryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inquiry::class);
    }

    // Additional methods can be added here, e.g., findByUser(), findByCar(), etc.
}
