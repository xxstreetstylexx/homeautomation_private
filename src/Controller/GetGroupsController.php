<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ColorService;
use App\Service\DeviceService;
use App\Entity\LightGroups;
use App\Entity\Scenes;

class GetGroupsController extends AbstractController {

    private $entity;
    private $color;

    public function __construct(EntityManagerInterface $entityManager, ColorService $colorService, DeviceService $Device) {

        $this->entity = $entityManager;
        $this->color = $colorService;

        $Device->update();
    }

    /**
     * @Route("/api/get/groups", name="get_groups")
     */
    public function index(): Response {
        $GroupsRepository = $this->entity->getRepository(LightGroups::class);

        $Groups = $GroupsRepository->findAll();
        
        $GroupsOut = [];
        foreach ($Groups as $Group) {
            ##if ($Group->getName() == 'TRADFRI motion sensor ') continue;
            $Lights = [];
            $Automatic = [];
            $Reachable_All = true;
            $Unreachable = [];
            foreach ($Group->getLights() as $Light) {
                $Lights[] = $Light->getId();
                if ($Light->getReachable() === false) {
                    $Unreachable[] = $Light->getId();
                }                    
                $Action = $this->getActions($Light);
                if (count($Action) > 0) $Automatic[] = $Action;
            }
            
            if (count($Lights) == count($Unreachable)) {
                $Reachable_All = false;                
            }

            $GroupsOut[] = [
                'id' => $Group->getInternalId(),
                'state' => $Group->getStateAll(),
                'state_any' => $Group->getStateAny(),
                'name' => $Group->getName(),
                'bridge' => $Group->getBridge()->getId(),
                'reachable' => $Reachable_All,
                //'bridgeip' => $Group->getBridge()->getIp(),
                //'bridgepw' => $Group->getBridge()->getAccount(),
                'class' => $Group->getClass(),
                'lights' => $Lights,
                'type' => $Group->getType(),
                'automatic' => (count($Automatic) > 0) ? $Automatic : false
            ];
        }

        return $this->json($GroupsOut);
    }

    /**
     * @Route("/api/get/group/{bridgeId}/{groupId}", name="get_group")
     */
    public function getGroup($bridgeId, $groupId) {
        $GroupsRepository = $this->entity->getRepository(LightGroups::class);

        $Group = $GroupsRepository->findOneBy(['internalId' => $groupId, 'bridge' => $bridgeId]);

        $Automatic = [];
        $Unreachable = [];
        
        foreach ($Group->getLights() as $Light) {
            $Action = $this->getActions($Light);
            if (count($Action) > 0) $Automatic[] = ['target' => $Light->getName(), 'actions' => $Action];
            $Lights[] = [
                'id' => $Light->getId(),
                'name' => $Light->getName(),
                'type' => $Light->getType(),
                'hascolor' => $Light->getHascolor(),
                'stateon' => $Light->getStateOn(),
                'reachable' => $Light->getReachable()
            ];
            if ($Light->getReachable() === false) {
                $Unreachable[] = $Light->getId();
            }                    
        }
        $Reachable_All = true;
        if (count($Lights) == count($Unreachable)) {
            $Reachable_All = false;                
        }
        
        /*
         * Get unsorted scenes
         */
        $Scenes_Unsorted = [];
        
        foreach ($Group->getScenes() as $Scene) {
            $Scenes_Unsorted[$Scene->getName()] = [
                'id' => $Scene->getSceneId(),
                'name' => $Scene->getName()
            ];
        }
        
        /*
         * Sort scenes by Name
         */
        ksort($Scenes_Unsorted);
        $Scenes = [];
        
        /*
         * Prepare output
         */
        foreach ($Scenes_Unsorted as $Scene) {
            $Scenes[] = [
                'id' => $Scene['id'],
                'name' => $Scene['name']
            ];
        }

        $GroupsOut = [
            'id' => $Group->getInternalId(),
            'state' => $Group->getStateAll(),
            'state_any' => $Group->getStateAny(),
            'name' => $Group->getName(),
            'bridge' => $Group->getBridge()->getId(),
            'bridgename' => $Group->getBridge()->getName(),
            'reachable' => $Reachable_All,
            //'bridgeip' => $Group->getBridge()->getIp(),
            //'bridgepw' => $Group->getBridge()->getAccount(),
            'class' => $Group->getClass(),
            'lights' => $Lights,
            'type' => $Group->getType(),
            'scenes' => $Scenes,
            'automatic' => (count($Automatic) > 0) ? $Automatic : false
        ];

        return $this->json($GroupsOut);
    }
    
    private function getActions($Light) {
        $Automatic = [];
        if (count($Light->getActions()) > 0) {
            
            foreach ($Light->getActions() as $Action) {
                $Automatic[] = [
                    'id' => $Action->getId(),
                    'switch_target' => $Light->getName(),
                    'mode' => $Action->getMode(),
                    'value' => $Action->getValue(),
                    'operation' => strtoupper($Action->getOperation()),
                    'active' => $Action->getActive(),
                    'sensor_name' => $Action->getSensor()->getName(),
                    'sensor_type' => $Action->getSensor()->getType(),
                    'start' => $Action->getStartTime()->format('H:i'),
                    'end' => $Action->getEndTime()->format('H:i'),
                ];
            }
        }
        
        return $Automatic;
    }

}
