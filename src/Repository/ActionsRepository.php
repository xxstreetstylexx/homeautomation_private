<?php

namespace App\Repository;

use App\Entity\Actions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Actions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Actions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Actions[]    findAll()
 * @method Actions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionsRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Actions::class);
    }

    public function getActiveWithTime() {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        
        $Time = new \DateTime('now');

        $q = $qb->select(array('p'))
                ->from('App:Actions', 'p')
                ->where(
                        ':Time between p.StartTime AND p.EndTime'
                )
                ->andWhere(
                        'p.Active = 1'
                )
                ->setParameter('Time', $Time->format('H:i:s'))
                ->orderBy('p.id', 'ASC')
                ->getQuery();

        return $q->getResult();
    }

    // /**
    //  * @return Actions[] Returns an array of Actions objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('a')
      ->andWhere('a.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('a.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Actions
      {
      return $this->createQueryBuilder('a')
      ->andWhere('a.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
