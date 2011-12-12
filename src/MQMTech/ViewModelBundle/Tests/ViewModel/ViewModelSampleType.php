<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ViewModelBundle\Tests\ViewModel;

/**
 * Description of ViewModelSampleType
 *
 * @author mqmtech
 */
class ViewModelSampleType implements \MQMTech\ViewModelBundle\ViewModel\ViewModelType{
    
    public function build($builder, $options) {
        $builder->add('description', 'sampleDescription');
    }

    public function getDefaultOptions(array $options) {
        
    }
}

?>
