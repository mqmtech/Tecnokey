<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ViewModelBundle\ViewModel;

use MQMTech\ViewModelBundle\ViewModel\ViewModelBuilder;
/**
 * Description of Viewmodel
 *
 * @author mqmtech
 */
interface ViewModelType{
    
    /**
     * @param ViewModelBuilder $builder
     */
    public function build($builder, $options);
    public function getDefaultOptions(array $options);
    
}

?>
