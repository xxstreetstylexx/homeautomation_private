<?php

/*
 *  Friendu_Frontend // PowerService.php
 *  
 *  (c) 2018 Carsten Zeidler
 */

namespace App\Service;

    
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PowerLog;
/**
 * Description of PowerService
 *
 * @author Carsten
 */
class PowerService {
    
    private $entity;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entity = $entityManager;
    }
    
    /**
     * 
     * @return PowerLog
     */
    public function getAllAvaiable() {
        $Power = $this->entity->getRepository(PowerLog::class);
        
        return count($Power->findAll());
        
    }
    
    public function getLastHour() {
        $Power = $this->entity->getRepository(PowerLog::class);
        
        $date = new \DateTime();
        $date->modify('-1 hour');

        $Power
            ->createQueryBuilder('t')
            ->andWhere('t.LastUpdate > :date')
            ->setParameter(':date', $date)
            ->getQuery()
            ->getResult();
        
        return $Power;
        
    }
}
