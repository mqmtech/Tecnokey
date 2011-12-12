<?php

namespace Tecnokey\ShopBundle\Form\Shop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class BrandType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'add_categoria',
            ))
            ->add('description', null, array(
                'label' => 'descripcion_form'
            ))
            ->add('image', new ImageType())
        ;
    }

    public function getName()
    {
        return 'tecnokey_shopbundle_shop_brandtype';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Tecnokey\ShopBundle\Entity\Shop\Brand',
        );
    }
}
