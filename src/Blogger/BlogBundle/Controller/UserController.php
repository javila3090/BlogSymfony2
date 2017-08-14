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
use Blogger\BlogBundle\Form\UserEditType;

/**
 * Description of UserController
 *
 * @author Julio
 */
class UserController extends Controller {
    
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select(array('u.id, u.name, u.username, u.email, u.isActive'))
           ->from('BloggerBlogBundle:User', 'u')
           ->where('u.isActive = 1');

        $query = $qb->getQuery();
        $users = $query->getResult();
        
        return $this->render('blog/listaUsuarios.html.twig',array('users' => $users));
    }
    
    public function editAction(Request $request,$user){
        
        $session = $request->getSession();
        
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Repositorios de entidades a utilizar
        $userRepository=$em->getRepository("BloggerBlogBundle:User");
        
        //conseguimos el objeto del User
        $user = $userRepository->findOneBy(array("id"=>$user));
            
        //Creamos el formulario, asociado a la entidad
        $form = $this->createForm(new UserEditType(), $user);
    
        //utilizamos el manejador de peticiones
        $form->handleRequest($request);
        
        //Si el formulario ha sido enviado
        if ($form->isSubmitted()) {
                      
            
            //Obteniendo datos desde el formulario
            $name = $form->get('name')->getData();
            $username = $form->get('username')->getData();
            $email = $form->get('email')->getData();
            
            //Seteando los atributos
            $user->setName($name);
            $user->setUsername($username);
            $user->setEmail($email);
            
        }
        
        //Si el formulario es valido tras aplicar la validacion de la entidad
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
  
            //Mensaje flash
            $session->getFlashBag()->add('info', '¡Registro actualizado con éxito!');
            
            //Redirigir al listado
            return $this->redirect($this->generateURL('list_users'));
        }else{
            //Si el formulario está enviado
            if ($form->isSubmitted()) {
                
                //Mensaje flash
                $session->getFlashBag()->add('error', 'Rellena correctamente el formulario');
            }
        }
        
        //Renderizar vista
        return $this->render(
            'Blog/editarUsuario.html.twig',
            array('form' => $form->createView())
        );
    }
    
    public function deleteAction(Request $request, $user){
        
        $session = $request->getSession();
        
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Repositorios de entidades a utilizar
        $userRepository=$em->getRepository("BloggerBlogBundle:User");
        
        //conseguimos el objeto del User
        $user = $userRepository->findOneBy(array("id"=>$user));
        
        //Seteamos el registro a 0 en el campo is_active
        $user->setIs_Active(0);
        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //Mensaje flash
            $session->getFlashBag()->add('info', '¡Registro actualizado con éxito!');

            //Redirigir al listado
            return $this->redirect($this->generateURL('list_users'));
            
        } catch(\Exception $e) {
            
            $errorMessage = $e->getMessage();
            $session->getFlashBag()->add('error', $errorMessage);
            return $this->redirect($this->generateUrl('registro_blog'));
        }        

        
        //Renderizar vista
        return $this->render(
            'Blog/editarUsuario.html.twig',
            array('form' => $form->createView())
        );
    }     
}
