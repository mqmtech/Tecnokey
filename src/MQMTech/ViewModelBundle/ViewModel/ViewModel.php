<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ViewModelBundle\ViewModel;

use Doctrine\Common\Util\Inflector;
/**
 * Description of ViewModel
 *
 * @author mqmtech
 */
class ViewModel {
    
    private $data;
    private $children;
    private $options;
    
    public function __construct($data, array $children, array $options = NULL) {
        $this->data = $data;
        $this->children = $children;
        $this->options = $options;
    }
    
    public function __get($name) {
        
        //get from facade (if possible)
        if(isset ($this->children[$name])){
            $value = $this->children[$name]['value'];
            if($value === NULL){
                return $this->getFromChildren($name);
            }
            return $value;
        }
        else{
            return $this->getFromChildren($name);
        }
    }
    
    private function getFromChildren($name){
        $method = 'get' . Inflector::classify($name);
        return call_user_func(array($this->data, $method));
    }
    
    public function __set($name, $value) {
        
        //set the facade param
        if(isset ($this->children[$name])){
            $this->children[$name]['value'] = $value;
        }
        
        $options = isset ($this->children[$name]['options']) ? $this->children[$name]['options'] : NULL;
        
        if($options!= NULL && !isset ($options['read_only']) || $options['read_only'] == FALSE){
            //...and set the data (if possible)
            $method = 'set' . Inflector::classify($name);
            if (method_exists($this->data, $method)) {
                call_user_func(array($this->data, $method), $value);
            }
        }
        
    }
    
    public function __call($method, $args)
    {
        $len = strlen($method);
        $attribute = substr($method, 3, $len - 2);
        $attribute = Inflector::camelize($attribute);
        
        
        if(isset($this->children[$attribute])){
            return $this->children[$attribute]['value'];
        }
        else{
           return call_user_func(array($this->data, $method), $args);
        }
    }
    
    public function printViewModel(){
        foreach ($this->children as $key => $value) {
            echo "key: $key, value: " . $value['value'];
        }
    }
}

?>
