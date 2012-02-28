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
            ->add('email', 'repeated', array(
                'type' => 'text',
                'invalid_message' => 'El email debe coincidir',
                'options' => array('label' => 'Email')
            ))
            ->add('address')
            //->add('isEnabled')
            //->add('permissionType')
            //->add('createdAt')
            //->add('modifiedAt')
            //->add('username')
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'El password debe coincidir',
                'options' => array('label' => 'Password')
            ))
            ->add('firmName')
            ->add('vatin')
            ->add('zipCode')
            ->add('city')
            ->add('province')
            ->add('phone')
            ->add('fax')
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
