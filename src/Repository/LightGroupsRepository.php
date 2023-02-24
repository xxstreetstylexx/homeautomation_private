<?php

namespace App\Repository;

use App\Entity\LightGroups;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LightGroups|null find($id, $lockMode = null, $lockVersion = null)
 * @method LightGroups|null findOneBy(array $criteria, array $orderBy = null)
 * @method LightGroups[]    findAll()
 * @method LightGroups[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LightGroupsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LightGroups::class);
    }

    // /**
    //  * @return LightGroups[] Returns an array of LightGroups objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LightGroups
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
