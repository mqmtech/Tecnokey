<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tecnokey\ShopBundle\Service;

use MQMTech\ToolsBundle\Service\IO\IReader;

class OrderManager{
    
    private $reader;
   
    public function __construct(IReader $reader) {
        $this->reader = $reader;
        $this->reader->setPath(__DIR__.'/../Property/order_properties.ini');
    }
    
    /**
     *
     * @return string
     */
    public function getStateDescription($state) {
        $reader = $this->getReader();
        return $reader->getProperty($state);        
    }

    /**
     *
     * @param string $location 
     */
    public function setStateDescription($state, $description) {
        $reader = $this->getReader();
        $reader->setProperty($state, $description);
    }
    
    public function getReader() {
        return $this->reader;
    }

    public function setReader($reader) {
        $this->reader = $reader;
    }


    
}