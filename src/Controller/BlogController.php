<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use SebastianBergmann\Type\NullType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Security\Core\Security;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Articles::class);
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'title' => "Bienvenu ici les amis",
            'age' => 31
        ]);
    }
    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function create(Articles $article = null, Request $request, EntityManagerInterface $manager)
    {
            if(!$article) {
                $article= new Articles();
            }
        // $form = $this->createFormBuilder($article)
        //     ->add('title', TextType::class)
        //     ->add('content', TextareaType::class)
        //     ->add('image', TextType::class)
        //     ->getForm();
        
        $form=$this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId() 
            ]);
        }
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     * 
     */
    public function show($id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Articles::class);

        $article = $repo->find($id);

        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }
}