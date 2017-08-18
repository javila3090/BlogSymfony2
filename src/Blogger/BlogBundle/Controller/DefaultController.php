<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Blogger\BlogBundle\Form\PassType;
use Blogger\BlogBundle\Entity\User;

class DefaultController extends Controller
{
    
    public function resetPassAction(Request $request)
    {
        $session = $request->getSession();
        
        $user = new User();
        
        $form = $this->createForm(new PassType(), $user);
    
        //utilizamos el manejador de peticiones
        $form->handleRequest($request);
        
        //Si el formulario ha sido enviado
        if ($form->isSubmitted()) {
            $email = $form->get('email')->getData();
            //Check if Email exist 
            $emailExists = $this->getDoctrine()
                    ->getRepository('BloggerBlogBundle:User')
                    ->findOneBy(array('email' => $email));

            if(!$emailExists){

                //Mensaje flash
                $session->getFlashBag()->add('error', 'La dirección de correo ingresada no se encuentra registrada');

                return $this->redirect($this->generateURL('forget_password'));

            }else{
                //Enviar mail
                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('admin@symfony.com')
                    ->setTo($email)
                    ->setBody(
                        $this->renderView(
                            'Blog/Emails/resetPass.html.twig',
                            array('name' => $email)
                        ),
                        'text/html'
                    );

                $this->get('mailer')->send($message);

                //Mensaje flash
                $session->getFlashBag()->add('info', 'Se ha enviado un correo electrónico con las instrucciones para restablecer su contraseña');

                return $this->redirect($this->generateURL('forget_password'));

            }               
        }
                
        return $this->render(
            'Blog/resetPass.html.twig',
            array('form' => $form->createView())
        );
    }
}