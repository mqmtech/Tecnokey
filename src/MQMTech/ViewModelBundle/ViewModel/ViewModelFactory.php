<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ViewModelBundle\ViewModel;

use MQMTech\ViewModelBundle\ViewModel\ViewModelType;
use MQMTech\ViewModelBundle\ViewModel\ViewModelBuilder;
use MQMTech\ViewModelBundle\ViewModel\ViewModel;
/**
 * Description of ViewModelFactory
 *
 * @author mqmtech
 */
class ViewModelFactory {
    
    /**
     *
     * @param ViewModelType $type
     * @param type $model
     * @param array $options
     * @return ViewModel 
     */
    public function create(ViewModelType $type, $data = NULL, $options = array()){
        $builder = new ViewModelBuilder($this);
        
        if(!isset($options['data'])){
            $options['data'] = $data;
        }
        
        $type->build($builder, $options);
        
        //$children = $builder->getChildren();
        $children = $builder->buildChildren($data);
        $viewModel = new ViewModel($data, $children, $options);
        
        return $viewModel;
    }
    
}

?>
