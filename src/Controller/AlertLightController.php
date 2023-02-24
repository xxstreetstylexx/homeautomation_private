<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Service\HueService;
use App\Entity\Lights;

class AlertLightController extends AbstractController
{
    private $hue;
    private $entity;
    private $allowedAlertType = [];
    public function __construct(HueService $Hue, EntityManagerInterface $entityManager) {
        $this->entity = $entityManager;
        $this->hue = $Hue;
        
        $this->allowedAlertType = [
            'Extended color light'
        ];
    }

    /**
     * @Route("/api/alert/light/{bridgeId}/{lightId}", name="alert_light")
     */
    public function index($lightId, $bridgeId): Response
    {
        
        $LightsRepository = $this->entity->getRepository(Lights::class);
        
        $Light = $LightsRepository->FindOneBy(['id' => $lightId, 'bridge' => $bridgeId]);
        
        if ($Light->getReachable() == false) {
            return $this->json(['not_reachable']);
        }
        
        if (in_array($Light->getType(), $this->allowedAlertType)) {
            $this->hue->setKey($Light->getBridge()->getIp(), $Light->getBridge()->getAccount());

            $this->hue->alertLight($Light->getBridge()->getIp(), $Light->getInternalId());

            return $this->json(['success']);
        }
        return $this->json(['not_allowed']);
        
    }
}
