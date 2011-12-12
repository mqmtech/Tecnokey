<?php

namespace Tecnokey\ShopBundle\Entity\Shop;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Tecnokey\ShopBundle\Entity\Shop\Order;
use \DateTime;

/**
 * Tecnokey\ShopBundle\Entity\Shop\OrderItem
 *
 * @author mqmtech
 * 
 * @ORM\Table(name="shop_order_item")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class OrderItem{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * Price per unit
     * 
     * @var float $basePrice
     *
     * @ORM\Column(name="basePrice", type="float", nullable=true)
     */
    private $basePrice;

    /**
     *
     * @var integer $quantity
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;
    
    /**
     *
     * @var float $totalBasePrice
     * @ORM\Column(name="totalBasePrice", type="float", nullable=true)
     */
    private $totalBasePrice;
    
    /**
     *
     * @var Product $product 
     * 
     * @ORM\ManyToOne(targetEntity="Product", cascade={"persist"})
     * @ORM\JoinColumn(name="productId", referencedColumnName="id", nullable=true)
     * 
     */
    private $product;
    
    /**
     *
     * @var Order $order
     * 
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="items", cascade={"persist"})
     * @ORM\JoinColumn(name="orderId", referencedColumnName="id", nullable=true)
     */
    private $order;
    
    function __construct() {
        $this->createdAt = new DateTime('now');                
    }

    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
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

    public function getBasePrice() {
        return $this->basePrice;
    }

    public function setBasePrice($basePrice) {
        $this->basePrice = $basePrice;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function getTotalBasePrice() {
        return $this->totalBasePrice;
    }

    public function setTotalBasePrice($totalBasePrice) {
        $this->totalBasePrice = $totalBasePrice;
    }

    public function getProduct() {
        return $this->product;
    }

    public function setProduct($product) {
        $this->product = $product;
    }

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
    }


}

?>
