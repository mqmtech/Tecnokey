<?php

namespace Tecnokey\ShopBundle\Form\Shop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('createdAt')
            ->add('modifiedAt')
            ->add('checkoutAt')
            ->add('shippingBasePrice')
            ->add('totalProductsBasePrice')
            ->add('totalBasePrice')
            ->add('tax')
            ->add('taxPrice')
            ->add('totalPrice')
            ->add('shippingMethod')
            ->add('orderState')
            ->add('status')
            ->add('user')
        ;
    }

    public function getName()
    {
        return 'tecnokey_shopbundle_shop_ordertype';
    }
}
