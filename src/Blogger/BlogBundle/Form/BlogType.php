<?php

namespace Blogger\BlogBundle\Form;

use Blogger\BlogBundle\Entity\BlogPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Description of BlogType
 *
 * @author Julio
 */

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array("label" => "Titulo* ",
                    "required" => true,
                    "attr" => array('class' => 'form-control')))      
                
                ->add('keyWords', 'text', array("label" => "Palabras claves ",
                    "required" => false,
                    "attr" => array('class' => 'form-control')))                
                
                ->add('description', 'text', array("label" => "Descripción ",
                    "required" => false,
                    "attr" => array('class' => 'form-control')))
                
                ->add('content', TextareaType::class, array("label" => "Contenido* ",
                    "required" => true,
                    "attr" => array('class' => 'form-control','rowspan' => '3')))
                
                ->add('category', 'entity', array(
                    "label" => "Categoría* ",
                    "required" => true,
                    'class' => 'BloggerBlogBundle:BlogCategory',
                    "attr" => array('class' => 'form-control'),
                    "property"=>"name"
                ))                 
                
                ->add('status', 'entity', array(
                    "label" => "Estatus* ",
                    "required" => true,
                    'class' => 'BloggerBlogBundle:Status',
                    "attr" => array('class' => 'form-control'),
                    "property"=>"name"
                ));         
    }

    public function getName() {
        return 'Nueva_Entrada';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BlogPost::class,
        ));
    }
}