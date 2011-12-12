<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MQMTech\ToolsBundle\Service\Ordering;
/**
 * Description of OrderingUtils
 *
 * @author mqmtech
 */
class WebOrderingUtils {

    const REQUEST_QUERY_PARAM = "sort";
    
    //Keys
    const KEY_ID = "id";
    //End Keys
    
    //Values
     const VALUE_MODE_ASC = "ASC";
     const VALUE_MODE_ASC_SYMBOL = "+";
     const VALUE_MODE_DESC = "DESC";
     const VALUE_MODE_DESC_SYMBOL = "-";
    //End Values
    
    
    public static function getModeFromModeId($str) {
        if ($str == NULL) {
            return NULL;
        }
        $mode = substr($str, 0, 1);
        if ($mode == self::VALUE_MODE_DESC_SYMBOL) {
            return self::VALUE_MODE_DESC;
        }
        else
            return self::VALUE_MODE_ASC;
    }

    public static function getIdFromModeId($str) {
        if ($str == NULL) {
            return NULL;
        }
        $mode = substr($str, 0, 1);

        if ($mode == self::VALUE_MODE_DESC_SYMBOL || $mode == self::VALUE_MODE_ASC_SYMBOL) {
            $length = strlen($str);
            return substr($str, 1, $length - 1);
        }
        return $str;
    }

    public static function generateModeId($mode, $id) {
        $modeid = "";
        if ($mode != NULL) {
            if ($mode == self::VALUE_MODE_DESC) {
                $modeid = self::VALUE_MODE_DESC_SYMBOL;
            }
        }
        if ($id != NULL) {
            $modeid .= $id;
        }
        return $modeid;
    }
    

}

?>
