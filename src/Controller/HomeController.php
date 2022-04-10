<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepage(PostRepository $postRepository)
    {

        $post = $postRepository->findBy([], [], 5);

        return $this->render('home/home.html.twig', [
            'post' => $post,
        ]);
    }
}
