<?php

namespace Blogger\BlogBundle\Form;

use Blogger\BlogBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array("label" => "Nombre: ",
                    "required" => true,
                    "attr" => array('class' => 'form-control')))
                
                ->add('username', 'text', array("label" => "Usuario: ",
                    "required" => true,
                    "attr" => array('class' => 'form-control')))
                
                ->add('email', 'email', array("label" => "Correo electronico: ",
                    "required" => true,
                    "attr" => array('class' => 'form-control')))
                
                ->add('password', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'Las contraseñas deben coincidir',
                    'required' => true,
                    'first_options' => array('label' => 'Contraseña: ',"attr" => array('class' => 'form-control')),
                    'second_options' => array('label' => 'Repetir contraseña: ',"attr" => array('class' => 'form-control'))));
    }

    public function getName() {
        return 'Registro';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}