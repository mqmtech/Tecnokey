<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Tecnokey\ShopBundle\Service;

use Tecnokey\ShopBundle\Entity\Shop\Product;
use MQMTech\ToolsBundle\Service\Utils;
use Tecnokey\ShopBundle\Service\PriceInfo;
/**
 * Description of PriceManager
 *
 * @author mqmtech
 */
class PriceManager {
    

    /**
     *
     * @param array <OrderItem|ShoppingCartItem>
     * @return PriceInfo
     */
    public function getItemCollectionPriceInfo(array $itemsCollection){
        if($itemsCollection == NULL){
            return NULL;
        }
        
        $priceInfo = new PriceInfo();
        $totalBasePrice = 0.0;
        foreach ($itemsCollection as $item) {
            $aTotalBasePrice = $item->getTotalBasePrice();
            
            $totalBasePrice += $aTotalBasePrice;
        }
        
        //TODO: Apply Offers to Shopping Cart ?
        $priceInfo->setBasePrice($totalBasePrice);
        
        return $priceInfo;
    }
    
    /**
     *
     * @param Product $product
     * @param type $quantity
     * @return PriceInfo 
     */
    public function getPriceInfo(Product $product, $quantity = 1){

        if($product == NULL){
            return NULL;
        }
        
        $offer = $product->getOffer();
        $isOffer = false;
        $basePrice = $product->getBasePrice();
        if($offer != NULL){ //Calculate and set new price
            
            $currentTime = Utils::getInstance()->convertDateTimeToTimeStamp(new \DateTime());
            $start = Utils::getInstance()->convertDateTimeToTimeStamp( $offer->getStart() );
            $deadline = Utils::getInstance()->convertDateTimeToTimeStamp( $offer->getDeadline() );
            $discount = $product->getOffer()->getDiscount();

            //look and set for an offer
            if($currentTime > $start && $currentTime <  $deadline && $discount != NULL && $discount > 0){
                $isOffer = true;
            }        
            //End look and set for an offer

            if($offer->getDiscount() != NULL && $offer->getDiscount() > 0){
                $basePrice = (float) $basePrice * ((float) 1.0 - $offer->getDiscount() / 100.0) ;
            }   
            
            //scale to quantity
            $basePrice *= $quantity;
        }       
        
        $priceInfo = new PriceInfo();
        $priceInfo->setBasePrice($basePrice);
        $priceInfo->addAppliedOffer($offer);
        return $priceInfo;
    }
}

?>
