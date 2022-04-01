<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var PostRepository
     */
    private $postRepo;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->postRepo = $doctrine->getRepository(Post::class);
        $this->entityManager = $doctrine->getManager();
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $posts = $this->postRepo->findAll();
        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
