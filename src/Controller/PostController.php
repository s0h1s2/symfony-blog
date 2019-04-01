<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\Post;
// Forms
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;





class PostController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(Request $request,PaginatorInterface $paginator)
    {
        $em=$this->getDoctrine()->getManager();
        $post=$em->getRepository(Post::class);
        $postQuery=$post->createQueryBuilder('p')->orderBy("p.id","DESC")->getQuery();
        /* @var $paginator \Knp\Component\Pager\Paginator */

        $posts=$paginator->paginate(
            $postQuery,
            $request->query->getInt('page',1),
            5

        );

        return $this->render("post/index.html.twig",["posts"=>$posts]);

    }

    /**
     * @Route("/post/show/{id}",name="app_show_post")
     */

    public function show ($id)
    {
        $post=$this->getPostContent($id);
        if ($post)
        {
            return $this->render("post/show.html.twig",["post"=>$post]);

        }
        else
        {
            return $this->redirectToRoute("app_404_error");


        }

    }
    public function getPostContent($id)
    {
        $post=$this->getDoctrine()->getRepository(Post::class)->find($id);

        if ($post)
        {
            return $post;

        }


    }

    /**
     * @Route("/post/save")
     */
    public function save(Request $request)
    {

        $entityManager=$this->getDoctrine()->getManager();
        $post=new Post();
        $form=$this->createFormBuilder($post)->
        add('title',TextType::class,["attr"=>["class"=>"form-control"]])->
        add('author',TextType::class,["attr"=>["class"=>"form-control"]])->
        add('body',TextareaType::class,["attr"=>["class"=>"form-control "]])->
        add('image',FileType::class,["attr"=>["class"=>"form-control-file"]])->
        add('save',SubmitType::class,['label'=>"Create new Post","attr"=>["class"=>"btn btn-primary mt-2"]])
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $post=$form->getData();
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash("success","Post Add Successfully");
            return $this->redirectToRoute("app_index");

        }
        return $this->render('post/add.html.twig',["form"=>$form->createView()]);




    }


}
