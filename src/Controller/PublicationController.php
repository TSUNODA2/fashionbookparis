<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicationController extends AbstractController
{

    private ObjectManager $entityManager;
    private PostRepository $postRepo;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
        $this->postRepo = new PostRepository($doctrine);
    }

    #[Route('/my-publications', name: 'app_my_publications')]
    public function index(): Response
    {
        $user = $this->getUser();
        $myPosts = $this->postRepo->findBy(['author' => $user]);
        return $this->render('publication/index.html.twig', [
            'controller_name' => 'PublicationController',
            'posts' => $myPosts
        ]);
    }

    #[Route('/new-publication', name: 'app_new_publication')]
    public function new(Request $request): Response
    {
        $post =  new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_my_publications');
        }
        return $this->renderForm('publication/new.html.twig', [
            'formPost' => $form
        ]);
    }
}
