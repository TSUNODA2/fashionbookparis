<?php

namespace App\Controller;


use App\Repository\ProductCategoryRepository;
use App\Repository\ProductMarketRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MarketController extends AbstractController
{
   /**
     * @Route("/market", name="market")
     */
   
    public function marketCategory( ProductCategoryRepository $productCategoryRepository,ProductMarketRepository $productMarketRepository ,$name = 'market' )
    {

        $category = $productCategoryRepository->findBy([],[],2);
        $product = $productMarketRepository->findBy([],[],2);
        return $this->render('/market/market.html.twig',[
            
            'category' => $category,
            'product' => $product
        
        ]);
      
      
    }


    public function Productrecent (ProductMarketRepository $productMarketRepository, $name = 'market')
    {

        $productRecent = $productMarketRepository->findBy([],['created_at' => 'ASC'],1);
        return $this->render('/market/market.html.twig',[
            'productRecent' => $productRecent
        ]);
    }

}
