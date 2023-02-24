<?php

namespace App\Repository;

use App\Entity\LightLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LightLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method LightLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method LightLog[]    findAll()
 * @method LightLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LightLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LightLog::class);
    }

    // /**
    //  * @return LightLog[] Returns an array of LightLog objects
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
    public function findOneBySomeField($value): ?LightLog
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
