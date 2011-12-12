<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tecnokey\ShopBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;

use MQMTech\ToolsBundle\Service\IO\PropertiesReader;
use MQMTech\ToolsBundle\Service\Utils;

class MarketManager{
    
    private static $FILE_PATH = NULL;
    private static $instance = NULL;
    private static $PROPERTIES = NULL;
    
    private $location = null;
    private $currency = null;
    private $tax = null;
    
    /**
     *
     * @return MarketManager
     */
    public static function getInstance(){
        if(self::$instance == NULL){
            self::$instance = new MarketManager();
        }
        return self::$instance;
    }
    
    public static function getProperties(){
        if(self::$PROPERTIES == NULL){
            $filepath = self::$FILE_PATH; //__DIR__.'/../Property/market_properties.ini';
            self::$PROPERTIES = parse_ini_file($filepath); 
        }
        return self::$PROPERTIES;
    }
    
    /**
     *
     * @return integer
     */
    public function getIva(){
        
        if($this->tax == null){
            $location = $this->getLocation();

            $props = self::getProperties();
            if($props != NULL){
                $this->tax = $props['tax_'.$location];
            }
            else{
            }
        }
        return $this->tax;
    }
    
    /**
     * @param float $number
     */
    public function roundoffCurrency($number){
        $roundoff = (float)($number * 100.0) + (float) 0.5;
        $roundoff = (floor($roundoff) / 100.0) ;
        return $roundoff;
    }
    
    /**
     *
     * @return string
     */
    public function getLocation() {
        
        if($this->location == null){
            $props = self::getProperties();
            if($props != NULL){
                $this->location = $props['default_location'];
            }
            else{
            }
        }
        return $this->location;
    }

    /**
     *
     * @param string $location 
     */
    public function setLocation($location) {
        $this->location = $location;
    }

    /**
     *
     * @return float
     */
    public function getCurrency() {
        if($this->currency == null){
            $location = $this->getLocation();
            
            $props = self::getProperties();
            if($props != NULL){
                $this->currency = $props['currency_'.$location];
            }
            else{
            }
        }
        return $this->currency;
    }
    
    /**
     * @return string
     */
    public function getCurrencySymbol(){
        $currency = $this->getCurrency();
        
        $props = self::getProperties();
        $currencySymbol = "";
        if($props != NULL){
            $currencySymbol = $props['currency_symbol_'.$currency];
        }
        else{
        }
        
        return $currencySymbol;
    }

    /**
     *
     * @param float $currency 
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    public function __construct() {
        self::$FILE_PATH =  __DIR__.'/../Property/market_properties.ini';
    }
    
}