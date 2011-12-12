<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ToolsBundle\Service\Pagination;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use MQMTech\ToolsBundle\Service\Utils;
/**
 * Description of WebPage
 *
 * @author mqmtech
 */
class WebPage implements IPage{
    
    const DEF_ID = 0;
    const DEF_OFFSET = 0;
    const DEF_LIMIT = 0;
    const DEF_IS_CURRENT = 0;
    
    private $offset;
    
    private $limit;
    
    private $isCurrent;
    
    private $responseParameters;
    
    private $responsePath;
    
    
    private $request;
    
    private $router;
    
    public function __construct(Request $request, Router $router, $id = self::DEF_ID, $offset = self::DEF_OFFSET, $limit = self::DEF_LIMIT, $isCurrent = self::DEF_IS_CURRENT, $responsePath=NULL, $responseParameters=NULL) {
        $this->setRequest($request);
        $this->setRouter($router);
        
        $this->setId($id);
        $this->setOffset($offset);
        $this->setLimit($limit);
        $this->setIsCurrent($isCurrent);
        
        $this->setResponsePath($responsePath);
        $this->setResponseParameters($responseParameters);
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    
    public function getIsCurrent() {
        return $this->isCurrent;
    }
    public function getLimit() {
        return $this->limit;
    }
    public function getOffset() {
        return $this->offset;
    }
    public function setIsCurrent($isCurrent) {
        $this->isCurrent=$isCurrent;
    }
    public function setLimit($limit) {
        $this->limit = $limit;
    }
    public function setOffset($offset) {
        $this->offset = $offset;
    }
    
    public function getResponseParameters() {
        return $this->responseParameters;
    }

    public function setResponseParameters($responseParameters) {
        $this->responseParameters = $responseParameters;
    }

    public function getResponsePath() {
        return $this->responsePath;
    }

    public function setResponsePath($responsePath) {
        $this->responsePath = $responsePath;
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
    
    public function getURL(){
        
        $url = "no_url";
        
        $parameters = NULL;
        if($this->getResponseParameters() != NULL){
            $parameters = $this->getResponseParameters();
        }
        else{
            $parameters = Utils::getInstance()->getAllParametersFromRequestAndQuery($this->getRequest());
        }

        $parameters[WebPagination::REQUEST_QUERY_PARAM]= $this->getId();

        if($this->getResponsePath() == NULL){
            $path = $this->getRequest()->getPathInfo();
            $path = $this->getRequest()->getUriForPath($path);

            $url = $path . Utils::getInstance()->toQueryString($parameters);
        }
        else {
            $url = $this->getRouter()->generate($this->getResponsePath(), $parameters);
        }
        
        return $url;
    }
}

?>
