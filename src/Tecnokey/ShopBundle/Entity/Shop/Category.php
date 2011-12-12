<?php

namespace Tecnokey\ShopBundle\Entity\Shop;

use Tecnokey\ShopBundle\Entity\Shop\Image;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tecnokey\ShopBundle\Entity\Shop\Category
 *
 * @ORM\Table(name="shop_category")
 * @ORM\Entity(repositoryClass="Tecnokey\ShopBundle\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Category
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
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="categories", cascade={"persist"})
     * @ORM\JoinColumn(name="parentCategoryId", referencedColumnName="id")
     * 
     * @var Category $parentCategory
     */
    private $parentCategory;
    
    /**
     * //added cascade persist on 02/12/2011 as another way to save the parentCategory when only changing the array of categories directly but it's not tested that this way works (without setting the parentCategory manually)
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parentCategory", cascade={"persist"}) 
     * @var ArrayCollection $categories
     */
    private $categories;
    
    /**
     * 
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     * @var ArrayCollection $products
     */
    private $products;
    
    public function __construct(){
        $this->createdAt = new \DateTime();
    }
    
    /**
     * @return the $products
     */
    public function getProducts() {
            return $this->products;
    }

    /**
     * @param ArrayCollection $products
     */
    public function setProducts($products) {
            $this->products = $products;
    }

    /**
     * @return Category $parentCategory
     */
    public function getParentCategory() {
            return $this->parentCategory;
    }

    /**
     *
     * @param Category $category 
     */
    protected function addCategory(Category $category){
        if($this->categories == null){
            $this->categories = new ArrayCollection();
        }
        $this->categories [] = $category;
        
        $category->setParentCategory($this); //IMPORTANT FOR THE SQL MAPPING AS THE PARENT CATEGORY IS WHAT KEEP THE HIERARCHY/DEPENDENCY INFO IN THE DATABASE BETWEEN CATEGORIES
    }

    /**
     * @return ArrayCollection $categories
     */
    public function getCategories() {
            return $this->categories;
    }

    /**
     * @param ArrayCollection $categories
     */
    protected function setCategories($categories) {
            $this->categories = $categories;
    }

    /**
     * @param Category $parentCategory
     */
    public function setParentCategory($parentCategory) {
            $this->parentCategory = $parentCategory;
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
    
    /**
     * 
     * Get image
     * 
     * @return Image
     */
    public function getImage(){
    	return $this->image;
    }
    
    /**
     * 
     * Set image
     * 
     * @param Image $image
     */
    public function setImage($image){
        $this->image = $image;
    }


    public function getRootCategory(){
        $parentCategory = $this->getParentCategory();
        if($parentCategory== NULL){
            return $this;
        }
        else{
            return $parentCategory->getRootCategory();
        }
    }

    public function getHierarchyDepth(){
        $parentCategory = $this->getParentCategory();
        if($parentCategory== NULL){
            return 1;
        }
        
        else{
            return 1 + $parentCategory->getHierarchyDepth();
        }
    }
    
    public function __toString() {
       $depth = $this->getHierarchyDepth();
       
       $contentStart = "";
       $contentEnd = "";
       $name = $this->getName();
       
       if($depth == 1){
           $contentStart = "";
           $contentEnd = "";
           $name = strtoupper($name);
       }
       else{
           
           //capitalize first character
           $name = strtolower($name);
           $name = ucfirst($name);
           //end capitalizing first character

           $depth = 2 * ($depth -1);
           for ($index = 0; $index < $depth; $index++) {
               $contentStart .='-';
           }
       }
       return $contentStart . "  " . $name . "  " . $contentEnd;
    }
    
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

    /**
     * Invoked before the entity is updated.
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

}