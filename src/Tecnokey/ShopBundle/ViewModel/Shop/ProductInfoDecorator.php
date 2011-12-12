<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tecnokey\ShopBundle\ViewModel\Shop;

use Tecnokey\ShopBundle\Entity\Shop\Product;
/**
 * ProductInfoDecorator is designed to add/modify information of a Product to show it to the end user
 * though the interface
 *
 * @author mqmtech
 */
class ProductInfoDecorator { //it should implement IProduct
    
    /**
     *
     * @var Product
     */
    private $product;
    
    /**
     *
     * @var float $basePrice
     */
    private $basePrice;
    
    /**
     *
     * @var bool $isOffer
     */
    private $isOffer;
    
    public function __construct(Product $product = NULL) {
        $this->setProduct($product);
    }
    
    public function __call($method, $args)
    {
        //echo __METHOD__.PHP_EOL;
        if(!method_exists($this, $method))
        {
            return $this->product->$method($args);
        }
    }
    
    /**
     *
     * @return Product
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     *
     * @param Product $product 
     */
    public function setProduct(Product $product) {
        $this->product = $product;
    }

    public function getBasePrice() {
        return $this->basePrice;
    }

    public function setBasePrice($basePrice) {
        $this->basePrice = $basePrice;
    }

    public function getIsOffer() {
        return $this->isOffer;
    }

    public function setIsOffer($isOffer) {
        $this->isOffer = $isOffer;
    }

}

?>
