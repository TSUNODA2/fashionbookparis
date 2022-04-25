<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScearchController extends AbstractController
{
    #[Route('/market/scearch', name: 'market_scearch')]
    public function index(): Response
    {
        return $this->render('scearch/market.html.twig', [
            'controller_name' => 'ScearchController',
        ]);
    }
}
