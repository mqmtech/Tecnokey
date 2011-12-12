<?php

namespace Tecnokey\ShopBundle\Form\Shop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Tecnokey\ShopBundle\Form\Shop\CustomFileType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            //->add('name')
            //->add('description')
            //->add('createdAt')
            //->add('modifiedAt')
            //->add('size')
            //->add('path')
            ->add('file', null, array(
                'label' => ' ',
                'data' => ' ',
            ))
        ;
    }

    public function getName()
    {
        return 'tecnokey_shopbundle_shop_imagetype';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Tecnokey\ShopBundle\Entity\Shop\Image',
        );
    }
}
