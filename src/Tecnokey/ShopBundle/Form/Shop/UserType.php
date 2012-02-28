<?php

namespace Tecnokey\ShopBundle\Form\Shop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('firstName', null, array(
                'required' => true,
            ))
            ->add('lastName', null, array(
                'required' => true,
            ))
            /*->add('email', 'repeated', array(
                'type' => 'text',
                'invalid_message' => 'El email debe coincidir',
                'options' => array('label' => 'Email')
            ))*/
            ->add('email', null, array(
                'required' => true,
            ))
            /*->add('emailConfirmation', null, array(
                'required' => true,
            ))*/
            ->add('address', null, array(
                'required' => true,
            ))
            //->add('isEnabled')
            //->add('permissionType')
            //->add('createdAt')
            //->add('modifiedAt')
            //->add('username')
            ->add('password', 'password', array(
                'required' => true,
            ))
            /*->add('passwordConfirmation', 'password', array(
                'required' => true,
            ))*/
            ->add('firmName', null, array(
                'required' => true,
            ))
            ->add('vatin', null, array(
                'required' => true,
            ))
            ->add('zipCode', null, array(
                'required' => true,
            ))
            ->add('city', null, array(
                'required' => true,
            ))
            ->add('province', null, array(
                'required' => true,
            ))
            ->add('phone', null, array(
                'required' => true,
            ))
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
