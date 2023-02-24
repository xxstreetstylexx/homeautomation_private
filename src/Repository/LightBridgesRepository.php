<?php

namespace App\Repository;

use App\Entity\LightBridges;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LightBridges|null find($id, $lockMode = null, $lockVersion = null)
 * @method LightBridges|null findOneBy(array $criteria, array $orderBy = null)
 * @method LightBridges[]    findAll()
 * @method LightBridges[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LightBridgesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LightBridges::class);
    }

    // /**
    //  * @return LightBridges[] Returns an array of LightBridges objects
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
    public function findOneBySomeField($value): ?LightBridges
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
