<?php

namespace Tecnokey\ShopBundle\Entity\Shop;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Tecnokey\ShopBundle\Entity\Shop\User;
use Tecnokey\ShopBundle\Entity\Shop\ShoppingCartItem;

/**
 * Tecnokey\ShopBundle\Entity\Shop\Order
 *
 * @author mqmtech
 * 
 * @ORM\Table(name="shop_shopping_cart")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class ShoppingCart {

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
     *
     * @var ArrayCollection $items of shoppingCartItem
     * 
     * @ORM\OneToMany(targetEntity="ShoppingCartItem", mappedBy="shoppingCart", cascade={"persist", "remove"})
     * )
     */
    private $items;
    
    /**
     *
     * @var User $user
     * 
     * @ORM\OneToOne(targetEntity="User",  mappedBy="shoppingCart", cascade={"persist"})
     */
    private $user;
    
    
    function __construct() {
        $this->createdAt = new \DateTime('now');
        
        $this->items = new ArrayCollection();
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function getModifiedAt() {
        return $this->modifiedAt;
    }

    public function setModifiedAt($modifiedAt) {
        $this->modifiedAt = $modifiedAt;
    }

    public function getItems() {
        return $this->items;
    }

    protected function setItems($items) {
        $this->items = $items;
    }
    
    /**
     *
     * @param ShoppingCartItem $item 
     */
    public function addItem(ShoppingCartItem $item){
        if($this->getItems() == NULL){
            $this->items = new ArrayCollection();
        }
        $this->items[] = $item;
        $item->setShoppingCart($this); //Important to keep the right info in the database
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }
}

?>
