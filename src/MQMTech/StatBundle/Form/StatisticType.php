<?php

namespace MQMTech\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class StatisticType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('createdAt')
            ->add('modifiedAt')
        ;
    }

    public function getName()
    {
        return 'mqmtech_statbundle_statistictype';
    }
}
