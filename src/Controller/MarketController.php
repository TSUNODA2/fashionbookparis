<?php

namespace App\Controller;

use App\Repository\ProductMarketRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MarketController extends AbstractController
{
   /**
     * @Route("/market", name="market")
     */
   
    public function market(ProductMarketRepository $productMarketRepository, $name = 'market' )
    {

        $products = $productMarketRepository->findBy([],[], 4);
        return $this->render('/market/market.html.twig',[

            'products' => $products
        ]);
    }
}
