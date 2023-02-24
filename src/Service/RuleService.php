<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Rule;
use App\Entity\RuleSet;
use App\Service\SensorService;

/**
 * Description of RuleService
 *
 * @author Carsten
 */
class RuleService {
    private $sensor;
    private $entity;

    public function __construct(EntityManagerInterface $entityManager, SensorService $sensor) {
        $this->entity = $entityManager;
        $this->sensor = $sensor;
    }
    
    public function checkRules() {
        $Rules = $this->entity->getRepository(Rule::class);
        
        foreach ($Rules->findAll() as $Rule) {
            if ($Rule->getActive()) {
                $allCheck = $Rule->getAllTrue();
                
                
                $succesOne = false;
                $failed = false;
                
                foreach ($Rule->getRuleSets() as $RuleSet) {
                    if($RuleSet->getActive()) {
                        $sensorCheck = $this->sensor->checkVSensor(
                                    $RuleSet->getSensor(), 
                                    $RuleSet->getMode(),
                                    $RuleSet->getOperation(), 
                                    $RuleSet->getValue()
                                );
                        
                        if ($allCheck && $sensorCheck && !$failed) {
                            // All okay
                        } else {
                            if ($allCheck && !$failed) {
                                // Failed
                                $failed = true;
                            } elseif ($sensorCheck && !$allCheck && !$succesOne) {
                                // One Req but not set
                                $succesOne = true;
                            } elseif ($sensorCheck && $allCheck) {
                                $failed = true;
                            }                        
                        }
                    }
                }
                if ($allCheck) {
                    $success = !$failed;
                } else {
                    $success = $succesOne;
                }
                
                $Rule->getTargetSensor()->setState([$success]);
                $this->entity->flush($Rule->getTargetSensor());
                
                dump($Rule->getTargetSensor()->getState());
            }
        }        
        
    }

}
