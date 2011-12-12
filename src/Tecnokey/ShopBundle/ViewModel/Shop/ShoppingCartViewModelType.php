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
class ShoppingCartViewModelType implements \MQMTech\ViewModelBundle\ViewModel\ViewModelType{
    
    private $shoppingCart;


    public function build($builder, $options) {
        
        $this->shoppingCart = $options['entity'];
        
        $builder->add('totalBasePrice', $this->getShoppingCartTotalBasePrice(), array(
            'read_only' => true
        ))
        ->add('iva', 18)
        ->add('ivaPrice', $this->getIvaPrice())
        ->add('totalPrice', $this->getShoppingCartTotalPrice())
        ;
    }
    
     /**
     *
     * @param ShoppingCart $shoppingCart
     * @return ShoppingCartViewModel 
     */
    public function getShoppingCartTotalBasePrice(){
        $totalBasePrice = 0.0;
        
        $items = $this->shoppingCart->getItems();
        if($items != NULL){
            foreach ($items as $item) {
                $totalBasePrice += $item->getBasePrice() * $item->getQuantity();
            }
        }
        return $totalBasePrice;
    }
    
    public function getShoppingCartTotalPrice(){
        $totalBasePrice = $this->getShoppingCartTotalBasePrice();
        return $totalBasePrice * 1.18;
    }
    
    public function getIvaPrice(){
        $totalBasePrice = $this->getShoppingCartTotalBasePrice();
        return $totalBasePrice * 0.18;
    }

    public function getDefaultOptions(array $options) {
        
    }
}

?>
