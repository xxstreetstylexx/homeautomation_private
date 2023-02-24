<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Device;
use App\Entity\DeviceSetting;

/**
 * Description of DeviceService
 *
 * @author Carsten
 */
class DeviceService {
    private $entity;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entity = $entityManager;
                
    }
    
    public function update() {
        
        $Device = $this->entity->getRepository(Device::class);
        
        $dev = $Device->findOneBy(['ip' => $_SERVER['REMOTE_ADDR']]);
        
        if ($dev === null) {
            $dev = new Device();
            $dev->setIp($_SERVER['REMOTE_ADDR']);
            $dev->setName($_SERVER['REMOTE_ADDR']);
            $dev->setLocation('Unknown');
            
            $devSetting = new DeviceSetting();
            $devSetting->setDeviceId($dev);
            
            $this->entity->persist($dev);
            $this->entity->persist($devSetting);

            $this->entity->flush();
        }
        
        $dev->setLastseen(new \DateTime());
        
        $this->entity->persist($dev);        
        $this->entity->flush();
        
    }
    
    public function get() {
        
        $this->update();        
        
        $Device = $this->entity->getRepository(Device::class);
        
        $dev = $Device->findOneBy(['ip' => $_SERVER['REMOTE_ADDR']]);
        
        $settings = $dev->getDeviceSetting()->getConfig();
        
        sort($settings);
        
        return [
            'name' => $dev->getName(),
            'location' => $dev->getLocation(),
            'setting' => $settings 
        ];
        
    }
    
}
