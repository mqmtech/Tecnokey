<?php

namespace MQMTech\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EntryType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('targetType')
            ->add('targetId')
            ->add('createdAt')
            ->add('modifiedAt')
            ->add('statistic')
        ;
    }

    public function getName()
    {
        return 'mqmtech_statbundle_entrytype';
    }
}
