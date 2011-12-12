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
interface IOrderManager {
    
    /**
     * @return string
     */
    public function getMode();

    /**
     * @param string
     */
    public function setMode($mode);
    
    /**
     *
     * @return IOrder
     */
    public function getCurrentOrder();
    
    /**
     * @param IOrder
     */
    public function setCurrentOrder(IOrder $currentOrder);
    
    /**
     *
     * @return IOrderManager
     */
    public function switchMode();

    /**
     * @param IOrderManager
     */
    public function addOrder(IOrder $order);
    
    /**
     * @return array of IOrderManager
     */
    public function getOrders();
    
}

?>
