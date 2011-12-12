<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ViewModelBundle\Tests\ViewModel;
/**
 * Description of SampleModel
 *
 * @author mqmtech
 */
class SampleModel {
    
    private $name;
    
    public function __construct() {
        $this->name = "My Name";
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}

?>
