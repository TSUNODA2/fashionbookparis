<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Repository\ArticleFbRepository;
use App\Entity\Post;
use App\Entity\ArticleFb;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\SendType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class PublicationController extends AbstractController
{
    #[Route('/', name: 'app_publication')]
    public function index(): Response
    {
        return $this->render('publication/index2.html.twig', [
            'controller_name' => 'PublicationController',
        ]);
    }

    #[Route('/about', name: 'aboutpage')]
    public function about(): Response
    {

        return $this->render('publication/about.html.twig');
    }

    #[Route('/photo', name: 'photo')]
    public function photo(): Response
    {
        return $this->render('publication/photo.html.twig');
    }

    #[Route('/user', name: 'profilpage')]
    public function homepage():Response
    {
        return $this->render('publication/profil.html.twig');
    }


    #[Route('/publication', name:'publication')]
    public function insert(ArticleFbRepository $articleFbRepo, Request $requestfb, EntityManagerInterface $entman, SluggerInterface $slugger): Response
    {

        // $articleFbs = $articleFbRepo->findBy([], ['createdAt' => 'DESC']);    

        $articleFb = new ArticleFb();

        $form = $this->createFormBuilder($articleFb)
                     ->add('contentfbp', null, array('label' => false))
                     ->add('photo', FileType::class, [
                        'label' => false,
                        'multiple' => true,
                        'mapped' => false,
                        'required' => false
                    ])
                     ->getForm();

        // $form = $this->createForm($articleFb);
        $form->handleRequest($requestfb);

       if($form->isSubmitted() && $form->isValid()) {
           $articleFb->setCreatedAt(new \DateTime());

           $articleFb->setUser($this->getUser());

           /***@var UploadedFile $photo */
           $photoFile = $form->get('photo')->getData();

           foreach($photoFile as $photoFiles){

            $originalFilename = pathinfo($photoFiles->getClientOriginalName(), PATHINFO_FILENAME);

            $safeFilename = $slugger->slug($originalFilename);

            $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFiles->guessExtension();

            {

                $photoFiles->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

            

            $articleFb->setPhoto($newFilename);
           }


           $entman->persist($articleFb); 
           $entman->flush();


           return $this->redirectToRoute('publication');        
       }

    //    , [
    //     'id' => $articleFb->getId()
    //  ]

        return $this->render('publication/publication.html.twig', [
            // 'articleFbs' => $articleFbs,
            'formArticleFb' => $form->createView()
        ]);
    }
}



    #[Route('/publication/{id}', name:'publication_send')]
    public function send(ArticleFb $articleFb, Request $request)
    {
        $formSend = $this->createForm(SendType::class);

        $contact = $formSend->handleRequest($request);

        return $this->render('publication/publication.html.twig', [
            'articleFb' => $articleFb, 
            'formSend' => $formSend->createView()
        ]);
    }


    #[Route('/create/{id}', name:'create')]
    public function envoyer(ArticleFb $articleFb, Request $request, MailerInterface $mailer)
    {
        $formSend = $this->createForm(SendType::class);

        $sender = $formSend->handleRequest($request);
        $contact = $formSend->handleRequest($request);

        if($formSend->isSubmitted() && $formSend->isValid()){
            $email = (new TemplatedEmail())
            ->from($sender->get('emailSend')->getData())
            ->to($contact->get('email')->getData())
            ->subject('Cette annonce pourrait vous plaire')
            ->htmlTemplate('emails/contact_annonce.html.twig')
            ->context([
                'articleFb'=>$articleFb,
                'e_mail'=>$contact->get('email')->getData(),
                'message'=>$contact->get('message')->getData()
            ]);

            $mailer->send($email);

            $this->addFlash('message', 'Votre e-mail à bien été envoyé');

            return $this->redirectToRoute('create', ['id'=>$articleFb->getId()]);
        }

        return $this->render('publication/create-fb.html.twig', [
            'articleFb' => $articleFb, 
            'formSend' => $formSend->createView() 
        ]);
    }
    private ObjectManager $entityManager;
    private PostRepository $postRepo;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
        $this->postRepo = new PostRepository($doctrine);
    }

    #[Route('/my-publications', name: 'app_my_publications')]
    public function index1(): Response
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

// use App\Repository\PostRepository;
// use Doctrine\Persistence\ManagerRegistry;
// use Doctrine\Persistence\ObjectManager;
// use App\Entity\Post;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;


// #[IsGranted('ROLE_USER')]
// class PublicationController extends AbstractController
// {

    
// }
