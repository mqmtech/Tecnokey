<?php

namespace MQMTech\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MQMTech\DemoBundle\Entity\Image
 *
 * @ORM\Table(name="mqmtech_demo_image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Image
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
     * @var text $rawData
     *
     * @ORM\Column(name="rawData", type="text", nullable=true)
     */
    private $rawData;


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
     * Set rawData
     *
     * @param text $rawData
     */
    public function setRawData($rawData)
    {
        $this->rawData = $rawData;
    }

    /**
     * Get rawData
     *
     * @return text 
     */
    public function getRawData()
    {
        return $this->rawData;
    }
}