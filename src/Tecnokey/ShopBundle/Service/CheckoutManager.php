<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Tecnokey\ShopBundle\Service;
/**
 * Description of OrderManager
 *
 * @author mqmtech
 */
use Tecnokey\ShopBundle\Entity\Shop\Order;
use Tecnokey\ShopBundle\Entity\Shop\OrderItem;
use Tecnokey\ShopBundle\Entity\Shop\ShoppingCart;

use Tecnokey\ShopBundle\Service\PriceManager;

class CheckoutManager {
    
    
    private $priceManager;
    private $marketManager;
    
    public function __construct(PriceManager $priceManager, $marketManager) {
        $this->priceManager = $priceManager;
        $this->marketManager = $marketManager;
    }
    
    /**
     * Fill the shopping cart with the current market values and product offers
     *
     * @param ShoppingCart $shoppingCart
     * @return ShoppingCart 
     */
    public function checkout(ShoppingCart $shoppingCart){
        if($shoppingCart == NULL){
            return NULL;
        }
        
        $priceManager = $this->getPriceManager();
        $marketManager = $this->getMarketManager();

        $shoppingCartItems = $shoppingCart->getItems();
        foreach ($shoppingCartItems as $item) {
            //Verify stock
            //stockManager->isStock($item->getProduct(), $item->getQuantity());
            //if no stock break and show error!

            //Calculate price per unit and total (and check for offers)
            $priceInfo = $priceManager->getPriceInfo($item->getProduct(), 1);
            $item->setBasePrice($priceInfo->getBasePrice());
            
            $priceInfo = $priceManager->getPriceInfo($item->getProduct(), $item->getQuantity());
            $item->setTotalBasePrice($priceInfo->getBasePrice());
        }
        
        //Calculate totalProductsBasePrice
        $priceInfo = $priceManager->getItemCollectionPriceInfo($shoppingCart->getItems()->toArray());
        $shoppingCart->setTotalProductsBasePrice($priceInfo->getBasePrice());
        
        //Calculate totalBasePrice (products + shipment)
        $totalBasePrice = $priceInfo->getBasePrice() ; // + shipment when it's needed
        $shoppingCart->setTotalBasePrice($totalBasePrice);
        
        //Get Tax
        $tax = $marketManager->getIva();
        $shoppingCart->setTax($tax);
        
        //Get Tax Price
        $taxPrice =  $tax * $totalBasePrice;
        $shoppingCart->setTaxPrice($taxPrice);
        
        //Calculate totalPrice
        $totalPrice = $totalBasePrice + $taxPrice;
        $shoppingCart->setTotalPrice($totalPrice);
        
        $shoppingCart->setModifiedAt(new \DateTime('now'));
        
        return $shoppingCart;
    }
    
    /**
     * Convert a shoppingCart into an Order
     *
     * @param ShoppingCart $shoppingCart
     * @return Order 
     */
    public function shoppingCartToOrder(ShoppingCart $shoppingCart){
        if($shoppingCart == NULL){
            return NULL;
        }
        
        $order = new Order();
        $shoppingCartItems = $shoppingCart->getItems();
        
        $marketManager = $this->getMarketManager();
        foreach ($shoppingCartItems as $item) {

            $orderItem = new OrderItem();
            
            $orderItem->setProduct($item->getProduct());
            $orderItem->setQuantity($item->getQuantity());
            $orderItem->setBasePrice($item->getBasePrice());
            $orderItem->setTotalBasePrice($item->getTotalBasePrice());
            
            //Add orderItem to Order
            $order->addItem($orderItem);
        }
        
        $order->setTotalProductsBasePrice($shoppingCart->getTotalProductsBasePrice());
        $order->setTotalBasePrice($shoppingCart->getTotalBasePrice());

        $order->setTax($shoppingCart->getTax());
        $order->setTaxPrice($shoppingCart->getTaxPrice());

        $order->setTotalPrice($shoppingCart->getTotalPrice());
        
        $order->setModifiedAt(new \DateTime('now'));
        
        return $order;
        
    }
    public function getPriceManager() {
        return $this->priceManager;
    }

    public function setPriceManager($priceManager) {
        $this->priceManager = $priceManager;
    }

    public function getMarketManager() {
        return $this->marketManager;
    }

    public function setMarketManager($marketManager) {
        $this->marketManager = $marketManager;
    }

}

?>
