<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Service\HueService;
use App\Entity\Lights;
use App\Entity\LightGroups;

class SwitchLightController extends AbstractController
{
    private $hue;
    private $entity;
    public function __construct(HueService $Hue, EntityManagerInterface $entityManager) {
        $this->entity = $entityManager;
        $this->hue = $Hue;
        
    }
    
    /**
     * @Route("/api/switch/light/{bridgeId}/{lightId}", name="switch_light")
     */
    public function index($lightId, $bridgeId): Response
    {
        $LightsRepository = $this->entity->getRepository(Lights::class);
        
        $Light = $LightsRepository->FindOneBy(['id' => $lightId, 'bridge' => $bridgeId]);
        
        $this->hue->setKey($Light->getBridge()->getIp(), $Light->getBridge()->getAccount());
        
        $api = $this->hue->SwitchLight($Light->getBridge()->getIp(), $Light->getInternalId());
        
        return $this->json(['success', 'light' => $lightId, 'bridge' => $bridgeId, 'api' => $api]);
        
    }
    
    /**
     * @Route("/api/switch/group/{bridgeId}/{groupId}", name="switch_group")
     */
    public function group($groupId, $bridgeId): Response
    {        
        $GroupRepository = $this->entity->getRepository(LightGroups::class);
        
        $Group = $GroupRepository->FindOneBy(['internalId' => $groupId, 'bridge' => $bridgeId]);
        
        $this->hue->setKey($Group->getBridge()->getIp(), $Group->getBridge()->getAccount());
        
        $msg = $this->hue->SwitchGroup($Group->getBridge()->getIp(), $Group->getInternalId());
        
        return $this->json(['success', 'msg' => $msg]);        
    }
    
        
    /**
     * @Route("/api/scene/group/{sceneId}/{bridgeId}/{groupId}", name="switch_scene")
     */
    public function scene($sceneId, $groupId, $bridgeId): Response
    {        
        $GroupRepository = $this->entity->getRepository(LightGroups::class);
        
        $Group = $GroupRepository->FindOneBy(['internalId' => $groupId, 'bridge' => $bridgeId]);
        
        $this->hue->setKey($Group->getBridge()->getIp(), $Group->getBridge()->getAccount());
        
        $msg = $this->hue->recallScene($Group->getBridge()->getIp(), $Group->getInternalId(), $sceneId);        
        
        return $this->json(['success', 'msg' => $msg]);        
    }
}
