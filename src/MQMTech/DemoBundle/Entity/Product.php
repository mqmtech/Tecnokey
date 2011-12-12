<?php

namespace MQMTech\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use MQMTech\DemoBundle\Entity\Image;

/**
 * MQMTech\DemoBundle\Entity\Product
 *
 * @ORM\Table(name="ProductDemoTable")
 * @ORM\Entity
 */
class Product
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
     *
     * @var Image $image
     * 
     * @ORM\ManyToOne(targetEntity="Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="imageId", referencedColumnName="id", nullable=true)
     */
    private $image;

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
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
}