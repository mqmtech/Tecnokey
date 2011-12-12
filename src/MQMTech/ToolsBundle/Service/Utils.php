<?php

namespace MQMTech\ToolsBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Utils{
    
    public static $TRUNCATE_DEFAULT_MAX_LENGTH = 160;
    private static $instance = NULL;
    
   /**
    *
    * @return Utils
    */
    public static function getInstance(){
        if(self::$instance == NULL){
            self::$instance = new Utils();
        }
        return self::$instance;
    }
    
    /**
     *
     * @param \DateTime $date
     * @return \DateTime
     */
    public function convertDateTimeToTimeStamp($date){
        if($date == NULL){
            return  0;
        }
        return $date->getTimestamp();
    }
    
    /**
     * @param string $str
     */
    public function firstLetterCapital($str){
        //$cadena = utf8_decode($cadena);
        //$cadena = utf8_encode(ucwords(strtolower($cadena)));
        $str = strtolower($str);
        $str = ucfirst($str);
        return $str;
    }
    
    /**
     * @param float $number
     */
    public function roundoff($number){
        $roundoff = (float)($number * 100.0) + (float) 0.5;
        $roundoff = (floor($roundoff) / 100.0) ;
        return $roundoff;
    }
    
    /**
     *
     * @param string $word
     * @param integer $maxLength
     * @param string $moreInfoLink
     * @return string 
     */
    public function truncate($word, $maxLength = NULL, $moreInfoLink=NULL) {

        if ($word == NULL) {
            return NULL;
        }
        
        if($maxLength == NULL){
            $maxLength = self::$TRUNCATE_DEFAULT_MAX_LENGTH;
        }

        $length = strlen($word);
        if ($length >= $maxLength) {
            $newStr = substr($word, 0, $maxLength);
            //echo $newStr;
            if($moreInfoLink != NULL){
                $newStr.="<a href='" . $moreInfoLink . "'>...</a>";
            }
            else{
                $newStr.="...";
            }
            return $newStr;
        } 

        return $word;
    }
    
    /**
     *
     * @param array $array
     * @return string 
     */
    public function toQueryString($array){
        
        if($array == NULL){
            return NULL;
        }
        
        $querystring = "";
        
        $count = 0;
        foreach ($array as $key => $value) {
            if($count == 0){
                $querystring.="?";
            }
            else{
                $querystring.="&";
            }
            
            $querystring .=$key ."=".$value;
                    
            $count++;
        }
        
        return $querystring;
    }
    
    /**
     *
     * @param string $input
     * @return string 
     */
    function cleanInput($input) {

        $search = array(
            '@<script[^>]*?>.*?</script>@si', // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        );

        $output = preg_replace($search, '', $input);
        return $output;
    }

    /**
     *
     * @param string $input
     * @return string 
     */
    function sanitize($input) {
        if (is_array($input)) {
            foreach ($input as $var => $val) {
                $output[$var] = $this->sanitize($val);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $input = $this->cleanInput($input);
            $output = mysql_real_escape_string($input);
        }
        return $output;
    }
    
    /**
     *
     * @param Request $request
     * @return ParameterBag
     */
    public function getParametersByRequestMethod($request){
        if($request  == NULL){
            return NULL;
        }
        
        $method = $request->getMethod();
        
        $query = NULL;
        if($method == 'POST'){
            $query = $request->request;
        }
        else{
            $query = $request->query;
        }
        return $query;
    }
    
    /**
     *
     * @param Request $request
     * @return Array 
     */
    public function getAllParametersFromRequestAndQuery($request){
        if($request  == NULL){
            return NULL;
        }
        
        $parameters = array();
        $paramRequest = $request->request->all();
        $paramQuery = $request->query->all();
        $parameters = array_merge($paramQuery, $paramRequest);        
        
        return $parameters;
    }

}
