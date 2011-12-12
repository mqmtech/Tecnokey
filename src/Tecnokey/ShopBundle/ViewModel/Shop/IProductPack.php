<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tecnokey\ShopBundle\ViewModel\Shop;

use Tecnokey\ShopBundle\Entity\Shop\Product;

/**
 *
 * @author ciberxtrem
 */
interface IProductPack {
    
    public function getId();

    public function getBasePrice();
    
    public function getQuantity();
    
     /**
     * @return Product
     */
    public function getProduct();
    
    public function getTotalBasePrice();
}

?>
