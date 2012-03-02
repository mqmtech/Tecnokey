<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MQMTech\ToolsBundle\Service\IO;

use Doctrine\Common\Collections\ArrayCollection;

class IniReader implements IReader{
       
    private $path = null;  
    private $properties = null;    
    
    public function __construct($path = null) {
        $this->path =  $path;
    }
    
    public function getProperties(){
        if($this->properties == NULL){
            $filepath = $this->path;
            $this->properties = parse_ini_file($filepath); 
        }
        return $this->properties;
    }
    
    public function setProperties(array $properties){
        $this->properties = $properties;
    }
    
    /**
     *
     * @param string $property
     * @return string|null
     */
    public function getProperty($property) {
        $properties = $this->getProperties();
        
        if($properties != NULL){
            if(isset ($properties[$property])){
                return $properties[$property];
            }
        }
        
        return null;
    }

    /**
     *
     * @param string $property
     * @param string $value 
     */
    public function setProperty($property, $value) {
        $properties = $this->getProperties();
        $properties[$property] = $value;
    }
    
    
    public function getPath() {
        return $this->path;
    }

    public function setPath($path) {
        $this->path = $path;
    }


    
}