<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_USER')]
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
        $myPosts = $this->postRepo->findBy(['author' => $user], ['createdAt' => 'DESC']);
        return $this->render('publication/index.html.twig', [
            'controller_name' => 'PublicationController',
            'posts' => $myPosts
        ]);
    }

    #[Route('/new-publication', name: 'app_new_publication')]
    #[Route('/modify-publication/{post_id}', name: 'app_edit_publication')]
    #[ParamConverter('post', options: ['mapping' => ['post_id' => 'id']])]
    public function new(Request $request, Post $post = null): Response
    {
        if ($post == null)
        {
            $post =  new Post();
            $user = $this->getUser();
            $post->setAuthor($user);
        }
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

    #[Route('/delete-publication/{post_id}', name: 'app_delete_publication')]
    #[ParamConverter('post', options: ['mapping' => ['post_id' => 'id']])]
    public function delete(Post $post = null): Response
    {
        $this->postRepo->remove($post);
        return $this->redirectToRoute('app_my_publications');
    }
}
