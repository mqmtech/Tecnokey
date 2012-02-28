<?php

namespace Tecnokey\ShopBundle\Form\Statistic;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProductStatisticType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('createdAt')
        ;
    }

    public function getName()
    {
        return 'tecnokey_shopbundle_statistic_productstatistictype';
    }
}
