<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Blogger\BlogBundle\Form\UserType;
use Blogger\BlogBundle\Entity\User;


class SecurityController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();        
 
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        $qb->select(array("a.description, a.keyWords"))
           ->from('BloggerBlogBundle:BlogPost', 'a')
           ->innerJoin('BloggerBlogBundle:User','u','WITH', 'a.createdBy = u.id')
           ->innerJoin('BloggerBlogBundle:BlogCategory','c') 
           ->where('a.id_category = c.id'); 

        $query = $qb->getQuery();
        $posts = $query->getResult();
        
        return $this->render(
            'Blog/login.html.twig',
            array(                
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
                'posts'         => $posts,
            )
        );
    }    

    /**
     * @Route("/secure", name="secure")
     * @Security("is_granted('ROLE_USER')")
     */

    public function secureAction(){

        return $this->render('Blog/secure.html.twig');
    }  
    
    public function registerAction(Request $request)
    {
        
        $session = $request->getSession();
            
        $user = new User();
           
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
            
        if ($form->isSubmitted()) {
            
            $name = $form->get('name')->getData();
            $username = $form->get('username')->getData();
            $email = $form->get('email')->getData();            

            //Cifra la contraseña
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($form->get('password')->getData(), $user->getSalt());

            //Seteamos los atributos
            $user->setUsername($name);
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword($password);
        
            if ($form->isValid()) {
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    
                    //Enviar mail
                    $message = (new \Swift_Message('Welcome Email'))
                    ->setFrom('admin@symfony.com')
                    ->setTo($name)
                    ->setBody(
                        $this->renderView(
                            'Blog/Emails/welcome.html.twig',
                            array('name' => $name)
                        ),
                        'text/html'
                    );

                    $this->get('mailer')->send($message);
                    
                    //generar flasdata
                    $session->getFlashBag()->add('info', '¡Registro realizado con éxito!');

                    return $this->redirectToRoute('registro_form');

                } catch(\Exception $e) {
                    $errorMessage = $e->getMessage();
                    $session->getFlashBag()->add('error', '¡Aviso! Ocurrió un error al registrar');
                    return $this->redirect($this->generateUrl('registro_form'));
                }
            }
        }
        return $this->render(
            'Blog/registro.html.twig',
            array('form' => $form->createView())
        );
    }
}
