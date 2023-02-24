<?php

namespace App\Repository;

use App\Entity\DeviceSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeviceSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviceSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviceSetting[]    findAll()
 * @method DeviceSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeviceSetting::class);
    }

    // /**
    //  * @return DeviceSetting[] Returns an array of DeviceSetting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeviceSetting
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
