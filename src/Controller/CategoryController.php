<?php

namespace App\Controller;

use App\Repository\ProductCategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

     /**
     *@Route("/market/{category_slug}", name="product_category")
     */

    public function category($slug = 'slug_category', ProductCategoryRepository $productCategoryRepository, $name ='product_category')
    {
        $category = $productCategoryRepository->findOneBy([],[],1);
        return $this->render('/market/category.html.twig',[
            'category' => $category
        ]);
    }
}