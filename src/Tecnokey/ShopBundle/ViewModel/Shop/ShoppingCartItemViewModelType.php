<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Tecnokey\ShopBundle\ViewModel\Shop;

use Tecnokey\ShopBundle\Entity\Shop\ShoppingCart;
/**
 * Description of ShoppingCartViewModel
 *
 * @author mqmtech
 */
class ShoppingCartItemViewModelType implements \MQMTech\ViewModelBundle\ViewModel\ViewModelType{
    

    public function build($builder, $options) {
        
        $builder->add('quantity')
        ;

    }
    
    public function getDefaultOptions(array $options) {
        
    }
}

?>
