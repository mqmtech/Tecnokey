<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ToolsBundle\Service\Ordering;
/**
 *
 * @author mqmtech
 */
interface IOrderFactory {
   
    /**
     * @return IPage
     */
    public function buildOrder();    
}

?>
