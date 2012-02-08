<?php

namespace Tecnokey\ShopBundle\Entity\Shop;

use Doctrine\Common\Collections\ArrayCollection;
use Tecnokey\ShopBundle\Entity\Shop\Category;
use Tecnokey\ShopBundle\Entity\Shop\Image;
use Tecnokey\ShopBundle\Entity\Shop\Brand;
use Tecnokey\ShopBundle\Entity\Shop\TimeOffer;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use \DateTime;

/**
 * Tecnokey\ShopBundle\Entity\Shop\Product
 *
 * @ORM\Table(name="shop_product")
 * @ORM\Entity(repositoryClass="Tecnokey\ShopBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Product{

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
     * @var boolean $isFeatured
     *
     * @ORM\Column(name="isFeatured", type="boolean", nullable=true)
     */
    private $isFeatured;

    /**
     * @var boolean $isEnabled
     *
     * @ORM\Column(name="isEnabled", type="boolean", nullable=true)
     */
    private $isEnabled;

    /**
     * @var float $stock
     *
     * @ORM\Column(name="stock", type="float", nullable=true)
     */
    private $stock;

    /**
     * @var float $weight
     *
     * @ORM\Column(name="weight", type="float", nullable=true)
     */
    private $weight;

    /**
     * @var string $sku
     *
     * @ORM\Column(name="sku", type="string", length=255, nullable=true)
     */
    private $sku;

    /**
     * @var float $basePrice
     *
     * @ORM\Column(name="basePrice", type="float", nullable=true)
     */
    private $basePrice;
    
    
    /**
     * 
     * @var float $discount
     * @ORM\Column(name="discount", type="float", nullable=true)
     */
    private $discount;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="TimeOffer", cascade={"persist", "remove", "detached"})
     * @ORM\JoinColumn(name="offerId", referencedColumnName="id", nullable=true)
     *
     * @var TimeOffer $offer
     */
    private $offer;
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(name="categoryId", referencedColumnName="id", nullable=true)
     * 
     * @var Category $category
     */
    private $category;
    
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
     * @ORM\ManyToOne(targetEntity="Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="secondImageId", referencedColumnName="id", nullable=true)
     *
     * @var Image $secondImage
     */
    private $secondImage;

    /**
     * @ORM\ManyToOne(targetEntity="Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="thirdImageId", referencedColumnName="id", nullable=true)
     *
     * @var Image $thirdImage
     */
    private $thirdImage;

    /**
     * @ORM\ManyToOne(targetEntity="Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="fourthImageId", referencedColumnName="id", nullable=true)
     *
     * @var Image $fourthImage
     */
    private $fourthImage;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(name="brandId", referencedColumnName="id")
     *
     * @var Brand $brand
     */
    private $brand;
    
     /**
     * @var string $tag
     *
     * @ORM\Column(name="tag", type="string", length=255, nullable=true)
     */
    private $tag;
    
    /**
     * @var string $secondTag
     *
     * @ORM\Column(name="secondTag", type="string", length=255, nullable=true)
     */
    private $secondTag;
    
    /**
     * @var string $thirdTag
     *
     * @ORM\Column(name="thirdTag", type="string", length=255, nullable=true)
     */
    private $thirdTag;
    
    /**
     * @var string $fourthTag
     *
     * @ORM\Column(name="fourthTag", type="string", length=255, nullable=true)
     */
    private $fourthTag;
    
        /**
     * Constructor
     */
    public function __construct() {
        $this->createdAt = new DateTime();
        
        $this->offer = new TimeOffer(); //To initializate dates, otherwise dates are old in the form
    }


    public function __toString() {
       return "" . $this->getName();
    }

    /**
     * Invoked before the entity is updated.
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->modifiedAt = new \DateTime();
    }
    
    function __clone(){
        
        // If the entity has an identity, proceed as normal.
        if ($this->id) {
            
        }
        // otherwise do nothing, do NOT throw an exception!
        
        //reset images
        $image = $this->image;
        if($image){
            $this->image = clone ($image);
        }
        
        //reset images
        $image = $this->secondImage;
        if($image){
            $this->secondImage = clone ($image);
        }
        
        //reset images
        $image = $this->thirdImage;
        if($image){
            $this->thirdImage = clone ($image);
        }
        
        //reset images
        $image = $this->fourthImage;
        if($image){
            $this->fourthImage = clone ($image);
        }
        //end reseting the images
        
        //reset offert
        if($this->offer != NULL){
            $this->offer = clone ($this->offer);
        }
        
    }
    
    /*public function serialize() {
        
        return implode(',', array(
            //'id' => $this->getId(),
            'name' => $this->getName(),
            'basePrice' => $this->getBasePrice(),
            ));
    }

    public function unserialize($serialized) {
        $serialized = explode(',', $serialized);

        //$this->setId($serialized[0]);    
        $this->setName($serialized[0]);    
        //$this->setCreatedAt($serialized[1]); 
        $this->setBasePrice($serialized[1]);
    }*/
    
    /**
     *
     * @return TimeOffer 
     */
    public function getOffer() {
        return $this->offer;
    }

    /**
     *
     * @param TimeOffer $offer 
     */
    public function setOffer(TimeOffer $offer) {
        $this->offer = $offer;
    }

    
    /**
     * @return float $discount
     */
    public function getDiscount() {
        return $this->discount;
    }

    /**
     * discount parameter between 0 and 100 %
     * 
     * @param float $discount
     */
    public function setDiscount($discount) {
        $discount = (float) $discount;
        $this->discount = $discount;
    }

    /**
     * @return $Brand
     */
    public function getBrand() {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand($brand) {
        $this->brand = $brand;
    }

    /**
     * @return Image $image
     */
    public function getimage() {
        return $this->image;
    }

    /**
     * @param Image $image
     */
    public function setimage($image) {
        $this->image = $image;
    }
    
      /**
     * @return Image $secondImage
     */
    public function getSecondImage() {
        return $this->secondImage;
    }

    /**
     * @param Image $image
     */
    public function setSecondImage($image) {
        $this->secondImage = $image;
    }
    
      /**
     * @return Image $thirdImage
     */
    public function getThirdImage() {
        return $this->thirdImage;
    }

    /**
     * @param Image $image
     */
    public function setThirdImage($image) {
        $this->thirdImage= $image;
    }
      /**
     * @return Image $fourthImage
     */
    public function getFourthImage() {
        return $this->fourthImage;
    }

    /**
     * @param Image $image
     */
    public function setFourthImage($image) {
        $this->fourthImage= $image;
    }
    
    
    /**
     * @return Category $category
     */
    public function getcategory() {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setcategory($category) {
        $this->category= $category;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }
    
     /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param datetime $modifiedAt
     */
    public function setModifiedAt($modifiedAt) {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * Get modifiedAt
     *
     * @return datetime 
     */
    public function getModifiedAt() {
        return $this->modifiedAt;
    }

    /**
     * Set isFeatured
     *
     * @param boolean $isFeatured
     */
    public function setIsFeatured($isFeatured) {
        $this->isFeatured = $isFeatured;
    }

    /**
     * Get isFeatured
     *
     * @return boolean 
     */
    public function getIsFeatured() {
        return $this->isFeatured;
    }

    /**
     * Set isEnabled
     *
     * @param boolean $isEnabled
     */
    public function setIsEnabled($isEnabled) {
        $this->isEnabled = $isEnabled;
    }

    /**
     * Get isEnabled
     *
     * @return boolean 
     */
    public function getIsEnabled() {
        return $this->isEnabled;
    }

    /**
     * Set stock
     *
     * @param float $stock
     */
    public function setStock($stock) {
        $this->stock = $stock;
    }

    /**
     * Get stock
     *
     * @return float 
     */
    public function getStock() {
        return $this->stock;
    }

    /**
     * Set weight
     *
     * @param float $weight
     */
    public function setWeight($weight) {
        $this->weight = $weight;
    }

    /**
     * Get weight
     *
     * @return float 
     */
    public function getWeight() {
        return $this->weight;
    }

    /**
     * Set sku
     *
     * @param string $sku
     */
    public function setSku($sku) {
        $this->sku = $sku;
    }

    /**
     * Get sku
     *
     * @return string 
     */
    public function getSku() {
        return $this->sku;
    }

    /**
     * Set basePrice
     *
     * @param float $basePrice
     */
    public function setBasePrice($basePrice) {
        $this->basePrice = $basePrice;
    }

    /**
     * Get basePrice
     *
     * @return float 
     */
    public function getBasePrice() {
        return $this->basePrice;
    }
       
    /**
     *
     * @return string
     */
    public function getTag() {
        return $this->tag;
    }

    /**
     *
     * @param string $tag 
     */
    public function setTag($tag) {
        $this->tag = strtolower($tag);
    }

    /**
     *
     * @return string
     */
    public function getSecondTag() {
        return $this->secondTag;
    }

    /**
     *
     * @param string $secondTag 
     */
    public function setSecondTag($secondTag) {
        $this->secondTag = strtolower($secondTag);
    }

    /**
     *
     * @return string
     */
    public function getThirdTag() {
        return $this->thirdTag;
    }

    /**
     *
     * @param string $thirdTag 
     */
    public function setThirdTag($thirdTag) {
        $this->thirdTag = strtolower($thirdTag);
    }

    /**
     *
     * @return string 
     */
    public function getFourthTag() {
        return $this->fourthTag;
    }

    /**
     *
     * @param string $fourthTag 
     */
    public function setFourthTag($fourthTag) {
        $this->fourthTag = strtolower($fourthTag);
    }
}
