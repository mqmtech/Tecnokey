<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ToolsBundle\Service\IO;
/**
 *
 * @author mqmtech
 */
interface IReader {
    
    public function setPath($path);
    
    public function getProperty($property);
    
    public function setProperty($property, $value);
}

?>
