<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ToolsBundle\Service\Ordering;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
/**
 * Description of WebPageFactory
 *
 * @author mqmtech
 */
class WebOrderFactory implements IOrderFactory{

    /*
     * var Request $request
     */
    private $request;
    
    /**
     * var Router $router
     */
    private $router;
    
    /**
     *
     * @param Request $request
     * @param Router $router 
     */
    public function __construct(Request $request, Router $router) {
        $this->setRequest($request);
        $this->setRouter($router);
    }
    
    public function buildOrder() {
        $order = new WebOrder($this->getRequest(), $this->getRouter());
        return $order;
    }
    
    public function getRequest() {
        return $this->request;
    }

    public function setRequest($request) {
        $this->request = $request;
    }

    public function getRouter() {
        return $this->router;
    }

    public function setRouter($router) {
        $this->router = $router;
    }
}

?>
