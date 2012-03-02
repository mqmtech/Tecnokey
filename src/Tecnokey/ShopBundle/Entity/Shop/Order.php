<?php

namespace Tecnokey\ShopBundle\Entity\Shop;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Tecnokey\ShopBundle\Entity\Shop\OrderItem;
use Tecnokey\ShopBundle\Entity\Shop\User;
use \DateTime;

/**
 * Tecnokey\ShopBundle\Entity\Shop\Order
 *
 * * It's just a CONTAINER (with NO logic) to store the order = copy the shoppingCart + added attributes related to the order itself
 * 
 * @author mqmtech
 * 
 * @ORM\Table(name="shop_order")
 * @ORM\Entity(repositoryClass="Tecnokey\ShopBundle\Repository\OrderRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Order {

    const STATUS_0_RECEIVED = "RECEIVED";                               //order saved in database
    const STATUS_1_IN_PROCESS = "IN_PROCESS";                           //order being processed by Tecnokey staff
    const STATUS_2_DELIVERED = "DELIVERED";                              //order delivered
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string $publicId
     *
     * @ORM\Column(name="publicId", type="string", length=255, nullable=true)
     */
    private $publicId;

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
     * @var datetime $checkoutAt
     *
     * @ORM\Column(name="checkoutAt", type="datetime", nullable=true)
     */
    private $checkoutAt;

    /**
     *
     * @var float $shippingBasePrice
     * 
     * @ORM\Column(name="shippingBasePrice", type="float", nullable=true)
     */
    private $shippingBasePrice;

    /**
     *
     * @var float $totalProductsBasePrice
     * 
     * @ORM\Column(name="totalProductsBasePrice", type="float", nullable=true)
     */
    private $totalProductsBasePrice;
    
    /**
     * Result of ProductsBasePrice + ShippingBasePrice
     * 
     * @var float $totalBasePrice
     * 
     * @ORM\Column(name="totalBasePrice", type="float", nullable=true)
     */
    private $totalBasePrice;
    
    /**
     * Per unitary tax that must be applied to the totalBasePrice
     *
     * @var float $tax
     * 
     * @ORM\Column(name="tax", type="float", nullable=true)
     */
    private $tax;
    
    /**
     * Result of totalBasePrice * tax
     * @var float $taxPrice 
     * 
     * @ORM\Column(name="taxPrice", type="float", nullable=true)
     */
    private $taxPrice;

    /**
     * Result of totalBasePrice + taxPrice
     * 
     * @var float $totalPrice
     * 
     * @ORM\Column(name="totalPrice", type="float", nullable=true)
     */
    private $totalPrice;

    /**
     *
     * @var string $shippingMethod
     * 
     * @ORM\Column(name="shippingMethod", type="string", length=255, nullable=true)
     */
    private $shippingMethod;
    
    /**
     *
     * @var string $orderState
     *  
     * @ORM\Column(name="orderState", type="string", nullable=true)
     */
    private $orderState;
    
    /**
     *
     * @var string
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    private $status;
    
   
    /**
     *
     * @var ArrayCollection $items of orderItems
     * 
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"persist", "remove"})
     * )
     */
    private $items;
    
    /**
     *
     * @var User $user;
     * 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders", cascade={"persist"}) 
     * @ORM\JoinColumn(name="userId", referencedColumnName="id", nullable=true)
     */
    private $user;
    
    function __construct() {
        $this->createdAt = new DateTime('now');
        
        $this->items = new ArrayCollection();
        
        $this->setStatus(self::STATUS_0_RECEIVED);

    }
    
    public function getPublicId() {
        return $this->publicId;
    }

    public function setPublicId($publicId) {
        $this->publicId = $publicId;
    }

    
        
    public function getItems() {
        return $this->items;
    }

    protected function setItems($items) {
        $this->items = $items;
    }
    
    /**
     *
     * @param OrderItem $item 
     */
    public function addItem(OrderItem $item){
        if($this->getItems() == NULL){
            $this->items = new ArrayCollection();
        }
        $this->items[] = $item;
        $item->setOrder($this); //Important to keep the right info in the database
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
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

    public function getCheckoutAt() {
        return $this->checkoutAt;
    }

    public function setCheckoutAt($checkoutAt) {
        $this->checkoutAt = $checkoutAt;
    }

    public function getShippingBasePrice() {
        return $this->shippingBasePrice;
    }

    public function setShippingBasePrice($shippingBasePrice) {
        $this->shippingBasePrice = $shippingBasePrice;
    }

    public function getTotalProductsBasePrice() {
        return $this->totalProductsBasePrice;
    }

    public function setTotalProductsBasePrice($totalProductsBasePrice) {
        $this->totalProductsBasePrice = $totalProductsBasePrice;
    }

    public function getTotalBasePrice() {
        return $this->totalBasePrice;
    }

    public function setTotalBasePrice($totalBasePrice) {
        $this->totalBasePrice = $totalBasePrice;
    }

    public function getTax() {
        return $this->tax;
    }

    public function setTax($tax) {
        $this->tax = $tax;
    }

    public function getTaxPrice() {
        return $this->taxPrice;
    }

    public function setTaxPrice($taxPrice) {
        $this->taxPrice = $taxPrice;
    }

    public function getTotalPrice() {
        return $this->totalPrice;
    }

    public function setTotalPrice($totalPrice) {
        $this->totalPrice = $totalPrice;
    }

    public function getShippingMethod() {
        return $this->shippingMethod;
    }

    public function setShippingMethod($shippingMethod) {
        $this->shippingMethod = $shippingMethod;
    }

    public function getOrderState() {
        return $this->orderState;
    }

    public function setOrderState($orderState) {
        $this->orderState = $orderState;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }


}

?>
