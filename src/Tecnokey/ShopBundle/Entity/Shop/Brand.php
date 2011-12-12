<?php

namespace Tecnokey\ShopBundle\Entity\Shop;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tecnokey\ShopBundle\Entity\Shop\Brand
 *
 * @ORM\Table(name="shop_brand")
 * @ORM\Entity(repositoryClass="Tecnokey\ShopBundle\Repository\BrandRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Brand
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
     * @Assert\Type(type="Tecnokey\ShopBundle\Entity\Shop\Image")
     *
     *
     * @ORM\ManyToOne(targetEntity="Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="imageId", referencedColumnName="id", nullable=true)
     *
     * @var Image $image
     */
    private $image;
    
    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="brand")
     * @var ArrayCollection $products
     */
    private $products;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="TimeOffer", cascade={"persist", "remove", "detached"})
     * @ORM\JoinColumn(name="offerId", referencedColumnName="id", nullable=true)
     *
     * @var TimeOffer $offer
     */
    private $offer;

//>>Start get/setters<<
     
    public function getOffer() {
        return $this->offer;
    }

    public function setOffer($offer) {
        $this->offer = $offer;
    }

        
    /**
    * Set products
    * @param ArrayCollection $products
    */
    protected function setProducts(ArrayCollection $products){
        $this->products = $products;
    }
    
    /**
     * Get products
     * @return ArrayCollection
     */
    public function getProducts(){
        return $this->products;
    }
    
    
    /**
     * Set image
     * @param Image $image
     */
    public function setImage(Image $image){
        $this->image = $image;
    }
    
    /**
     * Get image
     * @return Image
     */
    public function getImage(){
        return $this->image;
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
    
    function __clone(){
        // If the entity has an identity, proceed as normal.
        if ($this->id) {
            
        }
        // otherwise do nothing, do NOT throw an exception!
        
        //reset images
        if($this->image){
            $this->image = NULL;
        }
        //end reseting the images
    }

    
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