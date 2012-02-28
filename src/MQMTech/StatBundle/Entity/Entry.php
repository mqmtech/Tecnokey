<?php

namespace MQMTech\StatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * MQMTech\StatBundle\Entity\Entry
 *
 * @ORM\Table(name="shop_stat_entry")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class Entry
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
     * @var string $targetType
     *
     * @ORM\Column(name="targetType", type="string", length=255, nullable=true)
     */
    private $targetType;

    /**
     * @var text $targetId
     *
     * @ORM\Column(name="targetId", type="integer", nullable=true)
     */
    private $targetId;
    
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
     * @ORM\ManyToOne(targetEntity="Statistic", inversedBy="entries", cascade={"persist"})
     * @ORM\JoinColumn(name="statisticId", referencedColumnName="id")
     *
     * @var Statistic $statistic
     */
    private $statistic;

//>>Start get/setters<<
    
    public function getStatistic() {
        return $this->statistic;
    }

    public function setStatistic($statistic) {
        $this->statistic = $statistic;
    }
     
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
     * Set targetType
     *
     * @param string $targetType
     */
    public function setTargetType($targetType)
    {
        $this->targetType = $targetType;
    }

    /**
     * Get targetType
     *
     * @return string 
     */
    public function getTargetType()
    {
        return $this->targetType;
    }

    /**
     * Set description
     *
     * @param text $targetId
     */
    public function setTargetId($targetId)
    {
        $this->targetId = $targetId;
    }

    /**
     * Get targetId
     *
     * @return int 
     */
    public function getTargetId()
    {
        return $this->targetId;
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