<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Service\SensorService;
use App\Entity\Actions;

class SwitchActionController extends AbstractController
{
    private $Sensor;
    private $entity;
    public function __construct(SensorService $Sensor, EntityManagerInterface $entityManager) {
        $this->entity = $entityManager;
        $this->Sensor = $Sensor;        
    }
    
    /**
     * @Route("/api/switch/action/{actionId}", name="switch_action")
     */
    public function index($actionId): Response
    {
        $ActionsRepository = $this->entity->getRepository(Actions::class);
        
        $Action = $ActionsRepository->FindOneBy(['id' => $actionId]);
        
        if ($Action !== null) {
            
            return $this->json(['success', 'id' => $Action->getId(), 'state' => $this->Sensor->switchActivity($Action)]);
        }
        return $this->json(['error', 'msg' => 'Action not found']);
        
    }
    
}
