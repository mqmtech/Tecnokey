<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ToolsBundle\Service\Ordering;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MQMTech\ToolsBundle\Service\Utils;

/**
 * Description of WebOrderManager
 *
 * @author ciberxtrem
 */
class WebOrderManager{

    private $responsePath;
    
    private $responseParameters;
    
    /**
     *
     * @var WebOrder
     */
    private $currentOrder;
    
    /**
     *
     * @var array
     */
    private $orders;
    
    /**
     *
     * @var Request
     */
    private $request;

    public static function buildDefaultOrderManager(Request $request, WebOrderFactory $orderFactory, string $responsePath=NULL, array $responseParameters=NULL) {

        $orderManager = new WebOrderManager($request, $responsePath, $responseParameters);

        $order = $orderFactory->buildOrder();
        $order->setId('name'); 
        $order->setField('name');
        $order->setName('Producto');
        $order->setMode('ASC');
        $order->setResponsePath($responsePath);
        $order->setResponseParameters($responseParameters);
        $orderManager->addOrder($order);
        
        
        $order = $orderFactory->buildOrder();
        $order->setId('price'); 
        $order->setField('basePrice');
        $order->setName('Precio');
        $order->setMode('ASC');
        $order->setResponsePath($responsePath);
        $order->setResponseParameters($responseParameters);
        $orderManager->addOrder($order);

        $orderManager->initialize();
        
        return $orderManager;
    }
    
    public function __construct(Request $request, string $responsePath=NULL, array $responseParameters=NULL) {
        $this->setRequest($request);
        $this->setResponsePath($responsePath);
        $this->setResponseParameters($responseParameters);
    }
    
    public function initialize($default = NULL){
        
        $responseParameters = $this->getResponseParameters();
        if($responseParameters == NULL){
            $responseParameters = Utils::getInstance()->getAllParametersFromRequestAndQuery($this->getRequest());
        }
        $this->setResponseParameters($responseParameters);
        
        $query = Utils::getInstance()->getParametersByRequestMethod($this->getRequest());
        
        $modeId = $query->get(WebOrderingUtils::REQUEST_QUERY_PARAM);
        $id = WebOrderingUtils::getIdFromModeId($modeId);
        $mode = WebOrderingUtils::getModeFromModeId($modeId);
        
        if($default != NULL){
            if($field == NULL){
                if(array_key_exists(0, $default)){
                $field = $default[0];    
                }                
            }
            
            if($mode == NULL){
                if(array_key_exists(1, $default)){
                    $mode = $default[1];
                }
            }
        }
        
        //Set actual order as current
        if($id!=NULL){
            if(isset ($this->orders[$id])){
                $order = $this->orders[$id];
                $order->setMode($mode);
                $this->setCurrentOrder($order);
            }
        }
        //Set actual order as current
    }
    
    /**
     *
     * @return IOrderManager
     */
    public function switchMode() {
        
        $currentOrder = $this->getCurrentOrder();
        $currentOrder->switchMode();

        return $this;
    }
    
    public function getValues(){
        $currentOrder = $this->getCurrentOrder();
        return $currentOrder->getValues();
    }
  
    /**
     *
     * @param WebOrder $order
     * @return WebOrderManager 
     */
    public function addOrder(WebOrder $order){

        if($order == NULL){
            throw new \Exception ("Custom Exception: order is null in addOrder function");
        }
        
        $order->setOrderManager($this);
        
        $isCurrent = false;
        if($this->orders == NULL){
            $this->orders = array();
            $isCurrent = true;
        }
        
        $this->orders[$order->getId()]=$order;
        
        if($isCurrent== true){
            $this->setCurrentOrder($order);
        }
        
        return $this;
    }
    
    public function getRequest() {
        return $this->request;
    }

    public function setRequest($request) {
        $this->request = $request;
    }
    
    public function getResponsePath() {
        return $this->responsePath;
    }

    public function setResponsePath($responsePath) {
        $this->responsePath = $responsePath;
    }

    public function getResponseParameters() {
        return $this->responseParameters;
    }

    public function setResponseParameters($responseParameters) {
        $this->responseParameters = $responseParameters;
    }

    /**
     *
     * @return string
     */
    public function getField() {
        $current = $this->getCurrentOrder();
        return $current->getField();
    }

    public function setField($field) {
        $current = $this->getCurrentOrder();
        $current->setField($field);
    }

    public function getMode() {
        $current = $this->getCurrentOrder();
        return $current->getMode();
    }

    public function setMode($mode) {
        $current = $this->getCurrentOrder();
        $current->setMode($mode);
    }
    
    /**
     *
     * @return Order
     */
    public function getCurrentOrder(){
        return $this->currentOrder;
    }
    
    /**
     *
     * @param WebOrder $currentOrder 
     */
    public function setCurrentOrder($currentOrder){
        //Reset currents
        if($this->getOrders() != NULL){
            
            foreach ($this->orders as $key => $cOrder) {
                $cOrder->setIsCurrent(false);
            }
            //End Reset Currents
            $this->currentOrder = $currentOrder;
            $this->currentOrder->setIsCurrent(true);
        }
        
    }
    
    public function getOrders(){
        return $this->orders;
    }
}

?>
