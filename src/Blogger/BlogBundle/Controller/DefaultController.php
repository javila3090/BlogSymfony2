<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

class DefaultController extends Controller
{
    public function resetPassAction()
    {
        return $this->render('blog/resetPass.html.twig');
    }
    
    public function sendEmailAction(Request $request)
    {
        $session = $request->getSession();
        
        $email=$this->get('request')->request->get('email');
        
        //Check if Email exist 
        $emailExists = $this->getDoctrine()
                ->getRepository('BloggerBlogBundle:User')
                ->findOneBy(array('email' => $email));
        
        if(!$emailExists){
            
            //Mensaje flash
            $session->getFlashBag()->add('error', 'La dirección de correo ingresada no se encuentra registrada');

            return $this->render('blog/resetPass.html.twig');
            
        }else{
        
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('admin@symfony.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'blog/email.html.twig',
                        array('name' => $email)
                    ),
                    'text/html'
                );

            $this->get('mailer')->send($message);

            //Mensaje flash
            $session->getFlashBag()->add('info', 'Se ha enviado un correo electrónico con las instrucciones para restablecer su contraseña');

            return $this->render('blog/resetPass.html.twig');       
        }
    }
}
