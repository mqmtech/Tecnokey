<?php

namespace Tecnokey\ShopBundle\Form\Shop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ShoppingCartItemType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            //->add('product')
            ->add('basePrice', 'text', array(
                'read_only' => true
            ))
            ->add('totalBasePrice', 'text', array(
                'read_only' => true
            ))
            ->add('quantity')            
        ;
    }

    public function getName()
    {
        return 'tecnokey_shopbundle_shop_shoppingcartitemtype';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Tecnokey\ShopBundle\Entity\Shop\ShoppingCartItem',
        );
    }
}
