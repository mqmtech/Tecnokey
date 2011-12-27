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
            ->add('tax', 'number', array(
                'read_only' => true
            ))
            ->add('taxPrice')
            ->add('totalPrice')
        ;
    }

    public function getName()
    {
        return 'tecnokey_shopbundle_shop_shoppingcarttype';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Tecnokey\ShopBundle\Entity\Shop\ShoppingCart',
        );
    }
}
