<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Entity\Post;
use App\Entity\PostComment;
use App\Entity\PostLike;
use App\Form\PostCommentType;
use App\Repository\PostCommentRepository;
use App\Repository\PostLikeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private PostRepository $postRepo;

    private PostCommentRepository $commentRepo;

    private PostLikeRepository $likeRepo;

    private ObjectManager $entityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->postRepo = $doctrine->getRepository(Post::class);
        $this->commentRepo = $doctrine->getRepository(PostComment::class);
        $this->likeRepo = $doctrine->getRepository(PostLike::class);
        $this->entityManager = $doctrine->getManager();
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $posts = $this->postRepo->findBy(['visible' => true], ['createdAt' => 'DESC']);
        $comment = new PostComment();
        $commentForm = $this->createForm(PostCommentType::class, $comment);
        if ($this->getUser())
        {
            $user = $this->getUser();

            $options = [
                'posts' => $posts,
                'commentForm' => $commentForm
            ];
        }
        else
        {
            $options = [
                'posts' => $posts,
                'commentForm' => $commentForm
            ];
        }
        return $this->renderForm('home/index.html.twig', $options);
    }

    #[Route('/show/{post_id}', name: 'app_post_show')]
    #[ParamConverter('post', options: ['mapping' => ['post_id' => 'id']])]
    public function show(Post $post): Response
    {
        return $this->render('home/show.html.twig', [
            'post' => $post
        ]);
    }
    /**
     * @Route("/post/{id}/like",name="post_like",requirements={"id"="\d+"})
     *
     * @param Post $post
     * @return Response
     */
    public function like(Post $post): Response
    {
        $user = $this->getUser();
        if (!$user)
        {
            return $this->json(['code' => 403, 'message' => 'Unauthorized'], 403);
        }
        if ($post->isLikedByUser($user))
        {
            $like = $this->likeRepo->findOneBy(['post' => $post, 'user' => $user]);
            $this->entityManager->remove($like);
            $this->entityManager->flush();
            return $this->json([
                'code' => 200,
                'message' => 'Like supprimé',
                'likes' => $this->likeRepo->count(['post' => $post])
            ], 200);
        }
        $like = new PostLike();
        $like->setPost($post);
        $like->setUser($user);
        $this->entityManager->persist($like);
        $this->entityManager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'Like ajouté',
            'likes' => $this->likeRepo->count(['post' => $post])
        ], 200);
        //return $this->json(['code' => 200, 'message' => 'ça marche bien'], 200);
    }
}
