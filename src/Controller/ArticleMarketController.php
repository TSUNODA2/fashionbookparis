<?php

namespace App\Controller;

use App\Repository\ProductMarketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleMarketController extends AbstractController
{
    #[Route('/market/{product_slug}', name: 'marketArticle')]
    public function marketArticle($product_slug, ProductMarketRepository $ProductMarketRepository): Response
    {
        $category = $ProductMarketRepository->findOneBy([
            'product_slug' => $product_slug
        ]);

        if(!$category) {
           throw $this->createNotFoundException("L'article demander n'existe pas");
        }

        return $this->render('article_market/article_marker.html.twig', [
            'product_slug' => $product_slug,
            'category' => $category
        ]);
    }

    // /**
    //  * @Route("/market/article/[id]", name="marketArticle", requirements={"id"="\d+"})
    //  */
    // public function showArticle($id){
    //     $marketArticle = $this->getDoctrine()

    //     ->getRepository('ProductMarket')
    //     ->find($id);

    //     if(!$marketArticle){
    //         throw $this->createNotFoundException('aucun produit ne correspond');
    //     }

    //     return Response($marketArticle->getNom());
    // }
}
