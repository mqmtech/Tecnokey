<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tecnokey\ShopBundle\ViewModel\Shop;

use Tecnokey\ShopBundle\Entity\Shop\Product;
use Tecnokey\ShopBundle\ViewModel\Shop\IProductPack;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @author ciberxtrem
 */
interface IProductPackCollection {
    
    public function getId();

    public function getTotalBasePrice();
    
    public function getTotalPrice();
    
    /**
     * @return ArrayCollection <IProductPack>
     */
    public function getProductsPacks();
    
    public function getShippingBasePrice();
    
    public function getTotalProductsBasePrice();
        
    public function getTotalBasePrice();
    
    public function getTax();
    
    public function getTaxPrice();
    
    public function getTotalPrice();
}

?>
