<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ToolsBundle\Service\Ordering;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use MQMTech\ToolsBundle\Service\Utils;
/**
 * Description of Order
 *
 * @author mqmtech
 */
class WebOrder implements IOrder{
    
    const DEF_ID = "NO_ID";
    const DEF_FIELD = "NO_FIELD";
    const DEF_MODE = "ASC";
    const DEF_NAME = "NO_NAME";
    
    /**
     *
     * @var string $id
     */
    private $id;

    /**
     *
     * @var string $field
     */
    private $field;
    
    /**
     *
     * @var string $mode
     */
    private $mode;    
    
    /**
     *
     * @var string $name
     */
    private $name;
    
    /**
     *
     * @var WebOrderManger
     */
    private $orderManager;
    
    /**
     *
     * @var boolean $url 
     */
    private $isCurrent;
    
    /**
     *
     * @var Request $request
     */
    private $request;
    
    /**
     *
     * @var Router $router
     */
    private $router;
    
    /**
     *
     * @var string $responsePath
     */
    private $responsePath;
    
    /**
     *
     * @var array $responseParameters
     */
    private $responseParameters;
    
    /**
     *
     * @param Request $request
     * @param Router $router
     * @param type $id
     * @param string $field
     * @param string $mode
     * @param string $name
     * @param string $responsePath
     * @param array $responseParameters 
     */
    public function __construct(Request $request, Router $router, $id = self::DEF_ID, $field = self::DEF_FIELD, $mode = self::DEF_MODE,$name = self::DEF_NAME, string $responsePath=NULL, $responseParameters=NULL) {
        $this->setRequest($request);
        $this->setRouter($router);
        $this->setId($id);
        $this->setField($field);
        $this->setMode($mode);
        $this->setName($name);
        
        $this->setResponsePath($responsePath);
        $this->setResponseParameters($responseParameters);
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

        
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     *
     * @return WebOrderManager
     */
    public function getOrderManager() {
        return $this->orderManager;
    }

    public function setOrderManager($orderManager) {
        $this->orderManager = $orderManager;
    }

        
    public function getIsCurrent() {
        return $this->isCurrent;
    }

    public function setIsCurrent($isCurrent) {
        $this->isCurrent = $isCurrent;
    }
    
        
    public function getField() {
        return $this->field;
    }

    public function setField($field) {
        $this->field = $field;
    }

    public function getMode() {
        return $this->mode;
    }

    public function setMode($mode) {
        $this->mode = $mode;
    }
    
    /**
     *
     * @return string
     */
    public function getURL() {
        
        $url = "";
        $parameters = $this->getResponseParameters();
        if($parameters == NULL){
            $parameters = Utils::getInstance()->getAllParametersFromRequestAndQuery($this->getRequest());
        }
        $parameters[WebOrderingUtils::REQUEST_QUERY_PARAM] = WebOrderingUtils::generateModeId($this->getMode(), $this->getId());
       
        $path = $this->getResponsePath();
        if($path == NULL){
            $path = $this->getRequest()->getPathInfo();
            $path = $this->getRequest()->getUriForPath($path);

            $url = $path . Utils::getInstance()->toQueryString($parameters);
        }
        else {
            $url = $this->getRouter()->generate($path, $parameters);
        }
        
        return $url;
    }
        
    public function switchMode() {
        $currentMode = $this->getMode();
        if($currentMode == WebOrderingUtils::VALUE_MODE_ASC){
            $this->setMode(WebOrderingUtils::VALUE_MODE_DESC);
        }
        else{
            $this->setMode(WebOrderingUtils::VALUE_MODE_ASC);
        }
    }
    
    /**
     *
     * @return array
     */
    public function getValues(){
        return array($this->getField() => $this->getMode());
    }
}

?>
