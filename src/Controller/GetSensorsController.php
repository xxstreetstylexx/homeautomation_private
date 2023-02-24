<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Sensors;

class GetSensorsController extends AbstractController {

    private $entity;

    public function __construct(EntityManagerInterface $entityManager) {

        $this->entity = $entityManager;
    }

    /**
     * @Route("/api/get/sensors/{name}", name="get_sensors")
     */
    public function index($name): Response {

        $Sensors = $this->entity->getRepository(Sensors::class);

        ##$Sensor = $Sensors->findBy(['name' => $name]);
        $Sensor = $Sensors->createQueryBuilder('o')
                ->where('o.name LIKE :name')
                ->setParameter('name', $name.'%')
                ->getQuery()
                ->getResult();

        if ($Sensor !== null && count($Sensor) > 0) {
            $Build = [];
            $i = 0;
            foreach ($Sensor as $Sen) {
                $Build[$i] = [];
                $Build[$i]['name'] = $Sen->getName();
                $Build[$i]['battery'] = ($Sen->getBattery() !== null) ? $Sen->getBattery() : 100;
                $Build[$i]['type'] = $Sen->getType();
                $Build[$i]['reachable'] = $Sen->getReachable();
                $Build[$i]['virtual'] = $Sen->getVirtual();
                $Build[$i]['state'] = $Sen->getState();
                /*if ($Sen->getReachable()) {
                    switch ($Sen->getType()) {
                        case 'ZHATemperature':
                            $Build[$i]['temp'] = $Sen->getState()['temperature'] / 100;
                            break;
                        case 'ZHAHumidity':
                            $Build[$i]['humidity'] = $Sen->getState()['humidity'] / 100;
                            break;
                        case 'ZHAPressure':
                            $Build[$i]['pressure'] = $Sen->getState()['pressure'];
                            break;
                        case 'ZHAPresence':
                            $Build[$i]['ZHAPresence'] = $Sen->getState()['presence'];
                            $Build[$i]['ZHAlastupdate'] = $Sen->getState()['lastupdated'];
                            break;
                        case 'ZHAOpenClose':
                            $Build[$i]['ZHAOpenClose'] = $Sen->getState()['open'];
                            break;
                        default:

                    }
                }
                */
                $i++;
            }
            
            return $this->json(['name' => $name, 'sensors' => $Build]);
        }

        return $this->json(['name' => $name, 'sensors' => false]);
    }
    
    /**
     * @Route("/api/get/power", name="get_power")
     */
    public function power(): Response {

        $Sensors = $this->entity->getRepository(Sensors::class);

        ##$Sensor = $Sensors->findBy(['name' => $name]);
        $Sensor = $Sensors->createQueryBuilder('o')
                ->where('o.type = :type')
                ->setParameter('type', 'ZHAPower')
                ->getQuery()
                ->getResult();

        if ($Sensor !== null && count($Sensor) > 0) {
            $Build = [];
            $i = 0;
            foreach ($Sensor as $Sen) {
                $Build[$i] = [];
                $Build[$i]['name'] = $Sen->getName();
                $Build[$i]['id'] = $Sen->getId();
                $Build[$i]['type'] = $Sen->getType();
                $Build[$i]['reachable'] = $Sen->getReachable();
                $Build[$i]['virtual'] = $Sen->getVirtual();
                $Build[$i]['state'] = $Sen->getState();
                /*if ($Sen->getReachable()) {
                    switch ($Sen->getType()) {
                        case 'ZHATemperature':
                            $Build[$i]['temp'] = $Sen->getState()['temperature'] / 100;
                            break;
                        case 'ZHAHumidity':
                            $Build[$i]['humidity'] = $Sen->getState()['humidity'] / 100;
                            break;
                        case 'ZHAPressure':
                            $Build[$i]['pressure'] = $Sen->getState()['pressure'];
                            break;
                        case 'ZHAPresence':
                            $Build[$i]['ZHAPresence'] = $Sen->getState()['presence'];
                            $Build[$i]['ZHAlastupdate'] = $Sen->getState()['lastupdated'];
                            break;
                        case 'ZHAOpenClose':
                            $Build[$i]['ZHAOpenClose'] = $Sen->getState()['open'];
                            break;
                        default:

                    }
                }
                */
                $i++;
            }
            
            return $this->json(['sensors' => $Build]);
        }

        return $this->json(['sensors' => false]);
    }

}
