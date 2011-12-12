<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ToolsBundle\Service\Pagination;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
/**
 * Description of WebPageFactory
 *
 * @author mqmtech
 */
class WebPageFactory implements IPageFactory{

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
    
    public function buildPage() {
        $page = new WebPage($this->getRequest(), $this->getRouter());
        return $page;
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
