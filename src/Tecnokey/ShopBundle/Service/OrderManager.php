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

class OrderManager {
    
    
    private $priceManager;
    private $marketManager;
    
    public function __construct(PriceManager $priceManager, $marketManager) {
        $this->priceManager = $priceManager;
        $this->marketManager = $marketManager;
    }
    
    public function checkout(ShoppingCart $shoppingCart){
        if($shoppingCart == NULL){
            return NULL;
        }
        
        $order = new Order();
        $shoppingCartItems = $shoppingCart->getItems();
        
        $marketManager = $this->getMarketManager();
        foreach ($shoppingCartItems as $item) {
            //Verify stock
            //stockManager->isStock($item->getProduct(), $item->getQuantity());
            //if no stock break and show error!
            
            //Set Product and  quantity values
            $orderItem = new OrderItem();
            $orderItem->setProduct($item->getProduct());
            $orderItem->setProduct($item->getQuantity());
            
            $priceManager = $this->getPriceManager();

            //Calculate price per unit and total (and check for offers)
            $priceInfo = $priceManager->getPriceInfo($item->getProduct(), 1);
            $orderItem->setBasePrice($marketManager->roundoffCurrency($priceInfo->getBasePrice()));
            
            $priceInfo = $priceManager->getPriceInfo($item->getProduct(), $item->getQuantity());
            $orderItem->setTotalBasePrice($marketManager->roundoffCurrency($priceInfo->getBasePrice()));
            
            //Add orderItem to Order
            $order->addItem($orderItem);
        }
        
        //Calculate totalProductsBasePrice
        $priceInfo = $priceManager->getItemCollectionPriceInfo($order->getItems()->toArray());
        $order->setTotalProductsBasePrice($marketManager->roundoffCurrency($priceInfo->getBasePrice()));
        
        //Calculate totalBasePrice (products + shipment)
        $totalBasePrice = $priceInfo->getBasePrice() ; // + shipment when it's needed
        $order->setTotalBasePrice($marketManager->roundoffCurrency($totalBasePrice));
        
        //Get Tax
        $tax = $marketManager->getIva();
        $order->setTax($marketManager->roundoffCurrency($tax));
        
        //Get Tax Price
        $taxPrice =  $tax * $totalBasePrice;
        $order->setTaxPrice($marketManager->roundoffCurrency($taxPrice));
        
        //Calculate totalPrice
        $totalPrice = $totalBasePrice + $taxPrice;
        $order->setTotalPrice($marketManager->roundoffCurrency($totalPrice));
        
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
