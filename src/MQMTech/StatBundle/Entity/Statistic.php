<?php

namespace MQMTech\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * MQMTech\StatBundle\Entity\Statistic
 *
 * @ORM\Table(name="shop_statistic")
 * @ORM\Entity(repositoryClass="MQMTech\StatBundle\Repository\StatisticRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Statistic
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var datetime $modifiedAt
     *
     * @ORM\Column(name="modifiedAt", type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @ORM\OneToMany(targetEntity="Entry", mappedBy="statistic")
     * @var ArrayCollection $entries
     */
    private $entries;

//>>Start get/setters<<
     
    
    /**
    * Set products
    * @param ArrayCollection $entries
    */
    protected function setEntries(ArrayCollection $entries){
        $this->entries = $entries;
    }
    
    /**
     * Get products
     * @return ArrayCollection
     */
    public function getEntries(){
        return $this->entries;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param datetime $modifiedAt
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * Get modifiedAt
     *
     * @return datetime 
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }
    
//>>End get/setters<<
 
    

//>>Start functions<<
    
    public function __construct(){
        $this->createdAt = new \DateTime();
    }
    
    public function __toString(){
        return "".$this->getName();
    }
    
     /**
     * Invoked before the entity is updated.
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

//>>End functions<<
}