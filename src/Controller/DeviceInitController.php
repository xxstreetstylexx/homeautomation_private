<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DeviceService;

class DeviceInitController extends AbstractController
{
    private $device;
    public function __construct(DeviceService $Device) {
        
        $this->device = $Device;
    }
    /**
     * @Route("/api/init", name="device_init")
     */
    public function index(): Response
    {
        return $this->json($this->device->get());
    }
}
