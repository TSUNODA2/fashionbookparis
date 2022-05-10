<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Entity\Post;
use App\Entity\PostComment;
use App\Form\PostCommentType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private PostRepository $postRepo;

    private ObjectManager $entityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->postRepo = $doctrine->getRepository(Post::class);
        $this->entityManager = $doctrine->getManager();
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $posts = $this->postRepo->findBy(['visible' => true], ['createdAt' => 'DESC']);
        $comment = new PostComment();
        $commentForm = $this->createForm(PostCommentType::class, $comment);
        return $this->renderForm('home/index.html.twig', [
            'posts' => $posts,
            'commentForm' => $commentForm
        ]);
    }

    #[Route('/show/{post_id}', name: 'app_post_show')]
    #[ParamConverter('post', options: ['mapping' => ['post_id' => 'id']])]
    public function show(Post $post): Response
    {
        return $this->render('home/show.html.twig', [
            'post' => $post
        ]);
    }
}
