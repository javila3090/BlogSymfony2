<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Blogger\BlogBundle\Entity\BlogPost;
use Blogger\BlogBundle\Entity\BlogCategory;
use Blogger\BlogBundle\Entity\User;
use Blogger\BlogBundle\Form\BlogType;

class PostController extends Controller
{
    public function indexAction()
    {
        
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select(array("a.id, a.title, a.content, a.createdAt, a.description, SUBSTRING_INDEX(a.keyWords, ',', 1) keyWords, u.username, u.email, c.name as category"))
           ->from('BloggerBlogBundle:BlogPost', 'a')
           ->innerJoin('BloggerBlogBundle:User','u','WITH', 'a.createdBy = u.id')
           ->innerJoin('BloggerBlogBundle:BlogCategory','c') 
           ->where('a.id_category = c.id');

        $query = $qb->getQuery();
        $posts = $query->getResult();
        
        return $this->render('Blog/index.html.twig',array('posts' => $posts));
    }
    
    /**
     * @Route("/secure/post/list", name="list_posts")
     * @Security("is_granted('ROLE_USER')")
     */     
    
    public function listAction()
    {
        
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select(array('a.id, a.title, a.content, a.createdAt, a.lastModified,  a.description, a.keyWords, u.username, u.email'))
           ->from('BloggerBlogBundle:BlogPost', 'a')
           ->innerJoin('BloggerBlogBundle:User','u')
           ->where('a.createdBy = u.id');

        $query = $qb->getQuery();
        $posts = $query->getResult();
        
        return $this->render('Blog/listaEntrada.html.twig',array('posts' => $posts));
    }
    
    /**
     * @Route("/post/delete/{post}", name="delete_post")
     * @Security("is_granted('ROLE_USER')")
     */     
    
    public function deleteAction(Request $request, $post)
    {
        $session = $request->getSession();
        
        $em = $this->getDoctrine()->getManager();
        //Repositorios de entidades a utilizar
        $repository=$em->getRepository("BloggerBlogBundle:BlogPost");

        $post=$repository->findOneBy(array("id"=>$post));
        $em->remove($post);
        $em->flush();

        $session->getFlashBag()->add('info', '¡Registro eliminado con éxito!');

        return $this->redirectToRoute('list_posts');
    }    
    
    /**
     * @Route("/secure/nuevo/post", name="registro_Blog")
     * @Security("is_granted('ROLE_USER')")
     */    
    
    public function newPostAction(Request $request)
    {
        
        $session = $request->getSession();
            
        $post = new BlogPost();
           
        $form = $this->createForm(BlogType::class, $post);

        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {
            
            //Obteniendo el id del usuario logueado
            $logUser = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $logUser->getId();
            $user = $this->getDoctrine()
               ->getManager()
               ->getRepository('BloggerBlogBundle:User')
               ->findOneById($userId);
            $catId = $form->get('category')->getData();
            $category = $this->getDoctrine()
               ->getManager()
               ->getRepository('BloggerBlogBundle:BlogCategory')
               ->findOneById($catId);   
            
            $id_status = $form->get('status')->getData();
            $status= $this->getDoctrine()
               ->getManager()
               ->getRepository('BloggerBlogBundle:Status')
               ->findOneById($id_status);             
            
            //Obteniendo datos desde el formulario
            $title = $form->get('title')->getData();
            $keywords = $form->get('keyWords')->getData();
            $description = $form->get('description')->getData();
            $content = $form->get('content')->getData();
            setlocale(LC_TIME, 'es_VE');
            date_default_timezone_set('America/Caracas');
            $createdAt = new \DateTime('now');

            //Seteando los atributos
            $post->setTitle($title);
            $post->setKeywords($keywords);
            $post->setDescription($description);
            $post->setContent($content);
            $post->setCreatedAt($createdAt);
            $post->setCreatedBy($user);
            $post->setStatus($status);
            $post->setCategory($category);
        
            if ($form->isValid()) {
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($post);
                    $em->flush();

                    //generar flasdata
                    $session->getFlashBag()->add('info', '¡Registro realizado con éxito!');

                    return $this->redirectToRoute('registro_Blog');

                } catch(\Exception $e) {
                    $errorMessage = $e->getMessage();
                    $session->getFlashBag()->add('error', $errorMessage);
                    return $this->redirect($this->generateUrl('registro_Blog'));
                }
            }
        }
        return $this->render(
            'Blog/crearEntrada.html.twig',
            array('form' => $form->createView())
        );
    }
    
    /**
     * @Route("/secure/post/edit/{post}", name="edit_post")
     * @Security("is_granted('ROLE_USER')")
     */     

    public function editAction(Request $request,$post){
        
        $session = $request->getSession();
        
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Repositorios de entidades a utilizar
        $postRepository=$em->getRepository("BloggerBlogBundle:BlogPost");
        
        //conseguimos el objeto del Post
        $post = $postRepository->findOneBy(array("id"=>$post));
            
        //Creamos el formulario, asociado a la entidad
        $form = $this->createForm(new BlogType(), $post);
    
        //utilizamos el manejador de peticiones
        $form->handleRequest($request);
        
        //Si el formulario ha sido enviado
        if ($form->isSubmitted()) {
           
            //Metemos en variables los datos que llegan desde el formulario
            $catId = $form->get('category')->getData();
            $category = $this->getDoctrine()
               ->getManager()
               ->getRepository('BloggerBlogBundle:BlogCategory')
               ->findOneById($catId);            
            
            //Obteniendo datos desde el formulario
            $title = $form->get('title')->getData();
            $keywords = $form->get('keyWords')->getData();
            $description = $form->get('description')->getData();
            $content = $form->get('content')->getData();
            $status = $form->get('status')->getData();
            setlocale(LC_TIME, 'es_VE');
            date_default_timezone_set('America/Caracas');
            $lastModified = new \DateTime('now');
            
            //Seteando los atributos
            $post->setTitle($title);
            $post->setKeywords($keywords);
            $post->setDescription($description);
            $post->setContent($content);
            $post->setLastModified($lastModified);
            $post->setStatus($status);
            $post->setCategory($category);
            
        }
        
        //Si el formulario es valido tras aplicar la validacion de la entidad
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
  
            //Mensaje flash
            $session->getFlashBag()->add('info', '¡Registro actualizado con éxito!');
            
            //Redirigir a la home
            return $this->redirect($this->generateURL('list_posts'));
        }else{
            //Si el formulario está enviado
            if ($form->isSubmitted()) {
                
                //Mensaje flash
                $session->getFlashBag()->add('info', 'Rellena correctamente el formulario');
            }
        }
        
        //Renderizar vista
        return $this->render(
            'Blog/editarPost.html.twig',
            array('form' => $form->createView())
        );
    }
        
}
