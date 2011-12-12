<?php

namespace Tecnokey\ShopBundle\Entity\Shop;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;
/**
 * Tecnokey\ShopBundle\Entity\Shop\TimeOffer
 *
 * @ORM\Table(name="shop_time_offer")
 * @ORM\Entity
 */
class TimeOffer
{
    const SCOPE_LOCAL = "local";    // local sope is applied to entities (category, product)
    const SCOPE_GLOBAL = "global";  
    const DEF_SCOPE = self::SCOPE_LOCAL;
    
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
     * @var datetime $start
     *
     * @ORM\Column(name="start", type="datetime", nullable=true)
     */
    private $start;

    /**
     * @var datetime $deadline
     *
     * @ORM\Column(name="deadline", type="datetime", nullable=true)
     */
    private $deadline;
    
    /**
     *
     * @var float $discount
     * 
     * @ORM\Column(name="discount", type="float", nullable=true)
     */
    private $discount;

    /**
     * @var string $scope
     *
     * @ORM\Column(name="scope", type="string", length=255, nullable=true)
     */
    private $scope;


    public function __construct() {
        $this->setScope(self::DEF_SCOPE);
        $this->createdAt = new DateTime();
        
        $this->start = new DateTime('today');
        $this->deadline = new DateTime('today + 4 year');
    }
    
    public function __toString() {
        $content = "".$this->getName();
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
     * Set start
     *
     * @param datetime $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * Get start
     *
     * @return datetime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set deadline
     *
     * @param datetime $deadline
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     * Get deadline
     *
     * @return datetime 
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set scope
     *
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }
    
    /**
     *
     * @return float
     */
    public function getDiscount() {
        return $this->discount;
    }

    /**
     *
     * @param float $discount 
     */
    public function setDiscount($discount) {
        $this->discount = $discount;
    }
    
    /**
     * Get scope
     *
     * @return string 
     */
    public function getScope()
    {
        return $this->scope;
    }
}