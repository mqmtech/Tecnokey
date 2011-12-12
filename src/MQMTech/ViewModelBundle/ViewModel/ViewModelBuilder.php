<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ViewModelBundle\ViewModel;

use MQMTech\ViewModelBundle\ViewModel\ViewModelType;
/**
 * Description of ViewModelBuilder
 *
 * @author mqmtech
 */
class ViewModelBuilder {
    
    /**
     *
     * @var array
     */
    private $children = array();
    
    private $factory;
    
    private $appData;
    
    /**
     *
     * @param ViewModelFactory $factory 
     */
    public function __construct($factory) {
        $this->factory = $factory;
    }
    
    /**
     *
     * @param string $child
     * @param type $value
     * @return ViewModelBuilder 
     */
    public function add($child, $value = NULL, array $options = NULL){
        
        /*if ($child instanceof self) {
            $this->children[$child->getName()] = $child;

            return $this;
        }

        if (!is_string($child)) {
            throw new UnexpectedTypeException($child, 'string or Symfony\Component\Form\FormBuilder');
        }

        if (null !== $value && !is_string($value) && !$value instanceof ViewModelType) {
            throw new UnexpectedTypeException($type, 'string or MQMTech\ViewModelBundle\ViewModel\ViewModelType');
        }*/

        $this->children[$child] = array(
            'value' => $value,
            'options' => $options
        );
       
        return $this;
    }
    
    /**
     * Creates the children.
     *
     * @return array An array of Form
     */
    public function buildChildren($data = NULL, $options = NULL)
    {
        $children = array();

        $classes = array();
        
        foreach ($this->children as $name => $builder) {
            
            /*if(is_string($builder)){
                $classes[] = " $name is string";
            }
            
            if(is_array($builder)){
                $classes[] = " $name is array";
            }
            else{
                $classes[] = " $name is class:" . get_class($builder);
            }*/
            
            if ($builder instanceof ViewModelType) {
                $factory = $this->getFactory();
                    
                $childData = NULL;
                $method = 'get' . $name;
                if ($data != NULL && method_exists($data, $method)) {
                        $childData = call_user_func(array($data, $method));
                }
                
                //end setting data
                $builder = $factory->create($type, $childData, $options);
            }
            $children[$name] = $builder;
        }

        //$clas_str = print_r($classes, 1);
        //throw new \Exception ("Clases: " . $clas_str);

        return $children;
    }
    
    public function getAppData() {
        return $this->appData;
    }

    public function setAppData($appData) {
        $this->appData = $appData;
    }

        
    public function getFactory() {
        return $this->factory;
    }

    public function setFactory($factory) {
        $this->factory = $factory;
    }
        
    public function getChildren() {
        return $this->children;
    }

    public function setChildren($children) {
        $this->children = $children;
    }

}

?>
