<?php

namespace Tecnokey\ShopBundle\Entity\Statistic;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;
/**
 * Tecnokey\ShopBundle\Entity\Statistic\Statistic
 *
 * @ORM\Table()
 */
class Statistic
{
    
    const PRODUCT_TYPE_SEEN = "product_seen";
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255, nullable = true)
     */
    protected $type;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable = true)
     */
    protected $createdAt;

    function __construct() {
        $this->createdAt = new DateTime();
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
    
    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
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
}