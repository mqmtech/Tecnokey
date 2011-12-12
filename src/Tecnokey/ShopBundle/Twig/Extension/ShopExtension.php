<?php

# Test/MyBundle/Twig/Extension/MyBundleExtension.php

namespace Tecnokey\ShopBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use MQMTech\ToolsBundle\Service\Utils;

use Tecnokey\ShopBundle\Service\MarketManager;

class ShopExtension extends \Twig_Extension {
    
    /**
     *
     * @var Container
     */
    private $container;
    
    /**
     *
     * @var Router
     */
    private $router;
    
    /**
     *
     * @var Utils
     */
    private $utils;
    
    /**
     *
     * @var MarketManager
     */
    private $marketManager;
    
    
    /**
     *
     * @param Container $container The container is only used to retrieve objects that belongs to a narrower scope
     * @param Router $router
     * @param Utils $utils
     * @param MarketManager $marketManager 
     */
    public function __construct(Container $container, Router $router, Utils $utils, MarketManager $marketManager) {
        $this->container = $container;
        $this->router = $router;
        $this->utils = $utils;
        $this->marketManager = $marketManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            'totime' => new \Twig_Function_Method($this, 'toTime'),
            'truncate' => new \Twig_Function_Method($this, 'truncate'),
            'currencySymbol' => new \Twig_Function_Method($this, 'currencySymbol'),
            'noPriceAccess' => new \Twig_Function_Method($this, 'noPriceAccess'),
            'appPath' => new \Twig_Function_Method($this, 'appPath'),
        );
    }
    
    public function getFilters() {
        return array(
            'totime' => new \Twig_Filter_Method($this, 'toTime'),
            'truncate' => new \Twig_Filter_Method($this, 'truncate'),
            'addTax' => new \Twig_Filter_Method($this, 'addTax'),
            'firstLetterCapital' => new \Twig_Filter_Method($this, 'firstLetterCapital'),
            'roundoff' => new \Twig_Filter_Method($this, 'roundoff'),
            'toAppPath' => new \Twig_Filter_Method($this, 'toAppPath'),
            'floor' => new \Twig_Filter_Method($this, 'floor'),
            'displace' => new \Twig_Filter_Method($this, 'replace'),
        );
    }

    /**
     * Converts a string to time
     * 
     * @param string $string
     * @return int 
     */
    public function toTime($string) {
        return strtotime($string);
    }
    
    /**
     *
     * @param string $subject
     * @param string $search
     * @param string $replace
     * @return type 
     */
    public function replace($subject, $search, $replace) {
        return str_replace($search, $replace, $subject);
    }
    
    /**
     *
     * @param string $str
     * @param Array $arr
     * @return string 
     */
    public function appPath($controllerName, $parameters) {
        $url = $this->router->generate($controllerName, $parameters);
        
        return $this->toAppPath($url);
    }
    
    /**
     *
     * @param string $path
     * @return string
     */
    public function toAppPath($path){
        $baseUrl = $this->container->get('request')->getBaseUrl();
        
        $basePos = strpos($path, $baseUrl);
        if($basePos === false){
            return $path;
        }
        else{
            $lenBase = $basePos + strlen($baseUrl);
        }
        
        $lenPath = strlen($path);
        
        $path = substr($path, $lenBase, $lenPath -1);
        return $path;
    }



    public function truncate($word, $maxLength = NULL, $moreInfoLink=NULL) {
        return $this->utils->truncate($word, $maxLength, $moreInfoLink);
    }
    
    /**
     *
     * @param float $value 
     * @return float
     */
    public function addTax($value){
        if($value == NULL) return NULL;
        
        $currentTax = $this->marketManager->getIva();
        return $value * (1.0 + $currentTax);
    }
    
    /**
     *
     * @param string $string 
     */
    public function currencySymbol($currency = NULL){
        if($currency == NULL){
            $currencySymbol = $this->marketManager->getCurrencySymbol();
        }
         return $currencySymbol;
    }
    
    public function noPriceAccess(){
        $properties = $this->marketManager->getProperties();
        if($properties == NULL){
            return 'No Properties';
        }
        return $properties['no_price_access'];
    }
    

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'Shop';
    }
    
    /**
     * @param string $str
     */
    public function firstLetterCapital($str){
        return $this->utils->firstLetterCapital($str);
    }
    
    /**
     * @param float $number
     */
    public function roundoff($number){
        return $this->utils->roundoff($number);
    }
    
    /**
     *
     * @param float $number
     * @return float 
     */
    public function floor($number){
        return floor($number);
    }

}