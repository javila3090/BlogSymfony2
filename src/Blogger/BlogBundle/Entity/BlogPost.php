<?php

namespace Blogger\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlogPost
 *
 * @ORM\Table(name="blog_post", indexes={@ORM\Index(name="created_by", columns={"created_by"}), @ORM\Index(name="created_by_2", columns={"created_by"})})
 * @ORM\Entity
 */
class BlogPost
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=true)
     */
    private $lastModified;

    /**
     * @var string
     *
     * @ORM\Column(name="key_words", type="string", length=200, nullable=true)
     */
    private $keyWords;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @var \Blogger\BlogBundle\Entity\Status
     *
     * @ORM\ManyToOne(targetEntity="Blogger\BlogBundle\Entity\Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status", referencedColumnName="id")
     * })
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Blogger\BlogBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Blogger\BlogBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * })
     */
    private $createdBy;
    
    
    /**
     * @var \Blogger\BlogBundle\Entity\BlogCategory
     *
     * @ORM\ManyToOne(targetEntity="Blogger\BlogBundle\Entity\BlogCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_category", referencedColumnName="id")
     * })
     */
    private $id_category;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
    }     

    public function getCategory()
    {
        return $this->id_category;
    }
    
    public function setCategory($id_category){
        $this->id_category = $id_category;
    }    
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title){
        $this->title = $title;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function setContent($content){
        $this->content = $content;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    public function setLastModified($lastModified){
        $this->lastModified = $lastModified;
    }   
    
    public function getlastModified()
    {
        return $this->lastModified;
    }
    
    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    } 
    
    public function getKeywords()
    {
        return $this->keyWords;
    }
    
    public function setKeywords($keyWords){
        $this->keyWords = $keyWords;
    } 
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description){
        $this->description = $description;
    }
    
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
    
    public function setCreatedBy($createdBy){
        $this->createdBy = $createdBy;
    }  
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function setStatus($status){
        $this->status = $status;
    }    
}