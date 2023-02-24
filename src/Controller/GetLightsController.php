<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ColorService;
use App\Service\DeviceService;
use App\Entity\Lights;

class GetLightsController extends AbstractController {

    private $entity;
    private $color;

    public function __construct(EntityManagerInterface $entityManager, ColorService $colorService, DeviceService $Device) {

        $this->entity = $entityManager;
        $this->color = $colorService;

        $Device->update();
    }

    /**
     * @Route("/api/get/lights", name="get_lights")
     */
    public function index(): Response {
        $LightsRepository = $this->entity->getRepository(Lights::class);

        $Lights = $LightsRepository->findAll();

        $LightOut = [];

        foreach ($Lights as $Light) {

            $XY = $Light->getStateXY();
            $Br = $Light->getStateBri();
            if ($Light->getHascolor()) {
                $color = $this->color->RGBFromXYBri($XY[0], $XY[1], $Br);

                $color = $this->color->fromRGB($color['r'], $color['g'], $color['b']);
            } else {
                $color = false;
            }
            
            $Automatic = false;
            if (count($Light->getActions()) > 0) {
                
                foreach ($Light->getActions() as $Action) {
                    $Automatic[] = [
                        'id' => $Action->getId(),
                        'mode' => $Action->getMode(),
                        'value' => $Action->getValue(),
                        'operation' => strtoupper($Action->getOperation()),
                        'active' => $Action->getActive(),
                        'sensor_name' => $Action->getSensor()->getName(),
                        'sensor_type' => $Action->getSensor()->getType() 
                    ];
                }
            }

            $LightOut[] = [
                'id' => $Light->getId(),
                'state' => $Light->getStateOn(),
                'name' => $Light->getName(),
                'bridge' => $Light->getBridge()->getId(),
                'uniqueid' => $Light->getUniqueid(),
                'color' => $color,
                'reachable' => $Light->getReachable(),
                'type' => $Light->getType(),
                'automatic' => $Automatic
            ];
        };

        return $this->json($LightOut);
    }

}
