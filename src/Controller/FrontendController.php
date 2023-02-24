<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
    /**
     * @Route("/frontend_a", name="frontend_a")
     */
    public function index(): Response
    {
        return $this->render('frontend/index.html.twig', [
            'controller_name' => 'FrontendController',
        ]);
    }

    /**
     * @Route("/", name="frontend_a")
     */
    public function start(): Response
    {
        return $this->redirectToRoute('frontend_b');
    }    
    /**
     * @Route("/frontend_b", name="frontend_b")
     */
    public function index_b(): Response
    {
        return $this->render('frontend_b/index.html.twig', [
            'controller_name' => 'FrontendController',
        ]);
    }
}
