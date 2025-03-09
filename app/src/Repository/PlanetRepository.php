<?php

namespace App\Repository;

use App\Entity\Planet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Planet>
 */
class PlanetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planet::class);
    }

    public function getRandomPlanetWithoutOwner(): Planet
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.owner is null')
            ->orderBy('RANDOM()')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
            ;
    }
}
