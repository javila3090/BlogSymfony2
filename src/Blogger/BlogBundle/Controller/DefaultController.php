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
        
        //Mensaje flash
        $session->getFlashBag()->add('info', 'Se ha enviado un correo electrónico con las instrucciones para restablecer su contraseña');

        return $this->render('blog/resetPass.html.twig');
    }
}
