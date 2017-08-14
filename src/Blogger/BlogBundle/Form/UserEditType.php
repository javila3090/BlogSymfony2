<?php

namespace Blogger\BlogBundle\Form;

use Blogger\BlogBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array("label" => "Nombre* ",
                    "required" => true,
                    "attr" => array('class' => 'form-control')))
                
                ->add('username', 'text', array("label" => "Usuario* ",
                    "required" => true,
                    "attr" => array('class' => 'form-control')))
                
                ->add('email', 'email', array("label" => "Correo electronico* ",
                    "required" => true,
                    "attr" => array('class' => 'form-control')));
                
                
    }

    public function getName() {
        return 'Editar';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}