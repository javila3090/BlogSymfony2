<?php

namespace Blogger\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlogPost
 *
 * @ORM\Table(name="blog_category")
 * @ORM\Entity
 */
class BlogCategory

{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

        public function setId($id){
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    } 
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    } 
}