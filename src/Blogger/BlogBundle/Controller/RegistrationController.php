<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Form\UserType;
use Blogger\BlogBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Description of RegistrationController
 *
 * @author Julio
 */

class RegistrationController extends Controller
{

public function registerAction(Request $request){
        
        $session = $request->getSession();
            
        $user = new User();
           
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {
            
            $username = $form->get('username')->getData();
            $email = $form->get('email')->getData();            

            //Cifra la contraseña
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($form->get('password')->getData(), $user->getSalt());

            //Seteamos los atributos
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword($password);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            //generar flasdata
            $session->getFlashBag()->add('info', '¡Registro realizado con éxito!');

            return $this->redirectToRoute('registro_form');
        }else{
            //generar flasdata
            $session->getFlashBag()->add('info', '¡Ocurrió un error al registrar!');            
        }
        return $this->render(
            'Blog/register.html.twig',
            array('form' => $form->createView())
        );
    }
}
