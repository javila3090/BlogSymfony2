<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('admin@symfony.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'blog/email.html.twig',
                    array('name' => $email)
                ),
                'text/html'
            )
        /*
         * If you also want to include a plaintext version of the message
        ->addPart(
            $this->renderView(
                'Emails/registration.txt.twig',
                array('name' => $name)
            ),
            'text/plain'
        )
        */
        ;

        $this->get('mailer')->send($message);

        //Mensaje flash
        $session->getFlashBag()->add('info', 'Se ha enviado un correo electrónico con las instrucciones para restablecer su contraseña');

        return $this->render('blog/resetPass.html.twig');       
        
    }
}
