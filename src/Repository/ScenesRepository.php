<?php

namespace App\Repository;

use App\Entity\Scenes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Scenes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scenes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scenes[]    findAll()
 * @method Scenes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScenesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scenes::class);
    }

    // /**
    //  * @return Scenes[] Returns an array of Scenes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Scenes
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
