<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RadioIframeController extends AbstractController
{
    /**
     * @Route("/radio", name="radio")
     */
    public function index(): Response
    {
        return $this->render('radioiframe/index.html.twig');
    }
}
