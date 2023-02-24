<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use App\Service\ApiService;
use App\Service\UrlBuilderService;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\HueService;
use App\Entity\LightBridges;
use App\Entity\Lights;
use App\Entity\PowerLog;
use App\Entity\Actions;
use App\Entity\Sensors;

/**
 * Description of SensorService
 *
 * @author Carsten
 */
class SensorService {

    private $Keys = [];
    private $Api;
    private $UrlBuilder;
    private $Hue;
    private $entity;
    private $PowerLogEnabled;

    public function __construct(ApiService $Api, UrlBuilderService $UrlBuilder, EntityManagerInterface $entityManager, HueService $Hue) {
        $this->entity = $entityManager;
        $this->UrlBuilder = $UrlBuilder;
        $this->Api = $Api;
        $this->Hue = $Hue;
        
        $this->PowerLogEnabled = false;
    }

    public function setKey($Ip, $key) {

        if (isset($this->Keys[$Ip])) {

            return true;
        }

        $this->Keys[$Ip] = $key;

        return true;
    }

    public function getSensors($bridgeIp) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'sensors'
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $data = $this->Api->request('GET', $fullUrl);

        return $data;
    }

    public function getSensor($bridgeIp, $sensorId) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'sensors',
            $sensorId
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $data = $this->Api->request('GET', $fullUrl);

        return $data;
    }

    public function getSensorByName($name) {

        $Return = false;

        $Sensors = $this->entity->getRepository(Sensors::class);

        $Sensor = $Sensors->findBy(['name' => $name]);

        dump($Sensor);

        return $return;
    }

    public function updateSensors() {

        $Bridges = $this->entity->getRepository(LightBridges::class);
        $Sensors = $this->entity->getRepository(Sensors::class);

        $AllBridges = $Bridges->findAll();
        $BridgeRows = [];
        foreach ($AllBridges as $BridgeData) {

            $BridgeRows[] = [$BridgeData->getId(), $BridgeData->getName()];
        }

        foreach ($AllBridges as $BridgeData) {

            $this->setKey($BridgeData->getIp(), $BridgeData->getAccount());

            $data = $this->getSensors($BridgeData->getIp());

            // Update Data

            foreach ($data as $InternalId => $SensorData) {

                if (isset($SensorData['uniqueid'])) {
                    $Sensor = $Sensors->findOneBy(['internalId' => $InternalId, 'uniqueid' => $SensorData['uniqueid']]);
                    if ($Sensor === null) {
                        // Create Light Entry

                        $Sensor = new Sensors();
                    }

                    $Sensor->setChecktime(new \DateTime());
                    $Sensor->setBridge($BridgeData);
                    $Sensor->setType((isset($SensorData['type'])) ? $SensorData['type'] : 'Unkown');
                    $Sensor->setInternalId($InternalId);
                    $Sensor->setName($SensorData['name']);
                    $Sensor->setVirtual(false);
                    $Sensor->setState($SensorData['state']);
                    $Sensor->setBattery((isset($SensorData['config']['battery'])) ? $SensorData['config']['battery'] : null);
                    $Sensor->setReachable((isset($SensorData['config']['reachable'])) ? $SensorData['config']['reachable'] : true);
                    $Sensor->setUniqueid($SensorData['uniqueid']);

                    $this->entity->persist($Sensor);
                    
                    if ($SensorData['type'] === 'ZHAPower' && $this->PowerLogEnabled) {
                        
                        $PowerLog = new PowerLog();
                        $PowerLog->setSensorId($Sensor);
                        $PowerLog->setCurrent($SensorData['state']['current']);
                        $PowerLog->setLastUpdate(new \DateTime($SensorData['state']['lastupdated']));
                        $PowerLog->setVoltage($SensorData['state']['voltage']);
                        $PowerLog->setPower($SensorData['state']['power']);
                        
                        $this->entity->persist($PowerLog);
                    }
                }
            }

            $this->entity->flush();
        }
    }

    public function checkAction($Sensor, $stateAttr, $mode, $value, $Device) {

        #$Sensors = $this->entity->getRepository(Sensors::class);
        #$Lights = $this->entity->getRepository(Lights::class);
        #$Device = $Lights->findOneBy(['id' => $switchId]);
        #$Sensor = $Sensors->findOneBy(['id' => $sensorId]);

        $_Value = false;

        switch ($Sensor->getType()) {
            case 'ZHATemperature':
                $_Value = $Sensor->getState()['temperature'];
                break;
            case 'ZHAHumidity':
                $_Value = $Sensor->getState()['humidity'];
                break;
            case 'ZHAPressure':
                $_Value = $Sensor->getState()['pressure'];
                break;
            case 'Daylight':
                $_Value = $Sensor->getState()[$stateAttr];
                break;
            case 'ZHAOpenClose':
                $_Value = $Sensor->getState()['open'];
                break;
            case 'VirtualSensor':
                $_Value = $Sensor->getState()[$stateAttr];
                break;
            default:
                $_Value = 'Undefined '. $Sensor->getType();
        }

        $_Return = null;

        switch (strtoupper($mode)) {
            case 'MAX':
                if ($value > $_Value)
                    $_Return = true;
                else
                    $_Return = false;
                break;
            case 'MIN':
                if ($value < $_Value)
                    $_Return = true;
                else
                    $_Return = false;
                break;
            case 'SMATCH':
                if (strtoupper($value) == strtoupper($_Value))
                    $_Return = true;
                else
                    $_Return = false;
                break;
            case 'BMATCH':
                if ($this->toBoolean($value) == $this->toBoolean($_Value))
                    $_Return = true;
                else
                    $_Return = false;
                break;
            default:

                break;
        }
        
        if ($_Return) {
            // ON
            if ($Device->getStateOn()) {
                // Nothing                
            } else {
                // Turn On...
                $this->Hue->setKey($Device->getBridge()->getIp(), $Device->getBridge()->getAccount());
                $this->Hue->switchOnLight($Device->getBridge()->getIp(), $Device->getInternalId());
            }
        } else {
            // OFF
            if ($Device->getStateOn()) {
                // Turn Off...          
                $this->Hue->setKey($Device->getBridge()->getIp(), $Device->getBridge()->getAccount());
                $this->Hue->switchOffLight($Device->getBridge()->getIp(), $Device->getInternalId());
            } else {
                // Nothing
            }
        }
    }
    
    public function checkVSensor($Sensor, $stateAttr, $mode, $value) {

        #$Sensors = $this->entity->getRepository(Sensors::class);
        #$Lights = $this->entity->getRepository(Lights::class);
        #$Device = $Lights->findOneBy(['id' => $switchId]);
        #$Sensor = $Sensors->findOneBy(['id' => $sensorId]);

        $_Value = false;
        
        switch ($Sensor->getType()) {
            case 'ZHATemperature':
                $_Value = $Sensor->getState()['temperature'];
                break;
            case 'ZHAHumidity':
                $_Value = $Sensor->getState()['humidity'];
                break;
            case 'ZHAPressure':
                $_Value = $Sensor->getState()['pressure'];
                break;
            case 'Daylight':
                $_Value = $Sensor->getState()[$stateAttr];
                break;
            case 'ZHAOpenClose':
                $_Value = $Sensor->getState()['open'];
                break;
            case 'VirtualSensor':
                $_Value = $Sensor->getState()[$stateAttr];
                break;
            default:
                $_Value = 'Undefined '. $Sensor->getType();
        }

        $_Return = null;

        switch (strtoupper($mode)) {
            case 'MAX':
                if ($value > $_Value)
                    $_Return = true;
                else
                    $_Return = false;
                break;
            case 'MIN':
                if ($value < $_Value)
                    $_Return = true;
                else
                    $_Return = false;
                break;
            case 'SMATCH':
                if (strtoupper($value) == strtoupper($_Value))
                    $_Return = true;
                else
                    $_Return = false;
                break;
            case 'BMATCH':
                if ($this->toBoolean($value) == $this->toBoolean($_Value))
                    $_Return = true;
                else
                    $_Return = false;
                break;
            default:

                break;
        }
        
        return $_Return;
        
    }
    
    public function switchActivity(Actions $Action) {
        
        $state = (!$Action->getActive());
        $Action->setActive($state);
        
        $this->entity->persist($Action);
        $this->entity->flush();
        
        return $state; 
    }
    
    private function toBoolean($int)
    {
        if ($int == 1) return true;
        return false;
    }

}
