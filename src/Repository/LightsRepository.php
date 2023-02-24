<?php

namespace App\Repository;

use App\Entity\Lights;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lights|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lights|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lights[]    findAll()
 * @method Lights[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LightsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lights::class);
    }

    // /**
    //  * @return Lights[] Returns an array of Lights objects
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
    public function findOneBySomeField($value): ?Lights
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
