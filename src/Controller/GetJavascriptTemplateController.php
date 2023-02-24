<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetJavascriptTemplateController extends AbstractController
{
    /**
     * @Route("/js/{templateFile}", name="frontend")
     */
    public function index($templateFile): Response
    {
        return $this->render('javascript/' . $templateFile . '.html.twig', []);
    }
}
