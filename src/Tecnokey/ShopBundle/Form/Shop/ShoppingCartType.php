<?php

namespace Tecnokey\ShopBundle\Form\Shop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ShoppingCartType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            //->add('name')
            //->add('description')
            //->add('createdAt')
            //->add('modifiedAt')
            //->add('user')
            ->add('items', 'collection', array(
                'type' => new ShoppingCartItemType()
            ))
            ->add('totalBasePrice')
            ->add('iva', 'number', array(
                'read_only' => true
            ))
            ->add('ivaPrice')
            ->add('totalPrice')
        ;
    }

    public function getName()
    {
        return 'tecnokey_shopbundle_shop_shoppingcarttype';
    }
}
