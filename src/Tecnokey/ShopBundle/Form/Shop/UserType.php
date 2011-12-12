<?php

namespace Tecnokey\ShopBundle\Form\Shop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('address')
            ->add('isEnabled')
            ->add('permissionType')
            ->add('createdAt')
            ->add('modifiedAt')
            ->add('username')
            ->add('password')
        ;
    }

    public function getName()
    {
        return 'tecnokey_shopbundle_shop_usertype';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Tecnokey\ShopBundle\Entity\Shop\User',
        );
    }
}
