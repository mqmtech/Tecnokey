<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tecnokey\ShopBundle\Service;

use Tecnokey\ShopBundle\Entity\Shop\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Tecnokey\ShopBundle\Entity\Shop\ShoppingCart;
use Tecnokey\ShopBundle\Entity\Shop\ShoppingCartItem;

use Tecnokey\ShopBundle\ViewModel\Shop\IProductPackCollection;
use Tecnokey\ShopBundle\ViewModel\Shop\IProductPack;
use Tecnokey\ShopBundle\ViewModel\Shop\ProductInfoDecorator;

use MQMTech\ToolsBundle\Service\IO\PropertiesReader;
use MQMTech\ToolsBundle\Service\Utils;

class ProductManager{
     
    private static $instance = NULL;    
    
    /**
     *
     * @return ProductManager
     */
    public static function getInstance(){
        if(self::$instance == NULL){
            self::$instance = new ProductManager();
        }
        return self::$instance;
    }
    
    /**
     *
     * @param Product $product
     * @param integer $quantity 
     */
    public function getPrice($product, $quantity = 1){
        $iva = MarketManager::getInstance()->getIva();
    }
    
    /**
     *
     * @param array <$products> $products
     */
    public function getProductsPriceInfo($products){
        if($products == NULL){
            return NULL;
        }
        
        $productsPriceInfo = array();
        foreach ($products as $product) {
            $ProductPriceInfo = $this->getProductPriceInfo($product);
            $productsPriceInfo[$product->getId()] =  $ProductPriceInfo;
        }
        
        return $productsPriceInfo;
    }
    
    /**
     *
     * @param Product $product
     */
    public function getProductPriceInfo(Product $product){

        if($product == NULL){
            return NULL;
        }
        
        $offer = $product->getOffer();
        $isOffer = false;
        $basePrice = $product->getBasePrice();
        if($offer != NULL){ //Calculate and set new price
            
            $currentTime = Utils::getInstance()->convertDateTimeToTimeStamp( new \DateTime() );
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
        }       
        
        $productInfoDecorator = new ProductInfoDecorator($product); //added by mqm 20:35 - 20/10/2011
        $productInfoDecorator->setBasePrice($basePrice);
        $productInfoDecorator->setIsOffer($isOffer);
        return $productInfoDecorator;
    }
}